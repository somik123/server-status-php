<?php

require_once('conf.php');

// services
foreach ($services_list as $name => $port)
{
	$services[] = (@fsockopen($domain, $port) ? true : false);
}

// servers
if ($servers_list)
{
	foreach ($servers_list as $name => $ip)
	{
		$results = exec('ping -c 1 -w 1 ' . $ip, $output);
		$servers[] = ($results ? true : false);
	}
}

// cpu load
$get_cpuload = file_get_contents('/proc/loadavg');
$cpuload = explode(' ', $get_cpuload);

$cpu = [
	$cpuload[0],
	$cpuload[1],
	$cpuload[2]
];

// mem usage
$get_meminfo = file_get_contents('/proc/meminfo');
//echo $get_meminfo; die();

$meminfo_total = filter_var($get_meminfo[0], FILTER_SANITIZE_NUMBER_INT);
$meminfo_cached = filter_var($get_meminfo[4], FILTER_SANITIZE_NUMBER_INT);
$meminfo_free = filter_var($get_meminfo[1], FILTER_SANITIZE_NUMBER_INT);

if (preg_match('#MemTotal:\s+(\d+)\skB#si', $get_meminfo, $pieces)) {
    $meminfo_total = $pieces[1];
}
if (preg_match('#MemFree:\s+(\d+)\skB#si', $get_meminfo, $pieces)) {
    $meminfo_free = $pieces[1];
}
if (preg_match('#Cached:\s+(\d+)\skB#si', $get_meminfo, $pieces)) {
    $meminfo_cached = $pieces[1];
}
$meminfo_usage = ($meminfo_total - ($meminfo_free + $meminfo_cached));


if ($meminfo_total >= 10485760) {
	$mem_total = round(($meminfo_total / 1048576), 2);
	$mem_cached = round(($meminfo_cached / 1048576), 2);
	$mem_free = round((($meminfo_free + $meminfo_cached) / 1048576), 2);
    $mem_usage = round(($meminfo_usage / 1048576), 2);
	$mem_multiple = 'GB';
} else {
	$mem_total = round(($meminfo_total / 1024), 2);
	$mem_cached = round(($meminfo_cached / 1024), 2);
	$mem_free = round((($meminfo_free + $meminfo_cached) / 1024), 2);
    $mem_usage = round(($meminfo_usage / 1024), 2);
	$mem_multiple = 'MB';
}


$mem = array(
	'total' => $mem_total,
	'cached' => $mem_cached,
    'usage' => $mem_usage,
	'free' => $mem_free,
    'tag' => $mem_multiple
);

// disk usage
$disk_details = shell_exec("df -h | grep sd");
$count = preg_match_all("#\%\s([a-zA-Z0-9\/\-\_]*)#si",$disk_details,$mount_names);

foreach($mount_names[1] as $disk_path){
    $disk_space_total = disk_total_space($disk_path);
    $disk_space_free = disk_free_space($disk_path);
    $disk_space_usage = ($disk_space_total - $disk_space_free);

    if ($disk_space_total > 10737418240) {
        $disk_total = round(($disk_space_total / 1073741824), 2);
        $disk_usage = round(($disk_space_usage / 1073741824), 2);
        $disk_multiple = 'GB';
    } else {
        $disk_total = round(($disk_space_total / 1048576), 2);
        $disk_usage = round(($disk_space_usage / 1048576), 2);
        $disk_multiple = 'MB';
    }
    
    $disk[] = array(
		'name' => $disk_path,
        'total' => $disk_total,
        'usage' => $disk_usage,
        'tag' => $disk_multiple
    );
    
}
$disk['count'] = $count;


// Combine all info for output
$info = array(
	$services,
	$servers,
	$cpu,
	$mem,
	$disk
);

echo json_encode($info,JSON_PRETTY_PRINT);
