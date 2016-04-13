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
	system('clear');
	
	echo "Willkommen bei poolPi!\n";
	echo date("d.m.Y H:i:s")."\n";
	echo "\n";
	
	echo "Die IP-Adresse des Systems lautet:\n";
	echo getHostByName(getHostName())."\n";
	
	sleep(1);
}

?>
