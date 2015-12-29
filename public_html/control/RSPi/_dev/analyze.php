<?php
$mysqli = new mysqli("localhost", "poolpi", "SS6zUqpdjbBMpxxz", "poolpi");

/*
$mysqli->query("TRUNCATE log;");
$data = file("read.log");
foreach($data AS $line){
	if(trim($line) == "")
		continue;
	
	$mysqli->query("INSERT INTO log VALUES(NULL, '".trim($line)."');");
}
die();*/

function toAscii(array $bytes){
	$r = "";
	foreach($bytes AS $b)
		$r .= chr(hexdec($b));
	
	return $r;
}

function toFloat(array $bytes){
	
}

echo "<pre>";
echo "         SY SY SY SB SA ZA KB AB FC EB\n";

$za = "";

$t = $mysqli->query("SELECT data, COUNT(*) AS anzahl FROM log WHERE data LIKE '00000010$za%' GROUP BY data ORDER BY COUNT(*) DESC");
while($r = $t->fetch_object()){
	$k = strtoupper($r->data);
	$line = str_split($k, 2);

	echo str_pad($r->anzahl, 3, " ", STR_PAD_LEFT)."x     ".preg_replace('/..(?!$)/', '$0 ', $r->data)." (".  hexdec($line[5]).")\n";

	echo "         SY SY SY SB SA ZA KB AB FC DU DU DU DC EB\n";
	
	$ts = $mysqli->query("SELECT data, COUNT(*) AS anzahl FROM log WHERE data LIKE '00000068__$line[5]%' GROUP BY data ORDER BY COUNT(*) DESC");

	while($rs = $ts->fetch_object()){
		$ks = strtoupper($rs->data);

		$sline = str_split($ks, 2);
		$bytes = hexdec($sline[7]);
		
		echo "   >".str_pad($rs->anzahl, 3, " ", STR_PAD_LEFT)."x ";
		
		for($i = 0; $i < 9; $i++)
			echo $sline[$i]." ";
		
		$check = 0;
		for($i = 9; $i < 9 + $bytes; $i++){
			echo "<span style=\"font-weight:bold;\">".$sline[$i]."</span> ";
			$check += hexdec($sline[$i]);
		}

		$checkHex = str_pad(strtoupper(substr(dechex($check), -2)), 2, "0", STR_PAD_LEFT);
		
		if($sline[$i] == $checkHex)
			echo "<span style=\"color:green;\">".$sline[$i++]."</span> ";
		else
			echo "<span style=\"color:red;\">".$sline[$i++]."($checkHex)"."</span> ";
		
		if($sline[$i] == "16")
			echo "<span style=\"color:green;\">".$sline[$i]."</span> ($bytes)";
		else
			echo "<span style=\"color:red;\">".$sline[$i]."</span> ($bytes)";
		
		switch($sline[5]){
			case "36":
				echo " -> ";
				
				echo "Dosierverzögerung: ".hexdec($sline[9].$sline[10]);
			break;
		
			case "03":
				echo " -> ";
				
				echo "Modultyp: ".toAscii(array($sline[9], $sline[10], $sline[11], $sline[12], $sline[13], $sline[14], $sline[15], $sline[16], $sline[17], $sline[18], $sline[19], $sline[20]))."; ";
			break;
			case "04":
				echo " -> ";
				
				$mode = "";
				if($sline[9] == "01")
					$mode = "Automatik";
				if($sline[9] == "02")
					$mode = "Manuell";
				if($sline[9] == "04")
					$mode = "Adaption";
				
				echo "Betriebsart: ".$sline[9].": $mode";
			break;
			case "05":
			case "06":
				echo " -> ";
				
				echo "Messwert: ".(hexdec($sline[9].$sline[10]) * 0.01)."; ";
				echo "Bereich Anfang: ".(hexdec($sline[11].$sline[12]) * 0.01)."; ";
				echo "Bereich Ende: ".(hexdec($sline[13].$sline[14]) * 0.01)."; ";
				
				echo "Einheit: ".toAscii(array($sline[15], $sline[16], $sline[17], $sline[18], $sline[19]))."; ";
				echo "Teiler: ".toAscii(array($sline[20]));
			break;
		}
		
		echo "\n";
	}
	echo "\n";

}
echo "</pre>";

?>