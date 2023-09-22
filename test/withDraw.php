<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
/*
//Connection to webservice
function getDB() {
    global  $env;
    print_r($env);
    $dbconn = odbc_connect("SQL-Mas", "sa", "OLTDB1!");
    if ($dbconn == FALSE) {
        printf("error:%s\n", odbc_errormsg());
        exit(1);
    }
    odbc_exec($dbconn, "use NYMH");
    return $dbconn;
}

    $dbcon = getDB();
	
	//Query
		$sql = "
			SELECT TOP 1 [entry_date]
			   ,[ClientNo]
			   ,[Name]
			   ,[no_id]
			   ,[available_balance]
			   ,[Bank]
			   ,[AccountName]
			   ,[AccountNo]
			   ,[transfer_date]
			   ,[transfer_account]
			   ,[transfer_desc]
			   ,[status]
			   ,[id_msg]
		   FROM [NYMH].[dbo].[TB_CLIENT_WITHDRAW_CASH]
		   WHERE id_msg = '18'
		";
		//echo "sql=$sql";
		// Get Result
		$res = odbc_exec($dbcon, $sql);

		// Get Data From Result
		while (odbc_fetch_row($res)){
			$entryDate  = odbc_result($res,"entry_date");
			$clientNo 	= odbc_result($res,"ClientNo");
			$Name       = odbc_result($res,"Name");
			$no_id      = odbc_result($res,"no_id");
			$aBalance   = odbc_result($res,"available_balance");
			$Bank       = odbc_result($res,"Bank");
			$aName      = odbc_result($res,"AccountName");
			$aNo      	= odbc_result($res,"AccountNo");
			$tDate      = odbc_result($res,"transfer_date");
			$tAccount   = odbc_result($res,"transfer_account");
			$tDesc		= odbc_result($res,"transfer_desc");
			$status     = odbc_result($res,"status");
		}
	*/
	 /*
	echo $clientNo."|";
	echo $entryDate."|";
	echo $Name."|";
	echo $no_id."|";
	echo $aBalance."|";
	echo $Bank."|";
	echo $aName."|";
	echo $tDate."|";
	echo $tAccount."|";
	echo $status;
	*/
