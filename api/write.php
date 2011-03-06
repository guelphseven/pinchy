<?php
require 'libmsg.php';

if(($user = safeArrayValue($_POST, 'username')) &&
		($pass = safeArrayValue($_POST, 'password')) &&
		($data = safeArrayValue($_POST, 'data')) &&
		($dest = safeArrayValue($_POST, 'recipient'))) {
	if(!startSQLConnection()) {
		httpDeath(503);
	}

	if(!canWriteToFeed($user, $pass, $dest)) {
		httpDeath(401);
	}

	$data = mysql_real_escape_string($data);
	if(!writeToFeed($dest, $user, $data)) {
		httpDeath(400);
	}

	httpDeath(200);
} else {
	httpDeath(400);
}
?>
