<?php
require '/home/guelphseven/password.php';

include("fw/FeedWriter.php");
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

function echoFeedAsRSS($feed) {
	//Creating an instance of FeedWriter class. 
	$TestFeed = new FeedWriter(RSS2);

	//Setting the channel elements
	//Use wrapper functions for common channel elements
	$TestFeed->setTitle($feed['id']);
	$TestFeed->setLink('http://www.guelphseven.com/api/read/'. $feed['id'] .'/rss');
	$TestFeed->setDescription('Feed of user id: ' . $feed['id']);

	foreach($feed['feeditems'] as $item) {
			//Create an empty FeedItem
			$newItem = $TestFeed->createNewItem();

			//Add elements to the feed item    
			$newItem->setTitle($feed['id']);
			$newItem->setLink('http://www.guelphseven.com/api/read/' . $feed['id'] . '/rss');
			$newItem->setDate($item['time']);
			$newItem->setDescription($item['post']);

			//Now add the feed item
			$TestFeed->addItem($newItem);
	}

	//OK. Everything is done. Now genarate the feed.
	$TestFeed->genarateFeed();
}

if($id = safeArrayValue($_GET, 'id')) {
	header('Content-Type: text/xml');
	//print_r($_GET);
	echoFeedAsRSS(getFeed($id));
} else {
	politeDeath();
}
?>
