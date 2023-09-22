<link rel="shortcut icon" href="img/bca.ico" />
<link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script type="text/javascript" src="js/flexigrid.pack.js"></script>
<script type="text/javascript" src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
<script src="js/common.js"></script>
<script src="js/sprintf.js"></script>
<script src="js/date.format.js"></script>
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
		window.location = "index.php";
	}
	function goCurPrice() {
		window.location = "curPrice.php";
	}
	//function goBuy(code, price, qty) {
		//window.location = "buyOrder.php?" + sprintf("code=%s&price=%d&qty=%d", code, price, qty);
	//}
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
</script>
