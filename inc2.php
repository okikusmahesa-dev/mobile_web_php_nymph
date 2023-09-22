<link rel="shortcut icon" href="img/bca.ico" />
<link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
<link rel="stylesheet" href="css/jquery.mobile-1.2.0.min.css" />
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/flexigrid.pack.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>
<script src="js/common.js"></script>
<script src="js/sprintf.js"></script>
<script src="js/date.format.js"></script>
<script type="text/javascript" src="timerPing.js"></script>
<script>
	function goLogin() {
		window.location = "login.php";
	}
	function goLoginPin() {
		window.location = "inPin.php";
	}
	function goLogout() {
		window.location = "logout.php";
	}
	function goIndex() {
		//window.location = "index.php";
		alert("Test!");
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
	function goSellExt() {
		window.location = "sellOrderExt.php";
	}
	//
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
</script>
