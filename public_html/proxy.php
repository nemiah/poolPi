<?php
#error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);
ini_set('default_socket_timeout', 7);

$control = filter_input(INPUT_GET, "control");
if($control !== null)
	echo file_get_contents("http://$control/werte.htm");

$multi = filter_input(INPUT_GET, "multi");
if($multi !== null){
	$ex = explode(",", $multi);

	$return = array();
	
	foreach($ex AS $server){
		$data = file_get_contents("http://$server/werte.htm");
		if($data !== false)
			$data = json_decode($data);
		else
			$data = new stdClass();
		
		$s = new stdClass();
		$s->server = $server;
		$s->data = $data;
		
		$return[] = $s;
	}
	
	echo json_encode($return, JSON_UNESCAPED_UNICODE);
}
?>