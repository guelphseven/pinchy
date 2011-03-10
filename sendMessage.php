<?php
	require_once '/home/guelphseven/password.php';
	require_once 'api/libmsg.php';

	if (!isset($_COOKIE["sessionKey"]) || !isset($_COOKIE["userid"]))
	{
		print("<html>You need to <a href=\"login.php\">login</a> first.</html>");
		exit();
	}
	$key=$_COOKIE['sessionKey'];
	$id=$_COOKIE['userid'];
	startSQLConnection();
	/*
	$mysql=mysql_connect('localhost', 'messaging', $MYSQL_PASS);
	if (!$mysql || !mysql_select_db('messaging'))
		$error="Something's wrong with our database!";
	else if (!ctype_alnum($key) || ! ctype_digit($id))
		$error="Seriously, WHAT are you doing?";
	*/
	if (mysql_num_rows(mysql_query("select userid from webSessions where userid=".$id." and sessionkey='$key';"))!=1)
		$error="Your session has expired".mysql_error();

	if (!isset($error))
	{
		$res=mysql_query("select username, password from users where id=$id");
		$username=mysql_result($res, 0, "username");
		$hash=mysql_result($res, 0, "password");
	}
	else
	{
		print("<html>");
		print($error."<br/>");
		print("Try <a href=\"login.php\"> logging in.</a>");
		print("</html>");
		exit();
	}
?>
<form name="submit" method="post" action="api/write.php">
	<input type="hidden" name="username" value="<?echo $username;?>"/>
	<input type="hidden" name="hash" value="<?echo $hash;?>"/>
	<label for="recipient">Recipient:</label><input type="textbox" name="recipient" id="recipient"/>
	<label for="data">Data:</label><input type="textbox" name="data" id="data"/>
	<input id="submit" type="submit" id="submit" onclick="return false;" value="Send"/>
	<div class="clear">&nbsp;</div>
</form>