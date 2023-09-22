<?
$loc="/var/www/html/bca/mobile/json/";
session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	exit(0);
}

$_POST["loginId"] = $_SESSION['loginId'];
$_POST["tr"] = "000106";
include $loc."trproc9.php";

//print_r($res);
//print("<br>PING = ");
$ping=$res["out"][0]["ping"];
$port=$res["out"][0]["port"];

if ($ping > "00:02:00") {
	header("location:https://mobile.bcasekuritas.co.id/tlogout.php");
}else if ($port!="7002") {
	header("location:https://mobile.bcasekuritas.co.id/logout.php");
}else{
//	echo "<script>alert('1');</script>";
}


?>
