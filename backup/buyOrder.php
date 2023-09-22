<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

session_start();
//print_r($_SESSION);
if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
$userId = $_SESSION["userId"];
$pinState = 0;
$pinLogin = 0;
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first!');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
}

$in_code = $_GET["code"];
$in_price = $_GET["price"];
$in_qty = $_GET["qty"];
?>
<html>
<head> 
	<title>Buy Order</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<? include "inc-common.php" ?>
	<style type="text/css">
	<!--
	<? include "css/icon.css" ?>
	.data-row {height:23px;}
	.no-ime {ime-mode:disabled; text-transform:uppercase;}
	.bid-qty {background-color:#feeeee;}
	.bid-price {background-color:#feeeee;}
	.off-qty {background-color:#e4f2fa;}
	.off-price {background-color:#e4f2fa;}
	.buy-panel {background-color:#d6ffc6;}
	.sell-panel {background-color:#ffeaea;}
	//-->
	</style>
	<script type="text/javascript">
	var userId = "<?=userId?>";

	function setInput() {
		$("#codeTxt").keyup(function(e){
			this.value = this.value.toUpperCase();
		});
		$("#priceTxt").keyup(function(e){
			if (this.value.length == 0) {
				return;
			}
			if (!isNaN(parseInt(this.value,10))) {
				this.value = parseInt(this.value);
			} else {
				this.value = 1;
			}
			this.value = this.value.replace(/[^0-9]/g, '');
			if (parseInt(this.value,10) <= 0) {
				this.value = 1;
				return;
			}
			if (parseInt(this.value,10) > 100000) {
				this.value = 100000;
				return;
			}
		});
		$("#lotTxt").keyup(function(e){
			if (this.value.length == 0) {
				return;
			}
			if (!isNaN(parseInt(this.value,10))) {
				this.value = parseInt(this.value);
			} else {
				this.value = 0;
			}
			this.value = this.value.replace(/[^0-9]/g, '');
			if (parseInt(this.value,10) <= 0) {
				this.value = 1;
				return;
			}
			if (parseInt(this.value,10) > 10000) {
				this.value = 10000;
				return;
			}
		});
		$("#okBtn").click(function(){
			tr189000(userId, $("#codeTxt").val(), "RG", $("#priceTxt").val(), $("#lotTxt").val());
		});
	}

	function vol2lot(qty) {
		return qty / 500;
	}

	function tr189000(id, code, board, price, lot) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=189000&userId=" + id + "&dealerId=" + id + "&pin=&code=" + code + "&mkt=" + board + "&price=" + price + "&qty=" + lot + "&lot=100" + "&type=0",
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					alert("Order accepted.");
				}
				//alert( "jsRet=" + JSON.stringify(jsRet) );
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				alert('error communition with server.');
			}
		});
	}

	$(document).ready(function() {
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);
			if(page.attr('data-cache') == 'never'){
				page.remove();
			};
		});
		//
		$("#codeTxt").val('<?=$in_code;?>');
		$("#priceTxt").val('<?=$in_price;?>');
		$("#lotTxt").val('<?=$in_qty;?>');
		setInput();
	});
	</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div class="buy-panel">
	<!-- <h2>Buy Order</h2> -->
	<table width=320px border=0>
		<tr><td colspan="2"><h2 style="margin-bottom: 0px;">Buy Order</h2></td></tr>
		<tr>
			<td width="25%">Code</td><td width="25%"><input id="codeTxt" type="text" class="no-ime" /></td>
		</tr>
		<tr>
			<td width="25%">Price</td><td width="25%"><input id="priceTxt" type="number" class="no-ime" /></td>
		</tr>
		<tr>
			<td width="25%">Lot</td><td width="25%"><input id="lotTxt" type="number" class="no-ime" /></td>
		</tr>
		<tr>
			<td colspan=2>
				<a id="okBtn" href="#" data-role="button" data-icon="check">Order</a>
			</td>
		</tr>
	</table><br/>
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
