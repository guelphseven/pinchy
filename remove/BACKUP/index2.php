<html>
<head>
<title> Users Removed </title>
<p> The following users have been removed: </p>

<?php

require '/home/guelphseven/password.php';

$link = mysql_connect('127.0.0.1','messaging',$MYSQL_PASS);
if(!$link){
	die('Could not conect: ' . mysql_error());
}
$db_selected = mysql_select_db('messaging',$link);
if(!$db_selected){
	die('Can\'t use foo: ' . mysql_error());
}

$users = $_POST['username'];
$localuser = $_POST['localuser'];

if(count($users) == 1){
	$result = mysql_query("DELETE FROM access WHERE reader = $localuser AND writer = $users");
	if(!$result){
		die('Could not run query' . mysql_error());
	}
	$IDC = mysql_query($result);
	if($result){
		echo("<p> $users : Success</p>");
	}
	if(!$result){
		echo("<p> $users : Failure</p>");
	}
}

else if(count($users) > 1){

foreach($users as $user){
	$result = mysql_query("REMOVE FROM access WHERE reader = $localuser AND writer = $user");
	if(!$result){
		die('Could not run query' . mysql_error());
	}
	$IDC = mysql_query($result);
	if(result){
		echo("<p> $user : Success</p>");
	}
	if(!result){
		echo("<p> $user : Failure</p>");
	}
}
}
?>
