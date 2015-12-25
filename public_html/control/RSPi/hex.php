<?php
declare(ticks = 1);

pcntl_signal(SIGTERM, "signal_handler");
pcntl_signal(SIGINT, "signal_handler");

function signal_handler($signal) {
	switch($signal) {
		case SIGTERM:
			print "Caught SIGTERM\n";
		exit;
		
		case SIGKILL:
			print "Caught SIGKILL\n";
		exit;
			
		case SIGINT:
			global $running;
			$running = false;
	}
}

$fd = dio_open('/dev/ttyAMA0', O_RDONLY | O_NOCTTY);# | O_NONBLOCK);

dio_fcntl($fd, F_SETFL, O_SYNC);

dio_tcsetattr($fd, array(
	'baud' => 19200,
	'bits' => 8,
	'stop'  => 1,
	'parity' => 0
)); 



$stats = array();
$requests = array();
$answers = array();

$running = true;
while ($running) {
	$data = dio_read($fd);
	echo bin2hex($data)."\n";
#	echo bin2hex(~$data)."\n";
} 

?>
