<?php
header('Expires: Thu, 01-Jan-70 00:00:01 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

error_reporting(E_ERROR | E_PARSE);
ini_set('default_socket_timeout', 7);

$translate = [];
$pollFile = "/var/www/html/open3A/backend/specifics/steuerungen.poll";
if(file_exists($pollFile)){
	$controls = trim(file_get_contents($pollFile));
	foreach(explode("\n", $controls) AS $line){
		$cex = explode("\t", $line);
		$translate[trim($cex[1])] = trim($cex[0]);
	}
}

#$m = array("type" => "success", "message" => "Befehl erfolgreich");
#echo json_encode($m, JSON_UNESCAPED_UNICODE);
#die();

$control = filter_input(INPUT_GET, "control");
$id = filter_input(INPUT_GET, "id");
$value = filter_input(INPUT_GET, "value");


if(strpos($control, ":") !== false AND isset($translate[trim($control)]))
	$control = $translate[trim($control)];

$data = file_get_contents("http://$control/modify?0003=1234");
if($data === false){
	$m = array("type" => "error", "message" => "Steuerung nicht erreichbar!");
	die(json_encode($m, JSON_UNESCAPED_UNICODE));
}

file_get_contents("http://$control/modify?$id=$value");
file_get_contents("http://$control/modify?0003=0000");

$m = array("type" => "success", "message" => "Befehl erfolgreich");
echo json_encode($m, JSON_UNESCAPED_UNICODE);
?>
