<?php
include "class.myatomparser.php";
error_reporting(0);
function parse_feed($usernames, $limit=5) {
	$usernames = str_replace("www.", "", $usernames);
	$usernames = str_replace("http://twitter.com/", "", $usernames);
        $username_for_feed = str_replace(" ", "+OR+from%3A", $usernames);
	$feed = "http://api.twitter.com/1/statuses/user_timeline/".$username_for_feed.".atom?callback=?";
	$cache_rss = file_get_contents($feed);
	if (!$cache_rss) {
		// we didn't get anything back from twitter
		echo "<!-- ERROR: Twitter feed was blank! Using cache file. -->";
	}
        $atom_parser = new myAtomParser($feed);

        $output = $atom_parser->getOutput();	# returns string containing HTML
        echo $output;
	return $tweets;
}

if (isset($_REQUEST["username"])) {
	$username=$_REQUEST["username"];
	parse_feed($username);
}

?>

<html>
<head>
<title>Facebook and the Twitter</title>

<body>
        <form name="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        The Twitter name: <input type="textbox" name="username"/><br/>
        <input type="submit" name="submit" value="Get the tweets" /><br/>
        </form>
        <?php
                if (isset($error))
                   print ($error."<br/>\n");
        ?>
</body>

</html>
