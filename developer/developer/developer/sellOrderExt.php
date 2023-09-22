<?
//
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
	//echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Your trading session has Expired. \\nPlease re-Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
}
//
?>
<html>
<head> 
	<title>Sell Order</title> 
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
	body {background-color:#d6ffc6;}
	//-->
	</style>
	<script type="text/javascript" src="js/portorder.js"></script>
	<script type="text/javascript" src="js/curPriceExt.js"></script>
	<script type="text/javascript">
	var userId = '<?=$userId;?>';
	var now = new Date();
	today = now.format("yyyymmdd");
	
	function setInput() {
		$("#codeTxt").keyup(function(e){
			this.value = this.value.toUpperCase();
			if (event.keyCode == 13) {
				code = document.getElementById('codeTxt').value;
				price = document.getElementById('priceTxt').value
				$.mobile.loading('show');
				qryTr100000();
				$.mobile.loading('hide');
			}
		});
		$("#priceTxt").keyup(function(e){
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
			if (parseInt(this.value,10) > 50000) {
				this.value = 50000;
				return;
			}
		});
		$("#okBtn").click(function(){
			var bal = checkBalance($("#codeTxt").val());

			if ( document.getElementById('codeTxt').value.length < 4 || document.getElementById('priceTxt').value.length == 0 || document.getElementById('lotTxt').value.length == 0 ) {
				alert("Error Input. Check Again!");
				return;
			}

			console.log("uLimit: " + uLimit + " lLimit: " + lLimit);

			ret = chkFraction();
			//ret = 1;
//
			if (Number($("#priceTxt").val()) > uLimit) {
				alert("Cannot Order. Price cannot be more than " + uLimit + "!");
				return;
			}

			if (Number($("#priceTxt").val()) < lLimit) {
				alert("Cannot Order. Price cannot be less than " + lLimit + "!");
				return;
			}
//
			if (ret != 1) {
				alert("Cannot Order. Price is not valid!");
				return;
			}

			if (bal == 0) {
				alert("Cannot Order, Balance = 0");
				return;
			}

			if (bal < Number($('#lotTxt').val())) {
				alert("Cannot Order, Balance = " + bal);
				return;
			}
			
			if ( confirm("Are you sure?") ) {
				$.mobile.loading('show');
				tr189000(userId, $("#codeTxt").val(), "RG", $("#priceTxt").val(), $("#lotTxt").val());
				$.mobile.loading('hide');
			}
		});

		$("#stockBtn").click(function(){
			$.mobile.loading('show');
			qryTr100000();

			// Visible Balance
			code = document.getElementById('codeTxt').value;

			for (i=0;i<arr800001["code"].length; i++) {
				if (code == arr800001["code"][i]) {
					$("#balQty").val(arr800001["lot"][i]);
				}
			}
			//
			$.mobile.loading('hide');
		});
	}

	function chkFraction() {
		price = document.getElementById('priceTxt').value;

		interval = 0;

		if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
			interval = 1;
		} else {
			if (Gprev < 500) {
				interval = 1;
			}
			if ((Gprev >= 500) && (Gprev < 5000)) {
				interval = 5;
			}
			if (Gprev >= 5000) {
				interval = 25;
			}
		}

		// uLimit - lLimit
		fract = [];
		for (i=Number(lLimit); i<=Number(uLimit); i++) {
			fract.push(i.toString());
			if (i < 500) {
				i = (i + 1) - 1;
			} else if ((i >= 500) && (i < 5000)) {
				i = (i + 5) - 1;
			} else if (i >= 5000) {
				i = (i + 25) - 1;
			} else {
				i = (i + Number(interval)) - 1;
			}
		}

		for (i=0; i<fract.length; i++) {
			if (price == fract[i]) {
				return 1;
			}
		}

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
			//data: "tr=189100&userId=" + id + "&dealerId=" + id + "&pin=&code=" + code + "&price=" + price + "&qty=" + lot + "&lot=100" + "&mkt=" + board + "&type=0",
			data: "tr=189100&userId=" + id + "&dealerId=" + id + "&pin=&code=" + code + "&mkt=" + board + "&price=" + price + "&qty=" + lot + "&lot=100" + "&type=0",
			success: function(jsRet){
				console.log(jsRet);
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
	}

	function clearInput() {
		document.getElementById('codeTxt').value = "";
		document.getElementById('priceTxt').value = "";
		document.getElementById('lotTxt').value = "";
		document.getElementById('balQty').value = "";
	}

	$(document).ready(function() {
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);

			if(page.attr('data-cache') == 'never'){
			page.remove();
			};
		});
		setInput();
		
		qryTr800001();
	});

	//
	function qryTr800001() {
		var userId = "<?=$userId;?>";
		var now = new Date();
		nowStr = now.format("yyyymmdd");
		tr800001(userId, nowStr);
	}
	//

	var arr800001 = [];
	arr800001["code"] = [];
	arr800001["lot"] = [];

	//tr800001
	function tr800001(id, today) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=800001&clientID=" + id + "&date=" + today,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					rows = [];
					cnt = jsRet.out.length;
					for (i = 0; i < cnt; i ++) {
						id_v = i;
						//
						jsRet.out[i].lot = vol2lot(jsRet.out[i].balance);
						//
						code = jsRet.out[i].stkID;
						lot = jsRet.out[i].lot;
						shares = jsRet.out[i].balance;
						//
						arr800001["code"][i] = code;
						arr800001["lot"][i] = lot;
					}
				}
			}
		});
	}
	//

	//
	function checkBalance(code) {
		qryTr800001();
		
		//
		for (i = 0;i < arr800001["code"].length;i++) {
			if (arr800001["code"][i] == code) {
				lot = Number(arr800001["lot"][i]);
				return lot;
			}
		}
		//

		return 0;
	}
	//
	</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div class="sell-panel">
	<!-- <b>	Sell Order	</b> -->
	<table width=320px border=0>
		<tr><td colspan="3"><h2 style="margin-bottom: 0px;">Sell Order</h2></td></tr>
		<tr>
			<td width="25%">Code</td>
			<td width="25%"><input id="codeTxt" type="text" class="no-ime" data-mini="true" /></td>
			<td width="5%"><a id="stockBtn" href="#" data-role="button" data-icon="check" data-mini="true" data-inline="true">&nbsp</a></td>
		</tr>
		<tr>
			<td width="25%">Price</td><td width="25%" colspan="2"><input id="priceTxt" type="text" class="no-ime" data-mini="true" /></td>
		</tr>
		<tr>
			<td width="25%" id="lotMaxQty">Lot</td>
			<td width="25%"><input id="lotTxt" type="text" class="no-ime" data-mini="true" /></td>
			<td width="5%"><input id="balQty" type="text" disabled="disabled" data-mini="true" /></td>
		</tr>
		<tr>
			<td colspan=3>
				<a id="okBtn" href="#" data-role="button" data-icon="check" data-mini="true">Order</a>
			</td>
		</tr>
	</table>
	<table border=0 id="curPrice" width=320px>
		<thead>
			<th colspan="4" id="price" class="lprice" style="font-size:20px"></th>
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
