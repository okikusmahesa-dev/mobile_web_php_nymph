<? include "inc-idle.php"; ?>
<link rel="shortcut icon" href="img/bca.ico" />
<link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
<link rel="stylesheet" href="css/jquery.mobile-1.2.0.min.css" />
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/flexigrid.pack.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>
<script src="js/common.js?20180727a"></script>
<script src="js/sprintf.js"></script>
<script src="js/date.format.js"></script>
<script type="text/javascript" src="timerPing.js?20190213A"></script>
<script type="text/javascript" src="js/jquery.idle.min.js"></script>

<script>
	var loginId = "<?=$loginId;?>";     // for timerPing

    function goWatchlist(){
        window.location = "watchlist.php";
    }
    function goChgPin() {
	    window.location = "chgPin.php";
	}
	function goChgPass() {
		window.location = "chgPassword.php";
	}
	function goLogin() {
		window.location = "login.php";
	}
	function goLoginPin() {
		window.location = "inPin.php";
	}
	function goLogout() {
		window.location = "tlogout.php";
	}
	function goIndex() {
		window.location = "index.php?uFlag=1";
	}
	function goCurPrice() {
		window.location = "curPrice.php";
	}
	//function goBuy(code, price, qty) {
		//window.location = "buyOrder.php?" + sprintf("code=%s&price=%d&qty=%d", code, price, qty);
	//}
	// new added
	function goBuyExt() {
		window.location = "buyOrderExt.php";
	}
	function goBuyAcExt() {
	    window.location = "buyOrderAcExt.php";
	}
	function goSellExt() {
		window.location = "sellOrderExt.php";
	}
	function goSellAcExt() {
	    window.location = "sellOrderAcExt.php";
	}
	function goBuy() {
		window.location = "buyOrder.php";
	}
	function goSell() {
		window.location = "sellOrder.php";
	}
	function goRunning() {
		window.location = "running.php";
	}
	function goFavorite() {
		window.location = "favorite.php";
	}
	function goPortfolio() {
		window.location = "portfolio.php";
	}
	function goOrderList() {
		window.location = "orderList.php";
	}
	function goTradeList() {
		window.location = "tradeList.php";
	}
	function goBrokerTrans() {
		window.location = "brokerByStock.php";
	}
	function goForeignTrans() {
		window.location = "foreignTransSummary.php";
	}
	function goMarketSummary() {
		window.location = "marketSummary.php";
	}
	function goBrokerActivity() {
		window.location = "brokerActivity.php";
	}
	function goGainerLosser() {
		window.location = "gainerLosser.php";
	}
	function goWithdraw() {
		window.location = "form-withdraw.php";
	}
	function goDisclaimer(){
		window.location = "disclaimer.php";
	}
	function goBrowserInfo(){
		window.location = "browser.php";
	}
	/*function goWatchlist() {
		window.location = "watchlist.php";
	}*/
</script>
