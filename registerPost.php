<?php
	require '/home/guelphseven/password.php';
	require 'random.php';
	$username=$_REQUEST["username"];
	$password=$_REQUEST["password"];
	$confirm=$_REQUEST["confirm"];
	$hash=md5($username.$password);
	
	$mysql=mysql_connect('localhost', 'messaging', $MYSQL_PASS);
	if ($mysql)
		mysql_select_db('messaging');
	if (!$mysql)
		$error="Something's wrong with the database";
	else if ($password!=$confirm)
		$error="Passwords don't match";
	else if (!ctype_alnum($username))
		$error="Username can only contain numbers and letters";
	else if (strlen($username)<4 or strlen($username)>20)
		$error="Username must be between 4 and 20 characters.";
	else if (!mysql_query("insert into users (username, password) values ('$username', '$hash');", $mysql))
		$error="Username could not be inserted.<br/>\n".mysql_error();

	if (!isset($error))
	{
		$key=genRandomString(32);
		$res=mysql_query("select id from users where username='$username';");
		$id=mysql_result($res, 0, "id");
		setcookie('sessionKey', $key);
		setcookie('userid', $id);

		mysql_query("replace into webSessions (userid, sessionkey) values ('".$id."', '$key');");
	}

?>

<html>
<head><title>
	<?php 
	if (isset($error)) 
		print ("$error");
	else
		print ("Registration Succeeded");
	?>
</title></head>
<body>
	<?php
		if (isset($error))
		{
			print ("Your account could not be created.\n");
			print ($error);
		}
		else
		{
			print ("The account '$username' has been created. <a href=\"home.php\">Continue...</a>");
		}
	?>
</body>
</html>
