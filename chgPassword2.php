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
	echo '<script type="text/javascript">setTimeout(function() { window.location.href = "https://mobile.bcasekuritas.co.id/tlogout.php"; }, 15 * 60000);</script>';
if ($_SESSION["pinState"] != 1) {
	//echo "<script>alert('Please Input PIN first!');</script>";
	echo "<script>alert('Your trading session has Expired. \\nPlease re-Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
    //$_SESSION["url"]="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

}
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
	
	$(document).ready(function() {
		var userId = "<?=$userId;?>";
		var pinId = "<?=$pinLogin;?>";
		$.mobile.loading('show');
		tr800300(userId,pinId);
		$.mobile.loading('hide');
	});
	
	//start chgPassword
	function tr830000(userId, password) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=830000&userId=" + userId + "&password=" + password,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert('There is an error with our server');
				} else {
					//alert(jsRet.status+', userId = '+userId+', pass = '+password);
					alert('Your Password has been changed.');
					window.location.replace("index.php");
				}
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				//alert('error communition with server.');
			}
		});
	}
	//end chgPassword


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
				this.value = 1;
			}
			this.value = this.value.replace(/[^0-9]/g, '');
			if (parseInt(this.value,10) <= 0) {
				this.value = 1;
				return;
			}
			if (parseInt(this.value,10) > 1000000) {
				this.value =999999;
				return;
			}

			price = document.getElementById('priceTxt').value;
			
			maxQty = sprintf("%.0f", Number(GmaxAmt) / (Number(price) * (1.0 + Number(Gcommission / 100))) / 100 - 1.5);
			
			//if(sprintf("%.0f", Number(GmaxAmt) / (Number(price) * (1.0 + Number(Gcommission / 100))) / 100 - 1.5) > 0){
			//	 maxQty = sprintf("%.0f", Number(GmaxAmt) / (Number(price) * (1.0 + Number(Gcommission / 100))) / 100 - 1.5);
			//	 $('#mQty').val(maxQty);
			//}
			//else{
			//	maxQty = sprintf("%.0f",number(0);
			//	$('#mQty').val(maxQty);
			//}

			if(maxQty < 0){
				maxQty = 0;
				$('#mQty').val(maxQty);
			}
			else{
				$('#mQty').val(maxQty);
			}

			$('#mQty').val(maxQty);
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
	
		function chkTime() {

			//var cDt = new Date();
			//tm = cDt.format("HH:MM:ss");
			//var now_utc = new Date(now.getUTCHours(),now.getUTCMinutes,now.getUTSSeconds());
			//var tm2 = new Date(now_utc.getTime() + offset)
			//window.alert(tm);
			//window.alert(tm2.toLocaleString());

			//if ( tm > "08:00:00" && tm < "16:15:00" ) {
			//	return "OK";
			//} else {
			//	return "NO";
			//}

            <?php
            $open = "08:30:00";
            $close = "16:15:00";
			if(time() > strtotime($open) && time() < strtotime($close)){
            ?>
            return "OK";
            <?php
            }
            else {
            ?>
            return "NO";
            <?php
            }
            ?>


			return "NO";

		}


		$("#chgBtn").click(function(){
			nPass = document.getElementById('nPassTxt').value;
			cPass = document.getElementById('cPassTxt').value;
			
			if((nPass == '') || (cPass == '')){
				alert('Please input new password and confirm password');
				return;
			}
			else if( (nPass.length < 8) || (cPass.length < 8) ){
				alert('Please input the password at least 8 character');
				return;
			}
			else{
				if(nPass == cPass){
					var userId = "<?=$userId;?>";
					//alert("User ID = "+ userId +"\nPassword = "+$("#nPassTxt").val());
					tr830000(userId,$("#nPassTxt").val());
					//window.location.replace("index.php");
				}
				else{
					alert("New password and confirm password are different");
				}
			}
		});

		$("#stockBtn").click(function(){
			code = document.getElementById('codeTxt').value;
			price = document.getElementById('priceTxt').value = "";
			amountTxt = document.getElementById('amountTxt').value = "";
			lotTxt = document.getElementById('lotTxt').value = "";
			$.mobile.loading('show');
			qryTr100000();
			$.mobile.loading('hide');
		});
	}

	function chkFraction() {
		price = document.getElementById('priceTxt').value;

		interval = 1;

		//console.log("prevPrice: " + Gprev);
	
	    //alert("DISINI!!! : " + lLimit + " --- "+uLimit);
					
		var reks = false;

		if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-') {
			interval = 1;
			lLimit_baru = lLimit;
			uLimit_baru = uLimit;
			reks = true;
 		} else {
             if (Gprev < 200) {
		        interval = 1;
				lLimit_baru = lLimit;
				uLimit_baru = uLimit;
		     }
			 else if ((Gprev >= 200) && (Gprev < 500)) {
			 	if(lLimit%2!=0){
					lLimit_baru = lLimit-(lLimit%2)+2;
					uLimit_baru = uLimit-(uLimit%2)-2;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 2;
			 }
			 else if ((Gprev >= 500) && (Gprev < 2000)) {
			 	if(lLimit%5!=0){
					lLimit_baru = lLimit-(lLimit%5)+5;
					uLimit_baru = uLimit-(uLimit%5)-5;
				}
				else{
					lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				}
				interval = 5;
			 }

		     else if ((Gprev >= 2000) && (Gprev < 5000)) {
		         if(lLimit%10!=0){
		            lLimit_baru = lLimit-(lLimit%10)+10;
				    uLimit_baru = uLimit-(uLimit%10)-10;
			     }
				 else{
				 	lLimit_baru = lLimit;
					uLimit_baru = uLimit;
				 }
		         interval = 10;
		     }
		     else if (Gprev >= 5000) {
		         if(lLimit%25!=0){
		            lLimit_baru= lLimit-(lLimit%25)+25;
		            uLimit_baru= uLimit-(uLimit%25)-25;
			     }
				 else{
			        lLimit_baru = lLimit;
			        uLimit_baru = uLimit;
			     }
			     interval = 25;
			 }
	     }

		console.log("Interval: " + interval);

		// uLimit - lLimit
		fract = [];
		x = 0;
		for (i=Number(lLimit_baru); i<=Number(uLimit_baru); i++) {
			fract.push(x.toString());
			if (reks) {
				i = (i + 1) - 1;
				x = i;
			} else {
				if (Gprev < 200) {
					i = (i + 1) - 1;
					x = i;
                } else if ((Gprev >= 200) && (Gprev < 500)) {
				    i = (i + 2) - 1;
					x = (i + 1);
                } else if ((Gprev >= 500) && (Gprev < 2000)) {
				    i = (i + 5) - 1;
					x = (i + 1);
				} else if ((Gprev >= 2000) && (Gprev < 5000)) {
					i = (i + 10) - 1;
					x = (i + 1);
				} else if (Gprev >= 5000) {
					i = (i + 25) - 1;
					x = (i + 1);
				}/* else {
					i = (i + Number(interval)) - 1;
					x = (x + Number(interval)) - 1;
				}*/

			}
			//fract.push(x.toString());

		}
		 //alert("DISINI!!! : " + Gprev +" price: "+ price + " code: "+code+" -- llimit baru "+lLimit_baru+" ulimit baru "+uLimit_baru);
	 //alert("DISINI!!! : " +fract);
	 
		console.log(fract);
/*
		for (i=0; i<fract.length; i++) {
			if (price == fract[i]) {
				return 1;
			}
		}
*/
		if((price >= lLimit_baru) && (price <= uLimit_baru)){
			if (code.substring(0,1) == 'X' || code.substring(0,2) == 'R-')
				ret = price % 1;
			else if(price > 5000)
				ret = price % 25;
			else if((price > 2000) && (price <= 5000))
				ret = price % 10;
			else if((price > 500) && (price <= 2000))
				ret = price % 5;
			else if((price > 200) && (price <= 500))
				ret = price % 2;
			else if((price >= 50) && (price <= 200))
				ret = price % 1;
			else if(price < 50)
				ret = price % 1;
		}
		else{
			return 0;
		}
			
		if(ret > 0){
			return 0;}
		else{
			return 1;}

		//return 0;
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
					mesgFld = jsRet.out[0].mesg;

					alert(mesgFld);

					clearInput();
				}
				//alert( "jsRet=" + JSON.stringify(jsRet) );
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				alert('error communition with server.');
				window.location.replace("index.php");
			}
		});
	}

	function clearInput() {
		document.getElementById('codeTxt').value = "";
		document.getElementById('priceTxt').value = "";
		document.getElementById('lotTxt').value = "";
		document.getElementById('mQty').value = "";
	}

	$(document).ready(function() {
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);
			if(page.attr('data-cache') == 'never'){
				page.remove();
			};
		});
		console.log("uLimit: " + uLimit + " lLimit: " + lLimit);
		/*
		$("#codeTxt").val('<?=$in_code;?>');
		$("#priceTxt").val('<?=$in_price;?>');
		$("#lotTxt").val('<?=$in_qty;?>');
		*/
		setInput();

		if ($("#priceTxt").val.length == 0) {
			$("#priceTxt").val("1");
		}

	});

</script>
</head> 
<body>
	<div data-role="page" id="home" data-cache="never">
	<? include "page-header.php" ?>
	<div class="buy-panel">
	<!-- <b>Buy Order</b> -->
	<table style="overflow-x:auto;">
	<tr>
		<td width="10%">Login ID</td>
		<td width="25%"><input id="loginTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">New Password</td>
        <td width="25%"><input id="nPassTxt" type="password" data-mini="true" /></td>
	</tr>
	<tr>
		<td width="10%">Confirm Password</td>
	    <td width="25%"><input id="cPassTxt" type="password" data-mini="true"></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
        <a id="chgBtn" href="#" style="width:50%" data-role="button" data-icon="check" data-mini="true">Change</a>
        </td>

	</tr>

	
	</table>
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
