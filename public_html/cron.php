<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('default_socket_timeout', 7);

if($argc == 1){
	echo "Usage: Usage: php cron.php IP Target Value\n";
	die();
}

$control = $argv[1];
$id = $argv[2];
$value = $argv[3];

$data = file_get_contents("http://$control/modify?0003=1234");
if($data === false)
	exit(1);


file_get_contents("http://$control/modify?$id=$value");
file_get_contents("http://$control/modify?0003=0000");

exit(0);

?>