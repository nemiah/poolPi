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
	
	sleep(1);
}

?>
