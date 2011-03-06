<?php
require '/home/guelphseven/password.php';

function politeDeath() {
	header("HTTP/1.0 418 I'm a teapot");
	exit();
}

function safeArrayValue($array, $key) {
	if(array_key_exists($key, $array)) {
		return $array[$key];
	} else {
		return NULL;
	}
}

function echoFeedAsXML($feed) {
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<msg version="1.0">';
	echo '<feed id="' . $feed['id'] . '" total="' . count($feed['feeditems']) . '">';
	foreach($feed['feeditems'] as $item) {
		echo '<feeditem origin="' . $item['origin'] . '" date="' . $item['time'] . '">';
		echo $item['post'];
		echo '</feeditem>';
	}
	echo '</feed>';
	echo '</msg>';
}

function getFeed($id) {
	global $MYSQL_PASS;
	if(ctype_digit($id))
		if($mysql = mysql_connect('localhost', 'messaging', $MYSQL_PASS))
			if(mysql_select_db('messaging', $mysql))
				if($result = mysql_query("SELECT origin, post, time FROM feeds WHERE id = $id ORDER BY time DESC;", $mysql)) {
					$feed = array('id' => $id, 'feeditems' => array());
					while($row = mysql_fetch_assoc($result)) {
						$feed['feeditems'][] = array('time' => $row['time'], 'origin' => $row['origin'], 'post' => $row['post']);
					}

					return $feed;
				}
	politeDeath();
}

if($id = safeArrayValue($_GET, 'id')) {
	header('Content-Type: text/xml');
	//    print_r($_GET);
	echoFeedAsXML(getFeed($id));
} else {
	politeDeath();
}
?>
