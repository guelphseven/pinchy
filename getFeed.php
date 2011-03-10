<?php
	require_once '/home/guelphseven/password.php';
	require_once 'api/libmsg.php';

	session_start();
	if (!isset($_SESSION['username']))
	{
		session_destroy();
		httpDeath(401);
	}
	else
	{
		startSQLConnection();
		$feed = getFeed($_SESSION['username'], 0, 10, 0);

		$i = 0;
		foreach ($feed['feeditems'] as $item)
		{
	?>
	<div class="feed" id="message-<?echo $i;?>">
	<div class="message"><span><?echo $item['post'];?></span></div>
	<div class="messagefooter">
		<div class="from"><span><?echo usernameFromID($item['origin_id']);?></span></div>
		<div class="date"><span><?echo $item['time'];?></span></div>
	</div>
</div>
	<?
			$i++;
		}
?>
<div style="clear:both;">&nbsp </div>
<?
}
?>