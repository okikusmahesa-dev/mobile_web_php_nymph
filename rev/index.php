<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	echo "Please login...";
	$server = $_SERVER["SERVER_NAME"];
	//header("Location: http://$server/rev/login.php");	
	//exit(0);
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
	<title>BEST Mobile[<?=$loginId;?><?=$ipChk;?>]</title> 
		<? include "inc-common.php" ?>
		<script type="text/javascript">
			$(document).ready(function() {
				jQuery('body').on('pagehide','div',function(event,ui){
				//jQuery('div').live('pagehide', function(event, ui){
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
	 		function onLoad(){
	 			document.addEventListener("deviceready",onDeviceReady,false);
	 		}
	 		function onDeviceReady(){
				document.addEventListener("backbutton",noth,false);
			}
			function noth(){
		 		//code for what happends if back button is pressed
				alert('coba');
			}
		</script>
</head> 
<body>
	<?php include "page-header-index.php"?>
	<div class="container">
		<br>
		<center>
			<img src="img/logo-front.png"/>
		</center>
		<br>
		<div class="list-group">
		<?php if(isset($_SESSION['disclaim']) && $_SESSION['disclaim'] == "disclaim"){ ?>
			<a href="#" class="list-group-item list-group-item-action" onclick="goWatchlist();return false;">Watchlist <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goCurPrice();return false;">Order Book <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goRunning();return false;">Running Trade <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goBrokerActivity();return false;">Broker Activity <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goBrokerTrans();return false;">Broker Transaction by Stock <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goForeignTrans();return false;">Foreign Transaction Summary <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goGainerLosser();return false;">Top Gainers Lossers <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>            
			<a href="#" class="list-group-item list-group-item-action" onclick="goMarketSummary();return false;">Market Summary <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
		<?php }else{
			if (!isset($_SESSION["pinState"]) || $_SESSION["pinState"] != 1) {
			    $_SESSION['disclaim'] = "notdisclaim";
            	echo '<a href="#" class="list-group-item list-group-item-action" onclick="goLoginPin();return false;">Input Pin <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>';
			} ?>
			<a href="#" class="list-group-item list-group-item-action" onclick="goBuyExt();return false;">Buy Order <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
        	<a href="#" class="list-group-item list-group-item-action" onclick="goSellExt();return false;">Sell Order <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
        	<a href="#" class="list-group-item list-group-item-action" onclick="goPortfolio(); return false;">Portfolio <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a> 
			<a href="#" class="list-group-item list-group-item-action" onclick="goOrderList(); return false;">OrderList <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a> 
			<a href="#" class="list-group-item list-group-item-action" onclick="goTradeList(); return false;">TradeList <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goWatchlist();return false;">Watchlist <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goCurPrice();return false;">Order Book <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goRunning();return false;">Running Trade <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goBrokerActivity();return false;">Broker Activity <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goBrokerTrans();return false;">Broker Transaction by Stock <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goForeignTrans();return false;">Foreign Transaction Summary <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goGainerLosser();return false;">Top Gainers Lossers <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>            
			<a href="#" class="list-group-item list-group-item-action" onclick="goMarketSummary();return false;">Market Summary <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>           
			<a href="#" class="list-group-item list-group-item-action" onclick="goWithdraw();return false;">Withdrawal Form <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goChgPass();return false;">Change Password <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goChgPin();return false;">Change Pin <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>			
			<a href="#" class="list-group-item list-group-item-action" onclick="goDisclaimer();return false;">Disclaimer <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
			<a href="#" class="list-group-item list-group-item-action" onclick="goBrowserInfo();return false;">Browser Info <i class="fas fa-angle-right" style="float: right;margin-top: 5px;"></i></a>
		<?php } ?>
		</div>
	</div>
	<br>
</body>
</html>
