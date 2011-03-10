<?php
require 'libmsg.php';
session_start();
if (!isset($_SESSION['username']))
{
	httpDeath(503);
}
else if (!isset($_POST['recipient']) || !isset($_POST['data']))
{
	httpDeath(401);
}
else
{
	$user = $_SESSION['username'];
	$hash = $_SESSION['hash'];
	$dest = $_POST['recipient'];
	$data = $_POST['data'];
	
	if(!startSQLConnection()) {
		httpDeath(503);
	}
	$destid = usernameToID($dest);
	if(!canWriteToFeed($destid, $_SESSION['userid'])) {
		httpDeath(401);
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
}
?>
