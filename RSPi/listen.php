<?php
declare(ticks = 1);

if(function_exists("pcntl_signal")){

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
	
	pcntl_signal(SIGTERM, "signal_handler");
	pcntl_signal(SIGINT, "signal_handler");
}

$pi = new poolPi();

$fd = dio_open('/dev/ttyUSB0', O_RDONLY | O_NOCTTY);# | O_NONBLOCK);

if(!$fd)
	exit(0);
	
dio_fcntl($fd, F_SETFL, O_SYNC);

dio_tcsetattr($fd, array(
	'baud' => 19200,
	'bits' => 8,
	'stop'  => 1,
	'parity' => 0
)); 


$running = true;
$buf = "";
while ($running) {
	if($fd === false)
		exit(1);
		
	$data = dio_read($fd);
	if(substr(bin2hex($data), 0, 4) == "0000"){
		$add = "";
		if(substr(bin2hex($buf), 0, 4) == "0000" AND substr(bin2hex($buf), 0, 6) != "000000")
				$add = "00";
		
		$c = $add.bin2hex($buf);
		
		#file_put_contents("read.log", $c."\n", FILE_APPEND);
		
		$pi->save($c);
		
		$buf = $data;
	}
	else
		$buf .= $data;
} 

class poolPi {
	private $values = array();
	private $last = array();
	private $c;
	function __construct(){
		
		$this->c = new SQLite3('/home/pi/poolPi/RSPi/values.db');
		
		$result = $this->c->query("SELECT name FROM sqlite_master WHERE type='table';");
		$tables = array();
		
		while ($table = $result->fetchArray(SQLITE3_ASSOC)) 
			$tables[] = $table['name'];
		
		if(!in_array("poolpi", $tables))
			$this->c->exec('CREATE TABLE poolpi (number INTEGER PRIMARY KEY, data TEXT, name STRING)');
		
		#$this->c->exec("INSERT INTO foo (bar) VALUES ('This is a test')");

		#$result = $this->c->query('SELECT * FROM poolpi');
		#var_dump($result->fetchArray());
		
	}
	
	function save($data){
		$data = strtoupper($data);
		$line = str_split($data, 2);
		if(!isset($line[3]))
			return;
		
		if($line[3] != "68")
			return;
		
		$result = $this->analyze($line);
		
		if(!isset($result[2]->value))
			return;
		
		if(!isset($this->values[$result[0]]))
			$this->values[$result[0]] = null;
		
		if(!isset($this->last[$result[0]]))
			$this->last[$result[0]] = null;
		
		if($this->values[$result[0]] === $result[2]->value)
			return;
		
		if(time() - $this->last[$result[0]] < 600)
			return;
		
		$this->values[$result[0]] = $result[2]->value;
		$this->last[$result[0]] = time();
		
		echo date("d.m.Y H:i:s").": Updating $result[1]: ".$result[2]->value."... ";
		
		$r = $this->c->exec("UPDATE poolpi SET data = '".SQLite3::escapeString(json_encode($result[2], JSON_UNESCAPED_UNICODE))."', name = '".SQLite3::escapeString($result[1])."' WHERE number = '".SQLite3::escapeString($result[0])."';");
		echo "SQL Update: ".($r ? "OK" : "Fehler")."\n";
		
		if(!$this->c->changes()){
			$stmt = $this->c->prepare("INSERT INTO poolpi (number, data, name) VALUES (:number, :data, :name);");
			
			$stmt->bindValue(':number',$result[0] , SQLITE3_INTEGER);
			$stmt->bindValue(':name', $result[1], SQLITE3_TEXT);
			$stmt->bindValue(':data', json_encode($result[2], JSON_UNESCAPED_UNICODE), SQLITE3_TEXT);
			
			$stmt->execute();
		}
	}
	
	function analyze(array $data){
		#print_r($data);
		$r = new stdClass();
		$n = hexdec($data[5]);
		$l = "";
		
		switch($data[5]){
			case "36":
				$l = "Dosierverzögerung";
				
				$r->value = hexdec($data[9].$data[10]);
			break;
		
			case "03":
				$l = "Modultyp";
				
				$r->value = $this->toAscii(array($data[9], $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16], $data[17], $data[18], $data[19], $data[20]));
			break;
		
			case "04":
				$l = "Betriebsart";
				
				$mode = "";
				if($data[9] == "01")
					$mode = "Automatik";
				if($data[9] == "02")
					$mode = "Manuell";
				if($data[9] == "04")
					$mode = "Adaption";
				
				$r->value = $mode;
			break;
			
			case "05":
				$l = "Messwert Cl2";
				
				$r->value = (hexdec($data[9].$data[10]) * 0.01);
				$r->min = (hexdec($data[11].$data[12]) * 0.01);
				$r->max = (hexdec($data[13].$data[14]) * 0.01);
				$r->unit = $this->toAscii(array($data[15], $data[16], $data[17], $data[18], $data[19]));
				
				#echo "Messwert: ".(hexdec($data[9].$data[10]) * 0.01)."; ";
				#echo "Bereich Anfang: ".(hexdec($data[11].$data[12]) * 0.01)."; ";
				#echo "Bereich Ende: ".(hexdec($data[13].$data[14]) * 0.01)."; ";
				
				#echo "Einheit: ".toAscii(array($data[15], $data[16], $data[17], $data[18], $data[19]))."; ";
				#echo "Teiler: ".toAscii(array($data[20]));
			break;
		
			case "06":
				$l = "Messwert pH";
				
				$r->value = (hexdec($data[9].$data[10]) * 0.01);
				$r->min = (hexdec($data[11].$data[12]) * 0.01);
				$r->max = (hexdec($data[13].$data[14]) * 0.01);
				$r->unit = $this->toAscii(array($data[15], $data[16], $data[17], $data[18], $data[19]));
			break;
		}
		
		return array($n, $l, $r);
	}
	
	function toAscii(array $bytes){
		$r = "";
		foreach($bytes AS $b)
			$r .= chr(hexdec($b));

		return $r;
}
}

?>
