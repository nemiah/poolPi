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
$buf = "";
while ($running) {
	$data = dio_read($fd);
	if(substr(bin2hex($data), 0, 4) == "0000"){
		$add = "";
		if(substr(bin2hex($buf), 0, 4) == "0000" AND substr(bin2hex($buf), 0, 6) != "000000")
				$add = "00";
		
		$c = $add.bin2hex($buf);
		if(!isset($stats[$c]))
			$stats[$c] = 0;
		
		$stats[$c]++;
		
		file_put_contents("read.log", $c."\n", FILE_APPEND);
		
		$buf = $data;
	}
	else
		$buf .= $data;
} 

system('clear');
arsort($stats);
$i = 0;
echo "         SY SY SY SB SA ZA KB AB FC EB\n";
foreach($stats AS $k => $v){
	if(trim($k) == "")
		continue;

	$k = strtoupper($k);
	$line = str_split($k, 2);

	if($line[3] != "10")
		continue;

	echo str_pad($v, 3, " ", STR_PAD_LEFT)."x     ".preg_replace('/..(?!$)/', '$0 ', $k)."\n";

echo "         SY SY SY SB SA ZA KB AB FC DU DU DU DC EB\n";
	foreach($stats AS $ks => $vs){
		if(trim($ks) == "")
			continue;

		$ks = strtoupper($ks);
		$sline = str_split($ks, 2);

		if($sline[3] != "68" OR $line[5] != $sline[5])
			continue;


		echo "   >".str_pad($vs, 3, " ", STR_PAD_LEFT)."x ".preg_replace('/..(?!$)/', '$0 ', $ks)."\n";

	}
	echo "\n";
	$i++;
}

?>
