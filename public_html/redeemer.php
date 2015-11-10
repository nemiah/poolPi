<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('default_socket_timeout', 7);

#$m = array("type" => "success", "message" => "Befehl erfolgreich");
#echo json_encode($m, JSON_UNESCAPED_UNICODE);
#die();

$control = filter_input(INPUT_GET, "control");
$id = filter_input(INPUT_GET, "id");
$value = filter_input(INPUT_GET, "value");

$data = file_get_contents("http://192.168.0.$control/modify?0003=1234");
if($data === false){
	$m = array("type" => "error", "message" => "Steuerung nicht erreichbar!");
	die(json_encode($m, JSON_UNESCAPED_UNICODE));
}

file_get_contents("http://192.168.0.$control/modify?$id=$value");
file_get_contents("http://192.168.0.$control/modify?0003=0000");

$m = array("type" => "success", "message" => "Befehl erfolgreich");
echo json_encode($m, JSON_UNESCAPED_UNICODE);
?>