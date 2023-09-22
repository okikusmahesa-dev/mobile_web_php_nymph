<?
//
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//
session_start();
//print_r($_SESSION);
/*if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
$userId = $_SESSION["userId"];
$pinState = 0;
$pinLogin = 0;
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
if ($_SESSION["pinState"] != 1) {
	//echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Your trading session has Expired. \\nPlease re-Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
}*/
/*
$in_code = $_GET["code"];
$in_price = $_GET["price"];
$in_qty = $_GET["qty"];
*/
?>
<html>
<head> 
	<title>Change Password</title> 
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

	var price = 0;
    var lot = 0;
    var qty = 0;
	var tot = 0;
	
	//auto calculate total 20150930
	
	
	$(document).ready(function() {
		var userId = "<?=$userId;?>";
		var pinId = "<?=$pinLogin;?>";
		$.mobile.loading('show');
		tr800300(userId,pinId);
		$.mobile.loading('hide');
	});
	
	function tr800300(id, pin) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=800300&userId=" + id + "&pin=" + pin,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					//
					ClientNo = jsRet.out[0].ClientNo;
					Name = jsRet.out[0].Name;
					//$('#clientno').text(ClientNo);
					$('#loginTxt').val(ClientNo);
					//
				}
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				//alert('error communition with server.');
			}
		});
	}	
	//close 20150930

		
	$("#chgBtn").click(function(){
		window.location.replace("index.php?uFlag=1");
	});

	function tr800901(login_id, password) {
		$.ajax({
		type: "POST",
		url: "json/trproc.php",
		dataType : "json",
		data: "tr=800901&login_id=" + login_id + "&password=" + password,
		success: function(jsRet){
			if (jsRet.status != 1) {
				alert(jsRet.mesg);
			} else {
			//
				alert("Change Password success.");
				window.location.replace("index.php?uFlag=1");
			//
			}
		},
		error: function(data, status, err) {
			console.log("error forward : "+data);
			//alert('error communition with server.');
		}			
		});		
	}


</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div>
	<!-- <b>Buy Order</b> -->
	<table style="overflow-x:auto;">
		<tr>
			<td colspan="2"><h2 style="margin-bottom: 0px;">Change Password</h2></td>
			<!--<td><input id="commissionTxt" type="hidden" class="no-ime" data-mini="true"></td>-->
		</tr>
		<tr>
			<td width="10%">Login ID</td>
			<td width="25%"><input id="loginTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
		</tr>
		<tr>
			<td width="10%">New Password</td>
			<td width="25%"><input id="nPassTxt" type="password" data-mini="true" /></td>
		</tr>
		<!-- 20150930 -->
		
		<tr>
			<td width="10%">Confirm Password</td>
			<td width="25%"><input id="cPassTxt" type="password" data-mini="true"></td>
		</tr>
		
		<!-- close -->
		<tr>
			<td colspan="2" align="center">
				<a id="chgBtn" href="#" style="width:50%" data-role="button" data-icon="check" data-mini="true">Change</a>
			</td>
		</tr>
	</table>
	<!--<table border=0 id="curPrice" width=320px>
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
	</table>-->
	<!--<table width=320px id="r-quote" class="flexigrid">
		<thead>
		</thead>
		<tbody>
		</tbody>
	</table>-->
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
