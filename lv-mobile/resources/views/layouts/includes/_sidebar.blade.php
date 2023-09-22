<script type="text/javascript">
    $(document).ready(function() {
        $("#sidebar").mCustomScrollbar({
			theme: "minimal"
		});
		$('#dismiss, .overlay').on('click', function () {
			$('#sidebar').removeClass('active');
			$('.overlay').removeClass('active');
		});
		$('#sidebarCollapse').on('click', function () {
			$('#sidebar').addClass('active');
			$('.overlay').addClass('active');
			$('.collapse.in').toggleClass('in');
			$('a[aria-expanded=true]').attr('aria-expanded', 'false');
		});
    });
</script>
<nav id="sidebar">
	<div id="dismiss">
		<i class="fas fa-arrow-left" style="margin-top: 10px;"></i>
	</div>
	<div class="sidebar-header">
		JKU3062(3062)<br>
		NI LUH PUTU GEDE MAHARUPA ASMARINA
	</div>
	<ul class="list-unstyled components">
		<ul class="list-group list-group-flush">
			<li class="list-group-item" onclick="goIndex();return false;"><a href="#">Home</a></li>
			<li class="list-group-item" onclick="goOrderList();return false;"><a href="#">Order List</a></li>
			<li class="list-group-item" onclick="goTradeList();return false;"><a href="#">Trade List</a></li>
			<li class="list-group-item" onclick="goWatchlist();return false;"><a href="#">Watchlist</a></li>
			<li class="list-group-item" onclick="goCurPrice();return false;"><a href="#">Orderbook</a></li>
			<li class="list-group-item" onclick="goRunning();return false;"><a href="#">Running Trade</a></li>
			<li class="list-group-item" onclick="goBrokerActivity();return false;"><a href="#">Broker Activity</a></li>
			<li class="list-group-item" onclick="goBrokerTrans();return false;"><a href="#">Broker Transaction by Stock</a></li>
			<li class="list-group-item" onclick="goForeignTrans();return false;"><a href="#">Foreign Transaction Summary</a></li>
			<li class="list-group-item" onclick="goGainerLosser();return false;"><a href="#">Top Gainers Lossers</a></li>
			<li class="list-group-item" onclick="goMarketSummary();return false;"><a href="#">Market Summary</a></li>
		</ul>
	</ul>
	<ul class="list-unstyled CTAs">
		<a href="#" onclick="goLogout()" class="btn btn-info">Logout</a>
	</ul>
</nav>
