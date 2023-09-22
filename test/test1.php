<?php
	session_start();

echo "loginId=" . $_SESSION["loginId"] . "<br />";

	$loginId = $_SESSION["loginId"];
?>
<html>
<title>loginId=<?=$loginId;?></title>
<script>
//window.location = "login.php";
</script>
</html>
