<?php
	session_start();
	$_SESSION["pin"] = '';
	$_SESSION["pinState"] = 0;
	error_log("resetPIN.php");
?>