?>
<html>
<head> 
	<title>Withdraw</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="img/bca.ico" />
	<link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
	<link rel="stylesheet" href="css/jquery.mobile-1.2.0.min.css" />
	<!--<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>-->
	<!--<script src="js/jquery-ui.js"></script>-->
	<!--<script type="text/javascript" src="js/flexigrid.pack.js"></script>-->
	<!--<script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>-->
	<script src="js/common.js"></script>
	<script src="js/sprintf.js"></script>
	<script src="js/date.format.js"></script>
	<!--<script type="text/javascript" src="timerPing.js?20151015b"></script>-->
	<style type="text/css">
	<? include "css/icon.css" ?>
	<? include "css/marketSummary.css" ?>
	</style>
	<script type="text/javascript">
		$("#OK").click(funciton(){
			var userId = "";
			var date   = "";
			var id_mesg = $("#idTxt").val();
			$.mobile.loading('show');
				tr7100002();
			$.mobile.loading('hide');
		});

		function tr710002(userId,date,id_mesg) {
			$.ajax({
				type: "POST",
				url:  "../json/trproc.php",
				dataType: "json",
				data: "tr=710002&userId= + userId + "&date" + date + "&id_mesg" + id_mesg,
				succes: function(jsRet){
					id(jsRet.status != 1){
						alert(jsRest.mesg);
					}else{
						consolse.log(jsRet.out);

						clientNo = (jsRet.out[0].clientno);
						name	 = (jsRet.out[0].name);
						no_id	 = Number(jsRet.out[0].no_id);
						availabel_balance = (jsRet.out[0].availabel_balance);
						bank	 = (jsRet.out[0].bank);
						account_name = (jsRet.out[0].account_name);
						account_no = (jsRet.out[0].account_no);
						transfer_date = (jsRet.out[0].transfer_date);
						transfer_account = (jsRet.out[0].transfer_account);
						status = (jsRet.out[0].status);
					}
				},
				error: function(data, status, err){
					console.log("error forwad : " + data);
				}
			});
		}

	</script>
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<input type="text" id="idTxt"><input type="submit" id="OK">
	<div>
	<h1 style="margin-top: 5px;margin-bottom: 5px; text-align:center;">Permohonan Penarikan Dana</h1>
	<h2 style="margin-top: 3px;margin-bottom: 3px; text-align:center; font-style:italic;">(Request transfer for fund)</h2>
	</br>
	<table border="0" width="100%" >
		<tr><td>Yang bertanda tangan dibawah ini, saya:</td></tr>
		<tr><td>Nama </br> <i>Client Name</i></td> 
			<td><label id="name"></label> </td>
			<td>&nbsp; Kode Nasabah </br> &nbsp; <i>Client Code </i> </td><td><label id="clientNo"></label></td>
		</tr>
		<tr>
			<td>No KTP </br> <i>ID Number</i></td><td><label id="no_id"></label></td>
		</tr>
	</table>
	<p>Dengan  ini  memberikan instruksi kepada PT.BCA Sekuritas untuk melakukan transfer dana milik saya yang tersedia di Rekening Dana Investor atas nama saya ke rekening sebagai berikut :<br>
	<i>Herewith give to instructed PT.BCA Securities to transfer my funds are available in account investor fund under my name to the following account:</i></p>
	<table border="0" width="50%" align="center">
		<tr>
			<td>Dana yang Tersedia </br> <i>Available Balance</i></td><td><label id="availabel_balance">Rp.</label></td>
		</tr>
		<tr>
			<td>Nama Bank </br> <i>Name of Bank</i></td><td> <label id="bank"></label></td>
		</tr>
		<tr>
			<td>Nama Rekening </br> <i>Account Name</i></td><td> <label id="acccount_name"></label></td>
		</tr>
		<tr>
			<td>Nomor Rekening </br> <i>Account Number</i></td><td> <label id="account_no"></label></td>
		</tr>
		<tr>
			<td colspan="2">*)WAJIB DIISI (MUST BE FILLED)</td>
		</tr>
		<tr>
			<td>Tanggal Transfer </br> <i>Transfer Date</i>*</td><td><label id="transfer_date></label></td>
		</tr>
		<tr>
			<td>Jumlah Transfer </br> <i>Transfer Account</i>*</td><td> <label id="transfer_account"></label></td>
		</tr>
		<tr>
			<td>Deskripsi Transfer </br> <i>Transfer Description</i>*</td>
			<td>
				<label id="status"></label>
				<!--
				<label><input type="radio" name="descTra" data-role="controlgroup">Hasil Penjualan Saham/<i>Sales Proceeds</i></label></br>
				<label><input type="radio" name="descTra" data-role="controlgroup">Deviden/<i>Dividends</i></label></br>
				<label><input type="radio" name="descTra" data-role="controlgroup">Lain - Lain/<i>Others</i></label> &nbsp;
				<input type="Text" name="descTra">
				-->
			</td>
		</tr>
	</table>
	<p>Dengan disetujui pemindahan dana oleh PT.BCA Sekuritas (BCAS) sesuai dengan spesifikasi diatas, maka nasabah telah menyetujui pula hal-hal  sebagai berikut :<br>
	<i>In Consideration of PT.BCA Securities (BCAS) agreeing to make the transfer specified herein, it is agreed as follows :</i></p>
	<p>1. surat permohonan ini adalah sah dan benar untuk dijalankan sempai BCAS mendapatkan perintah tertulis untuk merubah atau membatalkan
	<br><i>&nbsp;&nbsp;&nbsp;This standing letter of transfer will remain in full force and effect until notice of revocation or modification is received by BCAS</i></p>
	<p>2. BCAS dan seluruh karyawannya terbebaskan dari segala tuntutan dan kewajiban dengan disetujuinya permohonan transfer dana ini, tidak terbatas dan mengikat kepada pewaris dan penerus dari pemohon.
	<br><i>&nbsp;&nbsp;&nbsp;&nbsp;BCAS and its employees liberated from all calims and liabilities with the approval of the application for the transfer of funds, this's not limited and shall be binding to the heirs and successors of the undersigned.</i></p>
	</div>
	Yang Mengajukan:
	
	</br></br></br>
	<p id="clientno">&emsp;&emsp;</p>
	<p id="name"></p>
	
	
	<p align="center"><input type="button" value="PRINT" id="save"></p>
</div>
</body>
</html>
