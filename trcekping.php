<?
require_once("config.php");
require_once("json/trmap.php");
/*
$_POST["tr"] = "100000";
$_POST["code"] = "ENRG";
$_POST["board"] = "RG";
*/

session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	exit(0);
}

//
//$_POST["time"] = "00:00:00";
$_POST["loginId"] = $_SESSION["loginId"];
$trNo = "000106";

require_once 'json/trproc.php';
?>
