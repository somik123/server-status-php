<?php

$start = microtime(true);

require('conf.php');
//require('info.php');

?>

<html>
<head>
	<title>status</title>
	<link href="css/status.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
        .progress_bar {
            width: 100%;   
            border: 1px solid black;
            position: relative;
            padding: 2px 2px 4px 2px;
            background-color: #e2e2e2;
            height: 16px;
            border-radius: 8px;
        }

        .bar1_box, .bar2_box {
            background-color: #5cb85c;
            float: left;
            text-align: center;
            padding-top: 2px;
            color: #ffffff;
            font-size: 10pt;
            height: 16px;
            border-radius: 6px;
            overflow: hidden;
        }
        .bar2_box {
            background-color: #5bc0de;
        }

    </style>
</head>

<body onload="load_data()">

	<div id="sidebar">

		<div class="title">services</div>
		<ul id="services">
        <?php
        // services
        foreach ($services_list as $name => $port)
        {
        	echo "<li>".$name."</li>";
        }
        ?>
        </ul>
<?php
// servers
if ($servers_list){
    ?>
		<div class="title">servers</div>
		<ul id="servers">
    <?php

	foreach ($servers_list as $name => $ip){
		echo "<li>".$name."</li>";
	}
?>
        </ul>
<?
}

// uptime
$get_uptime = file_get_contents('/proc/uptime');
$uptime = explode(' ', $get_uptime);

$uptime_days = floor($uptime[0] / 86400);
$uptime_hours = floor(($uptime[0] / 3600) % 24);
$uptime_minutes = floor(($uptime[0] / 60) % 60);
$uptime_seconds = ($uptime[0] % 60);



// server information
$distros = array(
	'debian_version' => 'Debian',
	'centos-release' => 'CentOS',
	'lsb-release' => 'Ubuntu',
	'redhat-release' => 'Redhat',
	'fedora-release' => 'Fedora',
	'SuSE-release' => 'SUSE',
	'gentoo-release' => 'Gentoo'
);
$distro = 'Unknown';

foreach ($distros as $distro_release => $distro_name) {
	$release_file = '/etc/' . $distro_release;
	if (file_exists($release_file)) {
		$distro = $distro_name;
	}
}

$webserver_info = explode('/', $_SERVER['SERVER_SOFTWARE']);
$webserver = $webserver_info[0];


$get_cpuinfo = file_get_contents('/proc/cpuinfo');
preg_match("#model name\s+:(.*)#mi",$get_cpuinfo,$matches);
$cpu_model = trim($matches[1]);
?>


	</div>

	<div id="main">

		<div class="title">uptime</div>
		<div id="uptime" class="box">
			<div>
				<span id="uptime-days" class="value"><?php echo $uptime_days; ?></span> days, 
				<span id="uptime-hours" class="value"><?php echo $uptime_hours; ?></span> hours, 
				<span id="uptime-minutes" class="value"><?php echo $uptime_minutes; ?></span> minutes and
				<span id="uptime-seconds" class="value"><?php echo $uptime_seconds; ?></span> seconds
			</div>
		</div>

		<div class="title">cpu load</div>
		<div id="cpu" class="box">
			<ul>
				<li><span id="cpuload-1" class="value"></span> (average over the last 1 minute)</li>
				<li><span id="cpuload-5" class="value"></span> (average over the last 5 minutes)</li>
				<li><span id="cpuload-15" class="value"></span> (average over the last 15 minutes)</li>
			</ul>
		</div>

		<div class="title">Memory <span id="mem_total" style="font-weight: bolder;"></span></div>
		<div id="meminfo" class="box">
			<div>
                <div class="progress_bar">
                    <div class="bar1_box" id="mem_used_box"></div>
                    <div class="bar2_box" id="mem_cached_box"></div>
                </div>
			</div>
		</div>


<?php
$disk_details = shell_exec("df -h | grep sd");
$count = preg_match_all("#\%\s([a-zA-Z0-9\/\-\_]*)#si",$disk_details,$mount_names);
for($i=0;$i<$count;$i++){
?>
		<div class="title">Disk space  <span id="disk_total_<?=$i?>"" style="font-weight: bolder;"></span></div>
		<div id="diskinfo_<?=$i?>" class="box">
			<div>
                <div class="progress_bar">
                    <div class="bar1_box" id="disk_used_box_<?=$i?>"></div>
                    <div class="bar2_box" id="disk_cached_box_<?=$i?>"></div>
                </div>
			</div>
		</div>
<?php } ?>


		<div class="title">software</div>
		<div id="software" class="box">
			<ul>
				<li>Server Hostname: <span class="value"><?php echo gethostname(); ?></span></li>
				<li>Server IP: <span class="value"><?php echo $_SERVER['SERVER_ADDR']; ?></span></li>
				<li>Operating System: <span class="value"><?php echo $distro; ?></span></li>
				<li>Webserver: <span class="value"><?php echo $webserver; ?></span></li>
				<li>CPU: <span class="value"><?php echo $cpu_model; ?></span></li>
			</ul>
		</div>

		<div id="footer">
			page generated in <?php echo round(microtime(true)-$start,4); ?> s // <a href="https://somik.org/">Powered by Somik.org</a>
		</div>

	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script src="js/status.js" type="text/javascript"></script>

</body>
</html>