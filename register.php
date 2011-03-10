<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Pinchy - Register</title>
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
			<div class="title">Register</div>
			<img src="img/pinchy_256.png"/>
				<form id="register" method="post" action="registerPost.php">
					<label for='username' accesskey='U'>Username:</label><input type="textbox" name="username" id="username"/>
					<label for='password' accesskey='P'>Password:</label><input type="password" name="password" id="password"/>
					<label for='confirm' accesskey='C'>Confirm Password:</label><input type="password" name="confirm" id="confirm"/>
					<input type="submit" value="Register"/>
					<div class="clear">&nbsp;</div>
				</form>
			</div>
		</div>
		<div class="footer">
			<div class="message"><span><a href="http://www.guelphseven.com">The Guelph Seven</a></span></div>
		</div>
	</body>
</html>
