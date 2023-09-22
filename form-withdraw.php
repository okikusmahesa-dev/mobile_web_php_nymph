<?
//
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
//

session_start();
if ($_SESSION["isLogin"] != 1) {
	echo "Please login...";
	header("refresh:3; url=login.php");
	exit(0);
}
$userId = $_SESSION["userId"];
$loginId = $_SESSION["loginId"];
$pinState = 1;
$pinLogin = 12345678;
if ($_SESSION["pinState"] != 1) {
	echo "<script>alert('Please Input PIN first.');</script>";
	header("refresh:1; url=inPin.php");
	exit(0);
} else {
	$pinState = $_SESSION["pinState"];
	$pinLogin = $_SESSION["pin"];
}
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
	<script type="text/javascript" src="js/curPriceExt.js?20170919b"></script>
	<script type="text/javascript">
	var userId = "<?=$userId;?>";
	var now = new Date();
	var price = 0;
    var lot = 0;
    var qty = 0;
	var tot = 0;
	var ktp="";
	var incr = 0;
    var lock1 = 0;
    
	$(document).ready(function() {
		
		var userId = "<?=$userId;?>";
		var loginId = "<?= strtoupper($loginId);?>";
		var pinId = "<?=$pinLogin;?>";
		var dt2 = new Date(); 
			dt2 = tr710024(now);
        //console.log(userId+"-"+loginId);
		$.mobile.loading('show');
		//tr800300(userId,pinId);
		tr801701(userId,pinId);
		//tr800000(userId);
		$.mobile.loading('hide');

		//alert(dt2);

		$("#sendBtn").click(function(){
            if(lock1 != 1){
                alert("proses pengumpulan data belum selesai, harap tunggu beberapa saat lagi");
            }else{
		    	var jum_transfer = document.getElementById("jumlahtf").value;
			    var client = document.getElementById("loginTxt").value.toUpperCase();
		    	var namaa  = document.getElementById("namaTxt").value;
				var nama = namaa.replace("'","");
	    		//var nama  = "Edy";//document.getElementById("accountNameTxt").value;
    			//var id = ktp;
    			var id = document.getElementById("noidTxt").value;
			
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
				console.log("name : "+nama);
				console.log("acctname : "+acname);
				console.log("noid : "+id);
   				 
    			if(jum_transfer == ""){
				    alert("Masukkan jumlah transfer yang diinginkan.");
			    	location.reload();
		    	}else if (cash == 0){
					alert("Tidak bisa melakukan penarikan dana, saldo anda = 0");
					location.reload();
				}
				else{
	    			if(confirm("Nama 	: "+nama+"\nlogin id	: "+loginId+"\njumlah transfer	: Rp. "+jum_transfer.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+"\n\nYakin melakukan withdraw?")){						
						if (parseFloat(cash)<parseFloat(jum_transfer)){
						    alert("Tidak bisa melakukan penarikan lebih besar dari Rp. " + cash.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +" !");
					    	location.reload();
				    	}
				    	else if (cash == jum_transfer){
							alert("Minimal dana mengendap pada saldo anda adalah Rp. 1,-");
							location.reload();
							// document.getElementById("jumlahtf").value; //kelipatan 100
						}else{
			    			if(parseFloat(jum_transfer)<50000){
		    					alert("Tidak bisa melakukan penarikan kurang dari 50000 !");
	    						location.reload();
    						}
    						else if (jum_transfer % 10){
    							alert("Tidak bisa melakukan penarikan dana.\nPenarikan dana harus kelipatan 100")
    							location.reload();
    						}
    						else{
							    var dtt = document.getElementById("tanggalTxt").value;
						    	var date = dtt.split("-");
					    		var d2t = (date[2]+date[1]+date[0]);
				    			//alert(document.getElementById("tanggalTxt").value);
			    				tr801707(client, nama, id, cash, bank, acname, acno, d2t, jum_transfer, desc );
		    				 }
	    				}
    				}else{
				    	//alter("masuk else");	
			    		location.reload();
		    		}
	    		}
            }
		});
		
		
	});
    

	//tr check bussines day
	function tr710024(now){
        lock1 = 1;
    }
    
	//tr withdraw cash by toni 20161130
	//update tr801707 Aldo 20220711
	function tr801707(client,nama,id,cash,bank,acname,acno,date,rek,desc){
		//alert("masuk");
		//alert("data masuk : "+client+","+nama+","+id+","+cash+","+bank+","+acname+","+acno+","+date+","+rek+","+desc);
        pHashOrg = "tr=801707&ClientNo="+client+"&Name="+nama+"&no_id="+id+"&available_balance="+cash+"&Bank="+bank+"&AccountName="+acname+"&AccountNo="+acno+"&transfer_date="+date+"&transfer_account="+rek+"&transfer_desc="+desc;
        pHash    = "phash=" + Base64.encode(pHashOrg);
		$.ajax({
			type : "POST",
			url : "json/trproc.php",
			dataType : "json",
			data : pHash,
			success : function(jsRet){
				if (jsRet.status!=1){
					alert("Error: " + jsRet.mesg);
				}else{
					//alert("Permintaan Withdraw diterima");
					//alert("Permintaan Penarikan Dana sudah kami terima.\nPenarikan Dana akan kami proses apabila dana tersedia di RDI");
					//location = "withdraw-hist.php";
					statusFld 	= jsRet.out[0].status;
					//cntFld 		= jsRet.out[0].cnt;

					if(statusFld != 1){
						//alert("Permintaan Penarikan Dana sudah kami terima.\nPenarikan Dana akan kami proses apabila dana tersedia di RDI");
						alert("Mohon Maaf Jumlah Penarikan Anda Melebihi Batas Proses Maksimal");
						location.reload()
					} else {
						//alert("Mohon Maaf Jumlah Penarikan anda melebihi batas proses maximal");
							alert("Permintaan Penarikan Dana sudah kami terima.\nPenarikan Dana akan kami proses apabila dana tersedia di RDI");
							location = "withdraw-hist.php";
					
					}
				}
			}
		});
	}

    function gotoHist(){
        location = "withdraw-hist.php";        
    }
    
	function tr801701(id) {
        pHashOrg = "tr=801701&loginId=" + id;
        pHash    = "phash=" + Base64.encode(pHashOrg);

        $.ajax({
            type: "POST",
            url: "json/trproc.php",
            dataType : "json",
            data: pHash,
            success: function(jsRet){
                if (jsRet.status != 1) {
                    alert(jsRet.mesg);
                } else {
                    //
                    console.log(jsRet);
					tdate 		= jsRet.out[0].tdate;	
					clientno	= jsRet.out[0].clientno;	
					name 		= jsRet.out[0].name;	
					bank 		= jsRet.out[0].bank;	
					acctno 		= jsRet.out[0].acctno;
					acctno1 	= jsRet.out[0].acctnoxxx;
					balance 	= jsRet.out[0].balance;	
					acctname 	= jsRet.out[0].acctname;	
					noid 		= jsRet.out[0].noid;	
                    //$('#clientno').text(ClientNo);
					//$('#tanggalTxt').val(now.format("dd-mm-yyyy"));
                    // 20210717 update -> 20220711
                    trfdate       = sprintf("%s-%s-%s", tdate.substring(6,8), tdate.substring(4,6), tdate.substring(0,4));
					$('#tanggalTxt').val(trfdate);
                    $('#loginTxt').val("<?= $loginId ;?>");
                    $('#accountNameTxt').val(acctname);
                    $('#namaTxt').val(name);
                    $('#noidTxt').val(noid);
                    $('#bankTxt').val(bank);
                    $('#noRekTxt').val(acctno);
                    $('#noRekTxt1').val(acctno1);
                    $('#danatxt').val(addCommas(balance));
                    //ktp=jsRet.out[0].no_id;
                    lock1 = 1;
                    console.log('lock1 open');
                    //
                }
            },
            error: function(data, status, err) {
                console.log("error forward : "+data);
                //alert('error communition with server.');
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
		<td width="25%"><input id="accountNameTxt" type="text" class="no-ime" data-mini="true" disabled />
						<input id="namaTxt" type="hidden" class="no-ime" data-mini="true" disabled />
						<input id="noidTxt" type="hidden" class="no-ime" data-mini="true" disabled />
		</td>
	</tr>
	<tr>
		<td width="10%">Nama Bank<br/><i>Name of Bank</i></td>
		<td width="25%"><input id="bankTxt" type="text" class="no-ime" data-mini="true" disabled /></td>
	</tr>
	<tr>
		<td width="10%">Nomor Rekening<br/><i>Account Number</i></td>
        <td width="25%"><input id="noRekTxt1" type="text" class="no-ime" data-mini="true" disabled /></td>
        <input id="noRekTxt" type="hidden" class="no-ime" data-mini="true" disabled />
	</tr>
	<tr>
		<td width="10%">Dana yang  Tersedia<br/><i>Available Balance</i></td>
        <td width="25%"><input id="danatxt" type="text" class="no-ime" data-mini="true" disabled /></td>
        <input id="dana2txt" type="hidden" class="no-ime" data-mini="true" disabled />
	</tr>
	<tr>
		<td width="10%">Jumlah Transfer<br/><i font='10'>Transfer Account*</i></td>
        <td width="25%"><input id="jumlahtf" type="number" class="no-ime" data-mini="true" pattern="(\d{3})([\.])(\d{2})"/></td>
	</tr><hr>
	<tr>
		<td>
        <a id="histBtn" href="#" style="width:50%" data-role="button" data-icon="go" data-mini="true" onclick="gotoHist()" >History</a>
        </td>	
		<td colspan="2" align="right">
        <a id="sendBtn" href="#" style="width:50%" data-role="button" data-icon="check" data-mini="true" disabled="disabled">Send</a>
        </td>

	</tr>

	
	</table>
	</div>
	<? include "page-footer-buysell.php" ?>
	</div>
</body>
</html>
