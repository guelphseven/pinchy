<?php
	require '/home/guelphseven/password.php';
	require 'random.php';
	if (isset($_REQUEST["username"]))
	{
		$username=$_REQUEST["username"];
		$password=$_REQUEST["pass"];
		$hash=md5($username.$password);
		$mysql=mysql_connect('localhost', 'messaging', $MYSQL_PASS);
		if (!$mysql || !mysql_select_db('messaging', $mysql))
			$error="Something's wrong with our database!";
		else if (!ctype_alnum($username))
			$error="... That's not even a valid username...";
		else if (mysql_num_rows(mysql_query("select username from users where username='$username' and password='$hash';", $mysql))!=1)
			$error="Invalid username or password";

		if (!isset($error))
		{
			$key=genRandomString(32);
			$res=mysql_query("select id from users where username='$username';");
			$id=mysql_result($res, 0, "id");
			setcookie('sessionKey', $key);
			setcookie('userid', $id);

			mysql_query("replace into webSessions (userid, sessionkey) values ('".$id."', '$key');");
 		
			print ("<html>");
			print ("Login successful. <a href=\"home.php\">Continue...</a>");
			print ("</html>");
			exit();
		}
	}
?>
<html>
<head><title>
	Login
</title></head>
<body>
	<form name="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	Username: <input type="textbox" name="username"/><br/>
	Password: <input type="password" name="pass"/><br/>
	<input type="submit" name="submit" value="Login" /><br/>
	</form>
	<?php
		if (isset($error))
			print ($error."<br/>\n");
	?>
</body>
</html>
