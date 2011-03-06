<?php
	require '/home/guelphseven/password.php';
	if (!isset($_COOKIE["sessionKey"]) || !isset($_COOKIE["userid"]))
	{
		print("<html>You need to <a href=\"login.php\">login</a> first.</html>");
		exit();
	}
	$key=$_COOKIE['sessionKey'];
	$id=$_COOKIE['userid'];

	$mysql=mysql_connect('localhost', 'messaging', $MYSQL_PASS);
	if (!$mysql || !mysql_select_db('messaging'))
		$error="Something's wrong with our database!";
	else if (!ctype_alnum($key) || ! ctype_digit($id))
		$error="Seriously, WHAT are you doing?";
	else if (mysql_num_rows(mysql_query("select userid from webSessions where userid=".$id." and sessionkey='$key';", $mysql))!=1)
		$error="Your session has expired".mysql_error();

	if (!isset($error))
	{
		$res=mysql_query("select username from users where id=$id", $mysql);
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

	if (isset($_REQUEST["toAdd"]))
	{
		$toAdd=$_REQUEST["toAdd"];
		if (!ctype_alnum($toAdd))
			$error="Username does not exist!";
		else
		{
			$res=mysql_query("select id from users where username='$toAdd'", $mysql);
			if (mysql_num_rows($res)!=1)
			{
				$error="Username does not exist!";
			}
			else
			{
				$addID=mysql_result($res, 0, "id");
				$res=mysql_query("select * from access where reader=$id and writer=$addID", $mysql);
				if (mysql_num_rows($res)>=1)
					$error="Already subscribed!";
			}
		}

		if (!isset($error))
		{
			mysql_query("insert into access (reader, writer) values ($id, $addID);", $mysql);
			$success="Successfully added ".$toAdd." to your subscriptions.";
		}
	}
?>
<html>
<head><title>Home</title></head>
<body>
<?php if (isset($error)) print($error."<br/>"); 
if (isset($success)) print($success."<br/>"); ?>
<form name="add" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="textbox" name="toAdd" value="" />
<input type="submit" name="submit" value="subscribe"/>
</form>
<a href="home.php">Back</a><br/>
</body>
</html>

