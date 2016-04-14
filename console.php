<?php

function stty($options) {
	exec($cmd = "/bin/stty $options", $output, $el);
	$el AND die("exec($cmd) failed");
	return implode(" ", $output);
}

$echo = false;
$echo = $echo ? "" : "-echo";

$stty_settings = preg_replace("#.*; ?#s", "", stty("--all"));

# Set new ones
stty("cbreak $echo");

while(true){
	$c = fgetc(STDIN);
	echo ord($c)." ";
	
	if($c == ".")
		break;
}

# Return settings
stty($stty_settings);



?>
