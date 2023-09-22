<div data-role="footer" data-position="fixed" data-theme="b">
	<!-- <a href="#" onclick="return goIndex();" data-role="button" data-icon="home">Home</a> -->
<?
	if(isset($_SESSION['disclaim']) && $_SESSION['disclaim'] == "disclaim"){
?>
	<a href="#" onclick="window.location='disclaim.php?disclaim=N'" data-role="button" data-icon="forward" title="Disclaimer">Disclaimer</a> 
<?
	}else{
?>
	<a href="#" onclick="return goBuyExt()" data-role="button" data-icon="plus">Buy</a>
	<a href="#" onclick="return goSellExt()" data-role="button" data-icon="minus">Sell</a>
	<!-- <a href="#" onclick="return goRunning()" data-role="button" data-icon="nmmts-running" title="Running">Live</a> -->
	<!-- <a href="#" onclick="return goFavorite()" data-role="button" data-icon="nmmts-favorite" title="Favorite">Favorite</a> -->
	<a href="#" onclick="return goOrderList()" data-role="button" data-icon="nmmts-portfolio" title="OrderList">OrderList</a>
	<a href="#" onclick="return goPortfolio()" data-role="button" data-icon="nmmts-portfolio" title="Portfolio">Portfolio</a>
	<!-- <a href="#" onclick="return goTradeList()" data-role="button" data-icon="nmmts-portfolio" title="TradeList">TradeList</a> -->
	<!-- <a href="#" onclick="return goLogout()" data-role="button" data-icon="forward" title="Logout">&nbsp;</a> -->
<?
	}
?>
</div>
