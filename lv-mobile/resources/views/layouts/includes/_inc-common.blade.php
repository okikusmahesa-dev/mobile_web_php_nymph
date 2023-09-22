<link rel="shortcut icon" href="{{asset('assets/img/bca.ico')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/fontawesome/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/sidebar.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/jquery.mCustomScrollbar.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/DataTables/datatables.min.css')}}"/>
<script src="{{asset('assets/vendor/bootstrap/jquery/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/jquery/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('assets/js/common.js')}}"></script>
<script src="{{asset('assets/js/sprintf.js')}}"></script>
<script src="{{asset('assets/js/date.format.js')}}"></script>
<script src="{{asset('assets/js/timerPing.js')}}"></script>
<script src="{{asset('assets/js/jquery.idle.min.js')}}"></script>
<script src="{{asset('assets/vendor/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor/DataTables/dataTables.fixedColumns.min.js')}}"></script>

<script>
	var loginId = "{{ $loginId ?? '' }}";

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
		window.location = "/login";
	}
	function goLoginPin() {
		window.location = "/inPin";
	}
	function goLogout() {
		window.location = "/logout";
	}
	function goIndex() {
		window.location = "/";
	}
	function goCurPrice() {
		window.location = "/curPrice";
	}
	function goBuyExt() {
		window.location = "buyOrderExt.php";
	}
	function goSellExt() {
		window.location = "sellOrderExt.php";
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
