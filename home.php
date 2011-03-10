<?php
	require_once '/home/guelphseven/password.php';
	require_once 'api/libmsg.php';

	session_start();
	if (!isset($_SESSION['username']))
	{
		header("Location: login.php");
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
			var username = "<?echo $_SESSION['username'];?>";
			var numPinches = 10;
			var deviceid = "<?echo session_id();?>";
		</script>
		<script src="script.js"></script>
	</head>
	<body>
		<div class="maintitle">
			<img src="img/pinchy_32.png"/>
			<span>Pinchy</span>
			<a href="logout.php">logout</a>
		</div>
		<div class ="container">
			<div class="title"><span class="username"><?echo $_SESSION['username'];?></span> dashboard</div>
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

