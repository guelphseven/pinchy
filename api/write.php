<?php
require 'libmsg.php';

if(($user = safeArrayValue($_POST, 'username')) &&
		(($pass = safeArrayValue($_POST, 'password')) || ($hash = safeArrayValue($_POST, 'hash')))&&
		($data = safeArrayValue($_POST, 'data')) &&
		($dest = safeArrayValue($_POST, 'recipient'))) {
	if(!startSQLConnection()) {
		httpDeath(503);
	}
	
	if ($hash == NULL)
	{
		if(!canWriteToFeed($user, $pass, $dest)) {
			httpDeath(401);
		}
	}
	else
	{
		if(!canWriteToFeedHash($user, $hash, $dest)) {
			httpDeath(401);
		}		
	}

	$data = mysql_real_escape_string($data);
	if(!writeToFeed($dest, $user, $data)) {
		httpDeath(400);
	}
	else
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://localhost?user=".$dest);
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PORT, 8080);

		// $output contains the output string
		$output = curl_exec($ch); 
		curl_close($ch);
	}

	httpDeath(200);
} else {
	httpDeath(400);
}
?>
