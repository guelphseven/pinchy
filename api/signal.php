<?php
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost?user=".$_GET['user']);
	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_PORT, 8080);

	// $output contains the output string
	$output = curl_exec($ch); 
	curl_close($ch); 
	echo "Response = {\n$output\n}";
?>