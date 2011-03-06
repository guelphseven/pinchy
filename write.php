<?php
require '/home/guelphseven/password.php';

function failureDeath() {
	header("HTTP/1.0 503 Service Unavailable");
	echo "503\n";
	exit();
}

function politeDeath() {
	header("HTTP/1.0 400 Bad Request");
	echo "400\n";
	exit();
}

function impoliteDeath() {
	header("HTTP/1.0 401 Unauthorized");
	echo "401\n";
	exit();
}

function startSQLConnection() {
	global $MYSQL_PASS;
	if(mysql_connect('localhost', 'messaging', $MYSQL_PASS)) {
		if(mysql_select_db('messaging')) {
			return true;
		}
	}
	return false;
}

function safeArrayValue($array, $key) {
	if(array_key_exists($key, $array)) {
		return $array[$key];
	} else {
		return NULL;
	}
}

function isValidUsername($username) {
	if(!ctype_alnum($username) || strlen($username) < 4 || strlen($username) > 20) {
		return false;
	}

	return true;
}

function usernameToID($username) {
	if(isValidUsername($username)) {
		if($result = mysql_query("SELECT id FROM users WHERE username = '$username';")) {
			$row = mysql_fetch_assoc($result);
			return $row['id'];
		}
	}

	return NULL;
}

function isAuthenticatedUser($username, $password) {
	if(isValidUsername($username)) {
		$hash = md5($username . $password);
		if($result = mysql_query("SELECT id FROM users WHERE username = '$username' AND password = '$hash';")) {
			if(mysql_num_rows($result) > 0) {
				return true;
			}
		}
	}

	return false;
}

function canWriteToFeed($username, $password, $recipient) {
	if(!isAuthenticatedUser($username, $password)) {
		return false;
	}

	if(NULL === ($reader = usernameToID($recipient))) {
		return false;
	}

	if(NULL === ($writer = usernameToID($username))) {
		return false;
	}

	if($result = mysql_query("SELECT COUNT(*) FROM access WHERE reader = '$reader' AND writer = '$writer';")) {
		if(mysql_num_rows($result) > 0) {
			return true;
		}
	}

	return false;
}

function writeToFeed($reader, $writer, $data) {
	if(NULL === ($reader_id = usernameToID($reader))) {
		return false;
	}

	if(NULL === ($writer_id = usernameToID($writer))) {
		return false;
	}

	if(mysql_query("INSERT INTO feeds (id, origin, post) VALUES ('$reader_id', '$writer_id', '$data');")) {
		return true;
	}
	return false;
}

if(($user = safeArrayValue($_POST, 'username')) &&
		($pass = safeArrayValue($_POST, 'password')) &&
		($data = safeArrayValue($_POST, 'data')) &&
		($dest = safeArrayValue($_POST, 'recipient'))) {
	if(!startSQLConnection()) {
		failureDeath();
	}
	if(!canWriteToFeed($user, $pass, $dest))
	{
		impoliteDeath();
	}

	if(!writeToFeed($dest, $user, $data)) {
		politeDeath();
	}

	header('HTTP/1.0 200 OK');
} else {
	politeDeath();
}
?>
