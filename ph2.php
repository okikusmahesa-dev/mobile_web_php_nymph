<div data-role="header" data-theme="b">

	<a href="#indexList" data-rel="popup" data-role="button" data-inline="true"  data-transition="fade" data-icon="home" data-theme="b">Index</a>
		<div data-role="popup" id="indexList" data-theme="b">
        	<ul data-role="listview" data-inset="true" style="min-width:210px;" data-theme="d">
				<li><a href="#" onclick="goRunning();return false;">Running Trade</a></li>
				<li><a href="#" onclick="goCurPrice();return false;">Order Book</a></li>
				<!-- <li><a href="#" onclick="goBuy();return false;">Buy Order</a></li> -->
				<!-- <li><a href="#" onclick="goSell();return false;">Sell Order</a></li> -->
				<li><a href="#" onclick="goBuyExt();return false;">Buy Order</a></li>
				<li><a href="#" onclick="goSellExt();return false;">Sell Order</a></li>
				<li><a href="#" onclick="goPortfolio(); return false;">Portfolio</a></li>
				<li><a href="#" onclick="goOrderList(); return false;">OrderList</a></li>
				<li><a href="#" onclick="goTradeList(); return false;">TradeList</a></li>
				<!-- <li><a href="#" onclick="goFavorite();return false;">My Favorite</a></li> -->
				<!-- <li><a href="#" onclick="openExtMenu(); return false;">.....</a></li> -->
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
