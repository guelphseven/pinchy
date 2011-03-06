<?php
function httpDeath($code) {
	$table = array(
		200 => "OK",
		400 => "Bad Request",
		401 => "Unauthorized",
		404 => "Not Found",
		503 => "Service Unavailable"
	);

	header("HTTP/1.0 $code $table[$code]");
	exit();
}

function startSQLConnection() {
	include '/home/guelphseven/password.php';
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

function usernameFromID($id) {
	if($result = mysql_query("SELECT username FROM users WHERE id = '$id';")) {
		$row = mysql_fetch_assoc($result);
		return $row['username'];
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

?>
