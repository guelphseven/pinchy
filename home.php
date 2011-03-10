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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Pinchy - Home</title>
		<link rel = "stylesheet" type="text/css" href = "style.css">
		<link rel = "stylesheet" type="text/css" href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
		<script>
			var username = "<?echo $username;?>";
			var numPinches = 10;
			var deviceid = "<?echo rand();?>";
		</script>
		<script src="script.js"></script>
	</head>
	<body>
		<div class="maintitle">
			Pinchy
			<a href="login.php">login</a>
		</div>
		<div class ="container">
			<div class="title"><span class="username"><?echo $username;?></span> dashboard</div>
			<div class="settings">
				<a id="feedbutton" href="#">Feed</a>
				<a id="allowedbutton" href="#">Allowed Users</a>
				<a id="sendbutton" href="#">Send Message</a>
				<a id="sentbutton" href="#">Sent Messages</a>
			</div>
			<div id="content" class="feedcontainer">
			</div>
			<?php //require("twitter-php/index.php"); ?>
			<div style="clear:both;">&nbsp</div>
		</div>
		<div class="footer">
			<div class="message"><span><a href="http://www.guelphseven.com">The Guelph Seven</a></span></div>
		</div>
	</body>
</html>

