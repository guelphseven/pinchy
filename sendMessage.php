<?php
	require_once '/home/guelphseven/password.php';
	require_once 'api/libmsg.php';

	session_start();
	if (!isset($_SESSION['username']))
	{
?>
	Sorry; unauthorized.
<?
	}
	else
	{
?>
<form name="submit" method="post" action="api/write.php">
	<label for="recipient">Recipient:</label><input type="textbox" name="recipient" id="recipient"/>
	<label for="data">Data:</label><input type="textbox" name="data" id="data"/>
	<input id="submit" type="submit" id="submit" onclick="return false;" value="Send"/>
	<div class="clear">&nbsp;</div>
</form>
<?
}
?>