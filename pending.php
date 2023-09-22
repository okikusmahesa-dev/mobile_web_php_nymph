<?
//
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//
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
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
$in_code = $_GET["code"];
$in_price = $_GET["price"];
$in_qty = $_GET["qty"];
//
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
	.sell-panel {background-color:#d6ffc6;}
	.r-cell {text-align:left;width:25%;}
	.r-qty {text-align:right;width:25%;}
	//-->
	</style>
	<script type="text/javascript" src="js/curPriceExt.js"></script>
	<script type="text/javascript">
	var userId = "<?=$userId;?>";
	var now = new Date();
	today = now.format("yyyymmdd");

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
			if ( document.getElementById('codeTxt').value.length < 4 || document.getElementById('priceTxt').value.length == 0 || document.getElementById('lotTxt').value.length == 0 ) {
				alert("Error Input. Check Again!");
				return;
			}
			
			ret = chkFraction();
			if (ret != 1) {
				alert("Cannot Order. Price is not valid!");
				return;
			} 
			if ( confirm("Are you sure?") ) {
				$.mobile.loading('show');
				tr189000(userId, $("#codeTxt").val(), "RG", $("#priceTxt").val(), $("#lotTxt").val());
			}
		});
	}

	function chkFraction() {
		price = document.getElementById('priceTxt').value;
		/*
		for (i=0; i<bidFract.length; i++) {
			if (price == bidFract[i]) {
				return 1;
			}
		}
		for (i=0; i<offFract.length; i++) {
			if (price == offFract[i]) {
				return 1;
			}
		}
		*/

		// uLimit - lLimit
		interval = Number(offFract[0]) - Number(bidFract[0]);
		fract = [];
		for (i=0; i<Number(uLimit); i+interval) {
			prc = Number(lLimit) + i;
			fract.push(prc.toString());
		}
		console.log(fract);
		//

		return 0;
	}

	function vol2lot(qty) {
		return qty / 100;
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
					clearInput();
				}
				//alert( "jsRet=" + JSON.stringify(jsRet) );
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				alert('error communition with server.');
			}
		});
		$.mobile.loading('hide');
	}

	function clearInput() {
		document.getElementById('codeTxt').value = "";
		document.getElementById('priceTxt').value = "";
		document.getElementById('lotTxt').value = "";
	}

	$(document).ready(function() {
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);
			if(page.attr('data-cache') == 'never'){
				page.remove();
			};
		});
		//
		//
		$("#codeTxt").val('<?=$in_code;?>');
		$("#priceTxt").val('<?=$in_price;?>');
		$("#lotTxt").val('<?=$in_qty;?>');
		setInput();

		if ($("#priceTxt").val.length == 0) {
			$("#priceTxt").val("1");
		}
	});

	// Calc
	function tr100006(id) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=100006&userId=" + id,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					console.log(jsRet.out);
				}
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
			}
		});
	}
	//
	</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div class="buy-panel">
	<!-- <b>Buy Order</b> -->
	<table width=320px border=0>
		<tr><td colspan="2"><h2 style="margin-bottom: 0px;">Buy Order</h2></td></tr>
		<tr>
			<td width="25%">Code</td><td width="25%"><input id="codeTxt" type="text" class="no-ime" data-mini="true" /></td>
		</tr>
		<tr>
			<td width="25%">Price</td><td width="25%"><input id="priceTxt" type="text" class="no-ime" data-mini="true" /></td>
		</tr>
		<tr>
			<td width="25%" id="lotMaxQty">Lot</td><td width="25%"><input id="lotTxt" type="text" class="no-ime" data-mini="true" /></td>
		</tr>
		<tr>
			<td colspan=2>
				<a id="okBtn" href="#" data-role="button" data-icon="check" data-mini="true">Order</a>
			</td>
		</tr>
	</table>
	<table border=0 id="curPrice" width=320px>
		<thead>
			<th colspan="4" id="price" class="lprice"></th>
		</thead>
		<tbody>
		<tr>
			<td class="r-cell">Prev</td><td id="prev" class="r-qty"></td>
			<td class="r-cell">Chg(%)</td><td id="chg" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Open</td><td id="oprice" class="r-qty"></td>
			<td class="r-cell">Value(M)</td><td id="value" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">High</td><td id="hprice" class="r-qty"></td>
			<td class="r-cell">Volume</td><td id="volume" class="r-qty"></td>
		</tr>
		<tr>
			<td class="r-cell">Low</td><td id="lprice" class="r-qty"></td>
			<td class="r-cell">Freq</td><td id="freq" class="r-qty"></td>
		</tr>
		</tbody>
	</table>
	<table width=320px id="r-quote" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
