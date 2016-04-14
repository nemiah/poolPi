<?php
declare(ticks=1);

$running = true;
function signalHandler($signo) {
    global $running;
    $running = false;
    echo " Beende...\n";
}

pcntl_signal(SIGINT, 'signalHandler');

#sleep(5);
system('clear');

$line = null;
while($running){
	$ips = explode(" ", exec("hostname --all-ip-addresses"));
	
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
	
	echo "\n";
	
	if($line == "ip"){
		echo " Die IP-Adresse des Systems lautet:\n";
		foreach($ips AS $ip){
			if(strpos($ip, ":") !== false)
				continue;
			
			echo " ".$ip."\n";
		}
	}
	
	if($line == "open"){
		echo " Starte Fernwartung...\n";
		exec("ssh -o StrictHostKeyChecking=no -R\*:2222:localhost:22 -R8888:localhost:80 -N nemiah@open3a.de > /dev/null 2>&1 &");
	}
	
	if($line == "close"){
		echo " Beende Fernwartung...\n";
		exec("killall ssh");
	}
	
	if($line == "exit"){
		echo " Beende...\n";
		die();
	}
	
	if($line == "reboot"){
		echo " Neustart...\n";
		shell_exec("sudo reboot");
		exit;
	}
	
	if($line == "shutdown"){
		echo " Fahre herunter...\n";
		shell_exec("sudo shutdown -h now");
		exit;
	}
	
	echo "\n";
	$line = readline(" Befehl: ");
	#echo $line;
		
	#sleep(1);
}

?>
