<?php
	require_once "api/libmsg.php";
	session_start();
	if (!isset($_SESSION['username']))
	{
		httpDeath(401);
		exit();
	}
	
	startSQLConnection();
	$users = getAllowedUsers($_SESSION['userid']);
	if ($users)
	{
		foreach ($users as $user)
		{
?>
<div class="feed" id="user-<?echo $user;?>">
	<div class="user"><span><?echo usernameFromID($user);?></span></div>
	<div class="delete"><span><a href="#" onclick="deleteUser(<?echo $user;?>)">x</a></span></div>
	<div class="clear">&nbsp;</div>
</div>
<?
		}
	}
?>
<div class="feed" id="adduser">
	<span><a href="#" id="addbutton">Add User</a></span>
	<div class="clear">&nbsp;</div>
</div>