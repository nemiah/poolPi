<?php
declare(ticks=1);

$running = true;
function signalHandler($signo) {
    global $running;
    $running = false;
    echo "Beende...\n";
}

pcntl_signal(SIGINT, 'signalHandler');

sleep(5);
system('clear');


while($running){
	$ips = explode(" ", exec("hostname --all-ip-addresses"));
	
	system('clear');
	
	echo "Willkommen bei poolPi!\n";
	echo date("d.m.Y H:i:s")."\n";
	echo "\n";
	
	echo "Die IP-Adresse des Systems lautet:\n";
	foreach($ips AS $ip)
		echo $ip."\n";
	
	sleep(1);
}

?>
