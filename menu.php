<?
require_once("enc.php");

$v = $_GET["v"];
$decV = decStr($v);
parse_str($decV, $info);
error_log("decV=" . $decV);
$_GET = array_merge($_GET, $info);
$_POST = array_merge($_POST, $info);
$php = $_GET["php"];
include($php);
?>
