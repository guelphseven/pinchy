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
		$res=mysql_query("select username from users where id=$id");
		$username=mysql_result($res, 0, "username");
	}
	else
	{
		print("<html>");
		print($error."<br/>");
		print("Try <a href=\"login.php\"> logging in.</a>");
		print("</html>");
		exit();
	}

$feed = getFeed($username, 0, 10, 0);

	$i = 0;
	foreach ($feed['feeditems'] as $item)
	{
	?>
	<div class="feed" id="message-<?echo $i;?>">
	<div class="message"><span><?echo $item['post'];?></span></div>
	<div class="messagefooter">
		<div class="from"><span><?echo usernameFromID($item['origin_id']);?></span></div>
		<div class="date"><span><?echo $item['time'];?></span></div>
	</div>
</div>
	<?
		$i++;
	}
?>
<div style="clear:both;">&nbsp </div>