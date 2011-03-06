<?php
	$user = $_POST['username'];
	$chat = $_POST['chat'];
	
	$message = "";
	if (rand(0,1) == 1) $from = "Herp";
	else $from = "Derp";

	$length = rand(5, 35);
	for ($i=0; $i<$length; $i++)
	{
		$message .= "blah ";
	}
	sleep(rand(5, 15));
	echo $from . " says: " . $message;
?>