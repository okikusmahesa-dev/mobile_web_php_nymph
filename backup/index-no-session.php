<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

?>
<html>
<meta name="viewPort" content="width=device-width, initial-scale=1.0" />
<head> 
	<title>BEST Mobile</title> 
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
	</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
		<div data-role="content">
		<div class="ui-grid-solo">
		<center>
		<img src="img/logo-front.png"/>
		</center>
		</div>
		<p>
		<ul data-role="listview" data-inset="true" data-filter="false">
			<li><a href="#" onclick="goCurPrice();return false;">Current Price</a></li>
			<li><a href="#" onclick="goBuy();return false;">Buy Order</a></li>
			<li><a href="#" onclick="goSell();return false;">Sell Order</a></li>
			<li><a href="#" onclick="goRunning();return false;">Running Trade</a></li>
			<li><a href="#" onclick="goFavorite();return false;">My Favorite</a></li>
			<li><a href="#" onclick="goPortfolio(); return false;">Portfolio</a></li>
			<li><a href="#" onclick="goOrderList(); return false;">OrderList</a></li>
			<li><a href="#" onclick="goTradeList(); return false;">TradeList</a></li>
		</ul>
		</p>
		</div>			
	<? include "page-footer.php" ?>
	</div>
</body>
</html>
