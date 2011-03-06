<?php
	if (!isset($_POST["user"]))
	{
		die("Need to login");
	}
	$user = $_POST['user'];
	$hash = $_POST['password'];
?>
<html>
	<head>
		<title>Test Chat Client</title>
		<link rel="stylesheet" href="style.css">

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
		<script src="chat.js"></script>
		<script>
			var username = "<?echo $user;?>";
			var hash = "<?echo $hash;?>";
		</script>
	</head>
	<body>
		<div class="container">
			<div class="title">Messaging Service Test Chat</div>
			<div class="left">
				<div class="userlist" id="userlist">
				</div>
				<div class="adduser">
					<button onclick="addUser()">Add User</button>
				</div>
			</div>
			<div class="right">
				<div id="chatwindows" class="chatwindowcontainer">
				</div>
				<div class="form">
					<input type="text" id="message" name="message"/>
					<div style="margin: auto; display: inline; float:right;"><button value="Send" onclick="sendMessage()">Send</button></div>
				</div>
			</div>
		</div>
	</body>
</html>