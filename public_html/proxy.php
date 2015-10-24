<?php
$control = filter_input(INPUT_GET, "control");

echo file_get_contents("http://192.168.0.$control/werte.htm");
?>