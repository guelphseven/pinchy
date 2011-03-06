<html>
<head>
<title> Remove Users</title>
<h1> REMOVE USERS </h1>
<?php

require '/home/guelphseven/password.php';

$usrnum = "4";
$link = mysql_connect('127.0.0.1','messaging',$MYSQL_PASS);
if(!$link){
	die('Could not connect: ' . mysql_error());
}
$db_selected = mysql_select_db('messaging',$link);
if(!$db_selected){
	die ('Can\'t use foo: ' . mysql_error());
}
$result = mysql_query("SELECT writer FROM access WHERE reader = $usrnum");
if(!$result){
	echo 'Could not run query: ' . mysql_error();
	exit;
}

echo("<form action=\"index2.php\" method=\"post\">");
echo("<input type=\"hidden\" name =\"localuser\" value=\"$usrnum\">");
echo("<input type=\"hidden\" name =\"username\" value\"\"");

while(($row = mysql_fetch_row($result)) != null){
	$result = mysql_query("SELECT username FROM users WHERE id = $row[0]");
	$username = mysql_fetch_row($result);
	echo("<p>$username[0]:<input type=\"checkbox\" name=\"username\" value=\"$row[0]\"></p>");
}
echo("<input type=\"submit\" name=\"formSubmit\" value=\"Submit\" />"); 
?>

