<?php
	require_once '/home/guelphseven/password.php';
	require_once 'api/libmsg.php';

	session_start();
	if (!isset($_SESSION['username']))
	{
		httpDeath(401);
		exit();
	}
	else
	{
		if (!isset($_POST['user']))
		{
			httpDeath(400);
			exit();
		}
		startSQLConnection();
		removeUserIDFromAllow($_SESSION['userid'], $_POST['user']);
	}
?>