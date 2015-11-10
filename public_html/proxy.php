<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('default_socket_timeout', 7);

$control = filter_input(INPUT_GET, "control");

echo file_get_contents("http://192.168.0.$control/werte.htm");
?>