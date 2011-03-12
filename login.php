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
		else if (mysql_num_rows($result = mysql_query("select username, id from users where username='$username' and password='$hash';", $mysql))!=1)
			$error="Invalid username or password";

		if (!isset($error))
		{
			/*
			$key=genRandomString(32);
			$res=mysql_query("select id from users where username='$username';");
			$id=mysql_result($res, 0, "id");
			setcookie('sessionKey', $key);
			setcookie('userid', $id);

			mysql_query("replace into webSessions (userid, sessionkey) values ('".$id."', '$key');");
 		
			print ("<html>");
			print ("Login successful. <a href=\"home.php\">Continue...</a>");
			print ("</html>");
			*/
			$row = mysql_fetch_assoc($result);
			$id = $row['id'];
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['userhash'] = $hash;
			$_SESSION['userid'] = $id;
			header("Location: home.php");
			exit();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Pinchy - Login</title>
		<link rel = "stylesheet" type="text/css" href = "style.css">
		<link rel = "stylesheet" type="text/css" href = "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/ui-lightness/jquery-ui.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
	</head>
	<body>
		<div class="maintitle">
			<img src="img/pinchy_32.png"/>
			<span>Pinchy</span>
			<a href="login.php">login</a>
		</div>
		<div class ="container">
			<div class="title">Please Login</div>
			<img src="img/pinchy_256.png"/>
			<form name="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<label for="username" accesskey="U">Username:</label><input type="textbox" name="username" id="username"/>
				<label for="pass" accesskey="P">Password:</label><input type="password" name="pass" id="pass"/>
				<input type="submit" name="submit" value="Login" />
				<div class="clear">&nbsp;</div>
			</form>
			<div class="formfooter">
				<a href="register.php">Register</a>
			</div>
		</div>
		<div class="footer">
			<div class="message"><span><a href="http://www.guelphseven.com">The Guelph Seven</a></span></div>
		</div>
	</body>
</html>
