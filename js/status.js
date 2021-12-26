setInterval(function() {
    load_data();
}, 2000);



setInterval(function() {

	if (parseInt($('#uptime-seconds').html()) === 59) {
		$('#uptime-seconds').html(0);
		if (parseInt($('#uptime-minutes').html()) === 59) {
			$('uptime-minutes').html(0);
			if (parseInt($('#uptime-hours').html()) === 23) {
				$('#uptime-hours').html(0);
				$('#uptime-days').html(parseInt($('#uptime-days').html()) + 1);
			} else {
				$('#uptime-hours').html(parseInt($('#uptime-hours').html()) + 1);
			}
		} else {
			$('#uptime-minutes').html(parseInt($('#uptime-minutes').html()) + 1);
		}
	} else {
		$('#uptime-seconds').html(parseInt($('#uptime-seconds').html()) + 1);
	}

}, 1000);



function load_data(){
	$.get('ajaxinfo.php', function(data) {

		info = $.parseJSON(data);

		services = info[0];
		services_length = services.length;
		var i = 0;
		$('#services li').each(function()
		{
			if (services[i] === true)
			{
				$(this).not('.online').addClass('online');
			}
			else
			{
				if ($(this).hasClass('online'))
				{
					$(this).removeClass('online');
				}
			}

			i++;
		});

		servers = info[1];
		servers_length = services.length;
		var i = 0;
		$('#servers li').each(function()
		{
			if (servers[i] === true)
			{
				$(this).not('.online').addClass('online');
			}
			else
			{
				if ($(this).hasClass('online'))
				{
					$(this).removeClass('online');
				}
			}

			i++;
		});

		cpuload = info[2];
		$('#cpuload-1').html(cpuload[0]);
		$('#cpuload-5').html(cpuload[1]);
		$('#cpuload-15').html(cpuload[2]);
        
        memory_progress(info[3]);
        disk_progress(info[4]);
        
	});
}



function memory_progress(mem){
    var used_box = (mem['usage'] / mem['total'] * 100);
    var cached_box = (mem['cached'] / mem['total'] * 100);
    var used_txt = used_box / 2;
    var cached_txt = (cached_box / 2) + used_box;
    
    $('#mem_used_box').html(mem['usage'] + " " + mem['tag']);
    $('#mem_cached_box').html(mem['cached'] + " " + mem['tag'] + " (Cached)");
    document.getElementById("mem_used_box").style.width = used_box + "%";
    document.getElementById("mem_cached_box").style.width = cached_box + "%";
    $('#mem_total').html("[Total " + mem['total'] + " " + mem['tag'] + "]");
}



function disk_progress(disk){
	var count = disk['count'];
	for( var i=0; i<count; i++){
		var used_box = (disk[i]['usage'] / disk[i]['total'] * 100);
		var used_txt = used_box / 2;
		$("#disk_used_box_"+i).html(disk[i]['usage'] + " " + disk[i]['tag']);
		document.getElementById("disk_used_box_"+i).style.width = used_box + "%";
		document.getElementById("disk_total_"+i).innerHTML = "[ Path: " + disk[i]['name'] + "] [Total " + disk[i]['total'] + " " + disk[i]['tag'] + "]";
	}
    
    
    
}


