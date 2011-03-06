<?php
require 'libmsg.php';
require 'fw/FeedWriter.php';

function renderFeedAsRSS($feed) {
	header('Content-Type: application/rss+xml');
	//Creating an instance of FeedWriter class. 
	$TestFeed = new FeedWriter(RSS2);

	//Setting the channel elements
	//Use wrapper functions for common channel elements
	$username = $feed['username'];
	$id = $feed['id'];
	$TestFeed->setTitle("Pinchy feed for $username");
	$TestFeed->setLink('http://192.168.11.157/api/read.php?username='. $username .'&format=rss');
	$TestFeed->setDescription("Feed of user id: $id, username: $username");

	foreach($feed['items'] as $item) {
		//Create an empty FeedItem
		$newItem = $TestFeed->createNewItem();

		//Add elements to the feed item
		$origin = usernameFromID($item['origin']);
		$date = $item['time'];
		$post = $item['post'];
		$newItem->setTitle("Post for $username from $origin at $date");
		$newItem->setLink('http://192.168.11.157/api/read?username=' . $username . '&format=rss');
		$newItem->setDate($date);
		$newItem->setDescription($post);

		//Now add the feed item
		$TestFeed->addItem($newItem);
	}

	//OK. Everything is done. Now genarate the feed.
	$TestFeed->genarateFeed();
}

function renderFeedAsXML($feed) {

	header('Content-Type: text/xml');
	$username = $feed['username'];
	$id = $feed['id'];
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<pinchy version="1.0">';
	echo '<feed title="Pinchy feed for '.$username.'" id="' . $id . '" total="' . count($feed['feeditems']) . '" description="Feed of user id: '. $id . ', username: ' . $username . '">';
	foreach($feed['items'] as $item) {
		$origin = usernameFromID($item['origin']);
		$date = $item['time'];
		$post = $item['post'];
		echo '<item origin="' . $origin . '" date="' . $date . '">' . $post . '</item>';
	}
	echo '</feed></pinchy>';
}

function getFeed($username) {
	if(!isValidUsername($username)) {
		return NULL;
	}

	if(NULL === ($id = usernameToID($username))) {
		return NULL;
	}

	if($result = mysql_query("SELECT origin, post, time FROM feeds WHERE id = $id ORDER BY time DESC;")) {
		$feed = array('id' => $id, 'username' => $username, 'feeditems' => array());
		while($row = mysql_fetch_assoc($result)) {
			$feed['items'][] = array('time' => $row['time'], 'origin' => $row['origin'], 'post' => $row['post']);
		}

		return $feed;
	}

	return NULL;
}

if($username = safeArrayValue($_GET, 'username')) {
	if(!startSQLConnection()) {
		httpDeath(503);
	}

	if(NULL === ($feed = getFeed($username))) {
		httpDeath(400);
	}

	if(NULL === ($format = safeArrayValue($_GET, 'format'))) {
		$format = "rss";
	}

	switch(strtolower($format)) {
		case 'xml':
			renderFeedAsXML($feed);
			break;
		case 'rss':
			renderFeedAsRSS($feed);
			break;
		case 'json':
			httpDeath(400);
			break;
		case 'atom':
			httpDeath(400);
			break;
	}
} else {
	httpDeath(400);
}
?>
