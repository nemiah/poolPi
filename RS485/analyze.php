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

echo "<pre>";
echo "         SY SY SY SB SA ZA KB AB FC EB\n";

$za = "08";

$t = $mysqli->query("SELECT data, COUNT(*) AS anzahl FROM log WHERE data LIKE '0000002001$za%' GROUP BY data ORDER BY COUNT(*) DESC");
while($r = $t->fetch_object()){
	$k = $r->data;

	#$k = strtoupper($k);
	$line = str_split($k, 2);
	#print_r($line);

	echo str_pad($r->anzahl, 3, " ", STR_PAD_LEFT)."x     ".preg_replace('/..(?!$)/', '$0 ', strtoupper($r->data))."\n";

	echo "         SY SY SY SB SA ZA KB AB FC DU DU DU DC EB\n";
	
	$ts = $mysqli->query("SELECT data, COUNT(*) AS anzahl FROM log WHERE data LIKE '000000d0__$line[5]%' GROUP BY data ORDER BY COUNT(*) DESC");

	while($rs = $ts->fetch_object()){
		$ks = $rs->data;
		#$ks = strtoupper($ks);
		$sline = str_split($ks, 2);

		echo "   >".str_pad($rs->anzahl, 3, " ", STR_PAD_LEFT)."x ".preg_replace('/..(?!$)/', '$0 ', strtoupper($rs->data)).": ".  strtoupper($sline[8].$sline[9])." -> ".hexdec($sline[8].$sline[9])."\n";

	}
	echo "\n";

}
echo "</pre>";

?>