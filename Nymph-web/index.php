<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://http://192.168.22.16/tlogout.php"; }, 5 * 60000);</script>';
session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	echo "Please login...";
	$server = $_SERVER["SERVER_NAME"];
	header("Location: http://$server/login.php");	
	exit(0);
}
$ipChk = "";
if (!isset($_SESSION["loginIp"])) {
	$curIp = $_SERVER["REMOTE_ADDR"];
	if (strcmp($curIp, $_SESSION["loginIp"]) != 0) {
		$ipChk = "e?";
	}
}


if(!isset($_GET["uFlag"])){
	if(isset($_SESSION["url"])){
//		header("location:".$_SESSION["url"]);
		//echo "<script>alert('$_SESSION[url]')</script>";
	}
}


$userId = $_SESSION["userId"];
$loginId = $_SESSION["loginId"];
?>
<html>
<meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head> 
	<title>Nymph Mobile[<?=$loginId;?><?=$ipChk;?>]</title> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	//-->
	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			jQuery('div').live('pagehide', function(event, ui){
				var page = jQuery(event.target);
				if(page.attr('data-cache') == 'never'){
					page.remove();
				};
			});
		});

		function openExtMenu() {
			$("#popupmenu").popup("open");
		}
	</script>
	<script type="text/javascript">
	 function onLoad()
	 {
	 document.addEventListener("deviceready",onDeviceReady,false);
	 }
	 function onDeviceReady()
	{
	document.addEventListener("backbutton",noth,false);
	}
	function noth()
	{
		 //code for what happends if back button is pressed
		alert('coba');
	}
	</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header-index.php" ?>
		<div data-role="content">
		<div class="ui-grid-solo">
		<center>
		<img src="img/logo-nymph.png"/>
		</center>
		</div>
		<p>
		<ul data-role="listview" data-inset="true" data-filter="false">
		<?
        if(isset($_SESSION['disclaim']) && $_SESSION['disclaim'] == "disclaim"){
        ?>
			    <li><a href="#" onclick="goWatchlist();return false;">Watchlist</a></li>
			    <li><a href="#" onclick="goCurPrice();return false;">Order Book</a></li>
			    <li><a href="#" onclick="goRunning();return false;">Running Trade</a></li>
			    <li><a href="#" onclick="goBrokerActivity();return false;">Broker Activity</a></li>
			    <li><a href="#" onclick="goBrokerTrans();return false;">Broker Transaction by Stock</a></li>
			    <li><a href="#" onclick="goForeignTrans();return false;">Foreign Transaction Summary</a></li>
			    <li><a href="#" onclick="goGainerLosser();return false;">Top Gainers Lossers</a></li>            
			    <li><a href="#" onclick="goMarketSummary();return false;">Market Summary</a></li>           
        <?php
        }else{
		    if (!isset($_SESSION["pinState"]) || $_SESSION["pinState"] != 1) {
			    $_SESSION['disclaim'] = "notdisclaim";
                echo '<li><a href="#" onclick="goLoginPin();return false;">Input Pin</a></li>';
		    }
		?>
			    <li><a href="#" onclick="goBuyExt();return false;">Buy Order</a></li>
				<li><a href="#" onclick="goSellExt();return false;">Sell Order</a></li>
				<li><a href="#" onclick="goBuyAcExt();return false;">Buy Order Accel</a></li>
				<li><a href="#" onclick="goSellAcExt();return false;">Sell Order Accel</a></li>
                <li><a href="#" onclick="goPortfolio(); return false;">Portfolio</a></li> 
			    <li><a href="#" onclick="goOrderList(); return false;">OrderList</a></li> 
			    <li><a href="#" onclick="goTradeList(); return false;">TradeList</a></li>
			    <li><a href="#" onclick="goWatchlist();return false;">Watchlist</a></li>
			    <li><a href="#" onclick="goCurPrice();return false;">Order Book</a></li>
			    <li><a href="#" onclick="goRunning();return false;">Running Trade</a></li>
			    <li><a href="#" onclick="goBrokerActivity();return false;">Broker Activity</a></li>
			    <li><a href="#" onclick="goBrokerTrans();return false;">Broker Transaction by Stock</a></li>
			    <li><a href="#" onclick="goForeignTrans();return false;">Foreign Transaction Summary</a></li>
			    <li><a href="#" onclick="goGainerLosser();return false;">Top Gainers Lossers</a></li>            
			    <li><a href="#" onclick="goMarketSummary();return false;">Market Summary</a></li>           
                <li><a href="#" onclick="goWithdraw();return false;">Withdrawal Form</a></li>
			    <li><a href="#" onclick="goChgPass();return false;">Change Password</a></li>
                <li><a href="#" onclick="goChgPin();return false;">Change Pin</a></li>			
			<!-- <li><a href="#" onclick="goBuy();return false;">Buy Order</a></li> -->
			<!-- <li><a href="#" onclick="goSell();return false;">Sell Order</a></li> -->
			<!-- <li><a href="#" onclick="goFavorite();return false;">My Favorite</a></li> -->
			<!-- <li><a href="#" onclick="openExtMenu(); return false;">.....</a></li> -->
			    <li><a href="#" onclick="goDisclaimer();return false;">Disclaimer</a></li>
			    <li><a href="#" onclick="goBrowserInfo();return false;">Browser Info</a></li>
		    <?php } ?>
        </ul>
		</p>
		</div>
		<div data-role="popup" id="popupmenu">
			<p>
			<ul data-role="listview" data-inset="true" data-filter="false">
				<li><a href="#" onclick="goBrokerActivity();return false;">Broker Activity</a></li>
				<li><a href="#" onclick="goBrokerTrans();return false;">Broker Transaction by Stock</a></li>
				<li><a href="#" onclick="goForeignTrans();return false;">Foreign Transaction Summary</a></li>
				<li><a href="#" onclick="goGainerLosser();return false;">Top Gainers Lossers</a></li>
				<li><a href="#" onclick="goMarketSummary();return false;">Market Summary</a></li>
			</ul>
			</p>
		</div>
	</div>
	</body>
</html>
