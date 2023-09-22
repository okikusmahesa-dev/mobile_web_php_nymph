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
	<script type="text/javascript">
	var userId = "<?=$userId;?>";
	var now = new Date();
	today = now.format("yyyymmdd");

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
		$("#chgBtn").click(function(){
			nPass = document.getElementById('nPassTxt').value;
			cPass = document.getElementById('cPassTxt').value;
			
			if((nPass == '') || (cPass == '')){
				alert('Please input new password and confirm password');
				return;
			}
            if( nPass !=  cPass) {
				alert('Pastikan password nya sama');
				return;
            }

            var pattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!pattern.test(nPass)) {
                alert("Pastikan password baru memiliki minimum 8 karakter dan setidaknya 1 buah angka dan huruf.");
                return;
            } else{
				var userId = "<?=$userId;?>";
				//alert("User ID = "+ userId +"\nPassword = "+$("#nPassTxt").val());
				tr830000(userId,$("#nPassTxt").val());
				//window.location.replace("index.php");
                //alert("change password");
			}
		});

	}

	function clearInput() {

	}

	$(document).ready(function() {
		jQuery('div').live('pagehide', function(event, ui){
			var page = jQuery(event.target);
			if(page.attr('data-cache') == 'never'){
				page.remove();
			};
		});
		setInput();
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
