<script type="text/javascript">
    function close_window(){
        if(confirm("Logout and Close Window?")){
		window.location = "tlogout.php"
	}
}
</script>
<?php 
//	include 'json/cekping.php';
?>
<div data-role="header" data-theme="b">
	<!-- <a href="#" onclick="return goIndex();" class="ui-btn-left ui-corner-all ui-shadow" data-role="button" data-icon="home">Home</a> -->
	<a href="#indexList" data-rel="popup" data-role="button" data-inline="true"  data-transition="fade" data-icon="arrow-d" data-iconpos="right" data-theme="b">Index</a>
		<div data-role="popup" id="indexList" data-theme="b">
        	<ul data-role="listview" data-inset="true" style="min-width:210px;" data-theme="d">
				<li><a href="#" onclick="goIndex();return false;">Home</a></li>
				<li><a href="#" onclick="goChgPass();return false;">Change Password</a></li>
				<li><a href="#" onclick="goChgPin();return false;">Change Pin</a></li>
				<li><a href="#" onclick="goWithdraw();return false;">Withdrawal Form</a></li>
				<li><a href="#" onclick="goRunning();return false;">Running Trade</a></li>
				<li><a href="#" onclick="goCurPrice();return false;">Order Book</a></li>
				<li><a href="#" onclick="goOrderList(); return false;">OrderList</a></li>
				<li><a href="#" onclick="goTradeList(); return false;">TradeList</a></li>
				<li><a href="#" onclick="goBrokerActivity();return false;">Broker Activity</a></li>
				<li><a href="#" onclick="goBrokerTrans();return false;">Broker Transaction by Stock</a></li>
				<li><a href="#" onclick="goForeignTrans();return false;">Foreign Transaction Summary</a></li>
				<li><a href="#" onclick="goGainerLosser();return false;">Top Gainers Lossers</a></li>
				<li><a href="#" onclick="goMarketSummary();return false;">Market Summary</a></li>
			</ul>
		</div>
	<h1>BEST Mobile</h1>
	<a href="#" onclick="return goLogout();" class="ui-btn-right ui-corner-all ui-shadow"data-role="button" data-icon="forward" title="Logout">Logout</a>
</div>
