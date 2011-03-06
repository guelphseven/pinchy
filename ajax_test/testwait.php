<?php
	if (isset($_POST['length']))
		sleep($_POST['length']);
	else
		sleep(15);
	echo "OK";
?>