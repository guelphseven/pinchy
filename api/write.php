<?php
require_once 'libmsg.php';
require_once '/home/guelphseven/password.php';
session_start();

if (!isset($_POST['username']) || !isset($_POST['password']))
{
	if (!isset($_SESSION['username']) || !isset($_SESSION['userhash']))
	{
		httpDeath(503);
	}
}
if (!isset($_POST['recipient']) || !isset($_POST['data']))
{
	httpDeath(401);
}
else
{
	if(!startSQLConnection()) {
		httpDeath(503);
	}
	if (isset($_POST['username']))
	{
		$user = $_POST['username'];
		$pass = $_POST['password'];
		if (!isAuthenticatedUser($user, $pass))
		{
			httpDeath(503);
			exit();
		}
	}
	else
	{
		$user = $_SESSION['username'];
		$hash = $_SESSION['userhash'];

		if (!isAuthenticatedUserHash($user, $hash))
		{
			httpDeath(503);
			exit();
		}

	}
	$dest = $_POST['recipient'];
	$data = $_POST['data'];
	
	$destid = usernameToID($dest);
	$userid = usernameToID($user);
	if(!canWriteToFeed($destid, $userid)) {
		httpDeath(401);
	}		

	$data = mysql_real_escape_string($data);
	$tags = "";
	if (isset($_POST['tags']))
	{
		$tags = $_POST['tags'];
	}
	$tags = mysql_real_escape_string($tags);
	if(!writeToFeed($dest, $user, $data, $tags)) {
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
if (!isset($_SESSION['username']))
{
	session_destroy();
}
?>
