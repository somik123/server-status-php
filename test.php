<?php

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
        'total' => $disk_total,
        'usage' => $disk_usage,
        'tag' => $disk_multiple
    );
    
}

echo "<pre>";
echo $count;
print_r($disk);