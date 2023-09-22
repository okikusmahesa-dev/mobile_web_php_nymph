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
$loginId = $_SESSION["loginId"];
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
}
/*
$in_code = $_GET["code"];
$in_price = $_GET["price"];
$in_qty = $_GET["qty"];
*/
?>
<html>
<head> 
	<title>Form Withdraw</title> 
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
	//today_plus=new Date(Date.now() + 1 * 24 * 60 * 60 * 1000);



	
	var price = 0;
    var lot = 0;
    var qty = 0;
	var tot = 0;
	var ktp="";
	var incr = 0;

	$(document).ready(function() {
		
		var userId = "<?=$userId;?>";
		var loginId = "<?= strtoupper($loginId);?>";
		var pinId = "<?=$pinLogin;?>";
		var dt2 = new Date(); 
			dt2 = tr710024(now);
		$.mobile.loading('show');
		tr800300(userId,pinId);
		tr800000(userId);
		$.mobile.loading('hide');

		//alert(dt2);

		$("#sendBtn").one("click",function(){
			var jum_transfer = document.getElementById("jumlahtf").value;
			var client = document.getElementById("loginTxt").value.toUpperCase();
			var nama  = document.getElementById("accountNameTxt").value;
			//var nama  = "Edy";//document.getElementById("accountNameTxt").value;
			var id = ktp;
			
			var dana  = document.getElementById("danatxt").value;
			
			var cash = dana.replace(/,/g,"");
			//alert ("dana"+dana+"dana2:cash:"+cash);

			var bank = document.getElementById("bankTxt").value;
			var acname = document.getElementById("accountNameTxt").value;
			//var acname = "Edy";//document.getElementById("accountNameTxt").value;
			var acno = document.getElementById("noRekTxt").value;
			var date = dt2; 
			var desc = "Hasil Penjualan Saham/ Deviden/ Lain-lain.";
			//var desc = "Warisan";//"Hasil Penjualan Saham, Deviden, Lain-lain :_________________";

			if(jum_transfer == ""){
				alert("Masukkan jumlah transfer yang diinginkan.");
				location.reload();
			}else{
				if(confirm("Nama 	: "+nama+"\nlogin id	: "+loginId+"\njumlah transfer	: Rp. "+jum_transfer.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+"\n\nYakin melakukan withdraw?")){
					if (parseFloat(cash)<parseFloat(jum_transfer)){
						alert("Tidak bisa melakukan penarikan lebih besar dari Rp. " + cash.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +" !");
						location.reload();
					}else{
						if(parseFloat(jum_transfer)<50000){
							alert("Tidak bisa melakukan penarikan kurang dari 50000 !");
							location.reload();
						}else{
							var dtt = document.getElementById("tanggalTxt").value;
							var date = dtt.split("-");
							var d2t = (date[2]+date[1]+date[0]);
							//alert(document.getElementById("tanggalTxt").value);
							tr801700(client, nama, id, cash, bank, acname, acno, d2t, jum_transfer, desc );
						}
					}
				}else{
					//alter("masuk else");	
					location.reload();
				}
			}
		});
		
		
	});

	//tr check bussines day
	function tr710024(now){
		now.setDate(now.getDate()+1);
		tgl = now.format("yyyymmdd");
		$.ajax({
			type : "POST",
			url : "json/trproc.php",
			dataType : "json",
			data : "tr=710024&trade_date="+tgl,
			success : function(jsRet){
				if (jsRet.status != 1){
					alert("Error:" + jsRet.mesg);
				}else{
					if(jsRet.out[0].trade_flag==0){
						tr710024(now);
					}else{
						$('#tanggalTxt').val(now.format("dd-mm-yyyy"));
					}
				}
			}
		});
//		alert (now.format("yyyymmdd"));
		return now.format("yyyymmdd");
	}

	//tr withdraw cash by toni 20161130
	function tr801700(client,nama,id,cash,bank,acname,acno,date,rek,desc){
		//alert("masuk");
		//alert("data masuk : "+client+","+nama+","+id+","+cash+","+bank+","+acname+","+acno+","+date+","+rek+","+desc);
		$.ajax({
			type : "POST",
			url : "https://api.telegram.org/bot285943118:AAEwOul7hSfaEqGTAM5CTI-CbQJ0AkeyFBw/sendMessage",
			//url : "json/trproc.php",
			dataType : "json",
			data : "chat_id=281922927&text="+rek,
			//data : "tr=801700&ClientNo="+client+"&Name="+nama+"&no_id="+id+"&available_balance="+cash+"&Bank="+bank+"&AccountName="+acname+"&AccountNo="+acno+"&transfer_date="+date+"&transfer_account="+rek+"&transfer_desc="+desc,
			success : function(jsRet){
				alert(JSON.stringify(jsRet));
				//if (jsRet.status!=1){
				//	alert("Error: " + jsRet.mesg);
				//}else{
				//	alert("Permintaan Withdraw diterima");
				//	location = "index.php";
				//}
			}
		});
	}

	//start chgPassword
	function tr830001(userId, password) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=830001&userId=" + userId + "&password=" + password,
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert('There is an error with our server');
				} else {
					alert(jsRet.status+', userId = '+userId+', pass = '+password);
					alert('Your Pin has been changed.');
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
					accountName = jsRet.out[0].AccountName;
					Bank = jsRet.out[0].Bank;
					AccountNo = jsRet.out[0].AccountNo;
					//$('#clientno').text(ClientNo);
					$('#loginTxt').val("<?= $loginId ;?>");
					$('#accountNameTxt').val(accountName);
					$('#bankTxt').val(Bank);
					$('#noRekTxt').val(AccountNo);
					ktp=jsRet.out[0].no_id;		

					//
				}
			},
			error: function(data, status, err) {
				console.log("error forward : "+data);
				//alert('error communition with server.');
			}
		});
	}	

	function tr800000(id) {
		$.ajax({
			type: "POST",
			url: "json/trproc.php",
			dataType : "json",
			data: "tr=800000&userID=" + id + "&clientID=" + id + "&date=20161025",
			success: function(jsRet){
				if (jsRet.status != 1) {
					alert(jsRet.mesg);
				} else {
					//
					rdn = parseFloat(jsRet.out[0].rdn);
					cashbal = parseFloat(jsRet.out[0].cashBalance);
					bid = parseFloat(jsRet.out[0].bid);
					donebuy = parseFloat(jsRet.out[0].donebuy);
					interest = parseFloat(jsRet.out[0].interest);


					if(donebuy == 0){
						interest = (rdn)+((cashbal)-(rdn))-(bid);
					}else{
						interest = (rdn)+((cashbal)-(rdn))-donebuy-(bid);
					}
					//alert("interest = "+interest+"rdn: " + rdn +"cashbal"+cashbal+"bid"+bid);	
					
					if (interest<50000) interest = 0;
					
					$('#danatxt').val(interest.toLocaleString());

					//$('#clientno').text(ClientNo);
		
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
		function chkTime() {
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
				alert('Please input new pin and confirm pin');
				return;
			}
			else if( (nPass.length < 6) || (cPass.length < 6) ){
				alert('Please input the pin at least 6 character');
				return;
			}
			else{
				if(nPass == cPass){
					var userId = "<?=$userId;?>";
					//alert("User ID = "+ userId +"\nPassword = "+$("#nPassTxt").val());
					tr830001(userId,$("#nPassTxt").val());
					//window.location.replace("index.php");
				}
				else{
					alert("New pin and confirm pin are different");
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
		<td width="10%">Tanggal Transfer<br/><i>Transfer Date*</i></td>
		<td width="25%"><input id="tanggalTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Kode Nasabah<br/><i>Client Code</i></td>
		<td width="25%"><input id="loginTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Nama Rekening<br/><i>Account Name</i></td>
		<td width="25%"><input id="accountNameTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Nama Bank<br/><i>Name of Bank</i></td>
		<td width="25%"><input id="bankTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Nomor Rekening<br/><i>Account Number</i></td>
        <td width="25%"><input id="noRekTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Dana yang  Tesedia<br/><i>Available Balance</i></td>
        <td width="25%"><input id="danatxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Jumlah Transfer<br/><i font='10'>Transfer Account*</i></td>
        <td width="25%"><input id="jumlahtf" type="number" class="no-ime" data-mini="true" pattern="(\d{3})([\.])(\d{2})"/></td>
	</tr><hr>
	<tr>
		<td></td>	
		<td colspan="2" align="right">
        <a id="sendBtn" href="#" style="width:50%" data-role="button" data-icon="check" data-mini="true">Send</a>
        </td>

	</tr>

	
	</table>
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
