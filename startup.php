<?php

system('clear');

function stty($options) {
  exec($cmd = "/bin/stty $options", $output, $el);
  $el AND die("exec($cmd) failed");
  return implode(" ", $output);
}


function turnOn(){
	exec("sudo sh -c \"echo '1' > /sys/class/gpio/gpio508/value\"");
	
	$queue = shell_exec("atq");
	if(trim($queue) != ""){
		$ex = explode("\n", $queue);
		foreach($ex AS $line){
			$sex = explode("	", $line);
			shell_exec("atrm ".trim($sex[0]));
		}
	}
	
	shell_exec("at now + 3min < /home/pi/poolPi/config/at_off.sh");
}

function turnOff(){
	exec("sudo sh -c \"echo '0' > /sys/class/gpio/gpio508/value\"");
}


$stty_settings = preg_replace("#.*; ?#s", "", stty("--all"));

stty("cbreak -echo");

$line = "";
$buffer = "";
while(1){
	turnOn();
	system('clear');
	echo "\n";
	echo " Willkommen bei poolPi!\n";
	
	echo "\n";
	echo "\n";
	echo " Es stehen folgende Befehle zur Verfügung:\n";
	echo "\n";
	echo " ip       - Zeigt die aktuelle IP-Adresse\n";
	echo " open     - Startet die Fernwartung\n";
	echo " close    - Beendet die Fernwartung\n";
	echo "\n";
	echo " reboot   - Startet das System neu\n";
	echo " shutdown - Fährt das System herunter\n";
	echo " exit     - Beendet diese Anwendung\n";
	
	#echo " off     - Beendet diese Anwendung\n";
	#echo " on     - Beendet diese Anwendung\n";
	
	echo "\n";
	
	if($line == "on"){
		turnOn();
		$line = "";
	}
	
	if($line == "off"){
		turnOff();
		$line = "";
	}
	
	if($line == "ip"){
		$ips = explode(" ", exec("hostname --all-ip-addresses"));
		echo " Die IP-Adresse des Systems lautet:\n";
		foreach($ips AS $ip){
			if(strpos($ip, ":") !== false)
				continue;
			
			echo " ".$ip."\n";
		}
		$line = "";
	}
	
	if($line == "open"){
		echo " Starte Fernwartung...\n";
		exec("ssh -o StrictHostKeyChecking=no -R\*:2222:localhost:22 -R8888:localhost:80 -N nemiah@open3a.de > /dev/null 2>&1 &");
		$line = "";
	}
	
	if($line == "close"){
		echo " Beende Fernwartung...\n";
		exec("killall ssh");
		$line = "";
	}
	
	if($line == "exit"){
		stty($stty_settings);
		echo " Beende...\n";
		$line = "";
		break;
	}
	
	if($line == "reboot"){
		echo " Neustart...\n";
		shell_exec("sudo reboot");
		$line = "";
		break;
	}
	
	if($line == "shutdown"){
		echo " Fahre herunter...\n";
		shell_exec("sudo shutdown -h now");
		$line = "";
		break;
	}
	
	echo "\n";
	echo " Befehl: $buffer";
	$c = fgetc(STDIN);
	#echo $c;
	if(ord($c) == 10){
		$line = $buffer;
		$buffer = "";
	}
	
	if(ord($c) == 127){
		if($buffer != "")
			$buffer = substr($buffer, 0, strlen($buffer) - 1);
	}
	
	if(ord($c) == 32){
		#if($buffer != "")
		#	$buffer = substr($buffer, 0, strlen($buffer) - 1);
	}
	
	if(ord($c) >= 97 AND ord($c) <= 122){
		$buffer .= $c;
	}
	if($c === false)
		break;
	#$line = readline(" Befehl: ");
	#echo $line;
		
	#sleep(1);
}


?>
