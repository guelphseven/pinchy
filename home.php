<?php
	require '/home/guelphseven/password.php';
	if (!isset($_COOKIE["sessionKey"]) || !isset($_COOKIE["userid"]))
	{
		print("<html>You need to <a href=\"login.php\">login</a> first.</html>");
		exit();
	}
	$key=$_COOKIE['sessionKey'];
	$id=$_COOKIE['userid'];

	$mysql=mysql_connect('localhost', 'messaging', $MYSQL_PASS);
	if (!$mysql || !mysql_select_db('messaging'))
		$error="Something's wrong with our database!";
	else if (!ctype_alnum($key) || ! ctype_digit($id))
		$error="Seriously, WHAT are you doing?";
	else if (mysql_num_rows(mysql_query("select userid from webSessions where userid=".$id." and sessionkey='$key';", $mysql))!=1)
		$error="Your session has expired".mysql_error();

	if (!isset($error))
	{
		$res=mysql_query("select username from users where id=$id");
		$username=mysql_result($res, 0, "username");
	}
	else
	{
		print("<html>");
		print($error."<br/>");
		print("Try <a href=\"login.php\"> logging in.</a>");
		print("</html>");
		exit();
	}

/* Retrieve and format raw XML for posts tagged with $tag, and optionally include the post date */
function getPostsAsHTML($url) {
    if(!$xml = simplexml_load_file($url.$username)) {
        return NULL;
    }

    $posts = $xml->xpath('/rss/channel/item');
    $html = "\n";
    foreach ($posts as $post) {
       $html .= '<h3>' . $post->{'title'} . '</h3>';
       $html .= $post->{'description'};
       $html .= ' <br/><br/>' . $post->{'link'};
       $html .= ' <br/>' . $post->{'pubDate'};
    }

    $html .= "\n";
    
    return $html;
}

?>
<html>
<head><title>Home</title>
<link rel = "stylesheet" type="text/css" href = "style.css">
</head>
<body>
<div id ="container">
Welcome <?php echo $username ?>!<br/><br/>
<a href="add.php" >Add subscriptions</a><br/>
<a href="remove.php">Remove subscriptions</a><br/>

<?php require("twitter-php/index.php"); ?>
<?php echo getPostsAsHTML("http://192.168.11.157/api/read.php?username=".$username); ?>
</div>
</body>
</html>

