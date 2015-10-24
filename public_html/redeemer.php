<?php
$control = filter_input(INPUT_GET, "control");
$id = filter_input(INPUT_GET, "id");
$value = filter_input(INPUT_GET, "value");

file_get_contents("http://192.168.0.$control/modify?0003=1234");
file_get_contents("http://192.168.0.$control/modify?$id=$value");
file_get_contents("http://192.168.0.$control/modify?0003=0000");

?>