<?php
require_once 'libmsg.php';
require_once 'fw/FeedWriter.php';
require_once '/home/guelphseven/password.php';

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

	foreach($feed['feeditems'] as $item) {
		//Create an empty FeedItem
		$newItem = $TestFeed->createNewItem();

		//Add elements to the feed item
		$origin = usernameFromID($item['origin_id']);
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
	foreach($feed['feeditems'] as $item) {
		$origin = usernameFromID($item['origin_id']);
		$date = $item['time'];
		$post = $item['post'];
		echo '<item origin="' . $origin . '" date="' . $date . '">' . $post . '</item>';
	}
	echo '</feed></pinchy>';
}

function renderFeedAsJSON($feed)
{
	echo json_encode($feed);
}

if($username = safeArrayValue($_GET, 'username')) {
	if(!startSQLConnection()) {
		httpDeath(503);
	}
	
	if (NULL === ($time = safeArrayValue($_GET, 'time')))
	{
		$time = 0;
	}
	
	if (NULL === ($limit = safeArrayValue($_GET, 'limit')))
	{
		$limit = 0;
	}

	if (NULL === ($page = safeArrayValue($_GET, 'page')))
	{
		$page = -1;
	}

	if(NULL === ($feed = getFeed($username, $time, $limit, $page))) {
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
			renderFeedAsJSON($feed);
			break;
		case 'atom':
			httpDeath(400);
			break;
	}
} else {
	httpDeath(400);
}
?>
