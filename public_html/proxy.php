<?php
header('Expires: Thu, 01-Jan-70 00:00:01 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

#error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);
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

		
$control = filter_input(INPUT_GET, "control");
if($control !== null){
	if(strpos($control, ":") !== false AND isset($translate[trim($control)]))
		$control = $translate[trim($control)];
	
	echo file_get_contents("http://$control/werte.htm");
}

$multi = filter_input(INPUT_GET, "multi");
if($multi !== null){
	$ex = explode(",", $multi);

	$return = array();
	
	foreach($ex AS $server){
		$serverOrig = $server;
		if(strpos($server, ":") !== false AND isset($translate[trim($server)]))
			$server = $translate[trim($server)];
		
		$data = file_get_contents("http://$server/werte.htm");
		if($data !== false)
			$data = json_decode($data);
		else
			$data = new stdClass();
		
		$s = new stdClass();
		$s->server = $serverOrig;
		$s->data = $data;
		
		$return[] = $s;
	}
	
	echo json_encode($return, JSON_UNESCAPED_UNICODE);
}
?>
