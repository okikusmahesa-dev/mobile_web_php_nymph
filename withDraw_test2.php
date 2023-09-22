<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

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
	$id_msg = $_GET['id_msg'];
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
		";
		//echo "sql=$sql";
		// Get Result
		$res = odbc_exec($dbcon, $sql);

		// Get Data From Result
		while (odbc_fetch_row($res)){
			$entryDate  = odbc_result($res,"entry_date");
			$clientNo 	= odbc_result($res,"ClientNo");
			if (strlen(rtrim($clientNo)) > 4){
				$clientNo2 	= substr(odbc_result($res,"ClientNo"),3,4);
			}
			else{
				$clientNo2 = $clientNo;
			}
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
function Terbilang($satuan) {
	$satuan = (float)$satuan;
	$huruf = array ("","Satu","Dua","Tiga","Empat","Lima","Enam","Tujuh","Delapan","Sembilan","Sepuluh","Sebelas");
	if ($satuan < 12){
		return $huruf[$satuan];
	}elseif ($satuan < 20){
		return $huruf[$satuan - 10] . "Belas";
	}elseif ($satuan < 100){
		$hasil_bagi = (int) ($satuan / 10);
		$hasil_mod = $satuan % 10;
		return trim(sprintf("%s Puluh %s", $huruf[$hasil_bagi], $huruf[$hasil_mod]));
	}elseif ($satuan < 200){
		return sprintf(" Seratus %s" , Terbilang($satuan - 100));
	}elseif ($satuan < 1000){
		$hasil_bagi = (int) ($satuan / 100);
		$hasil_mod = $satuan % 100;
		return trim(sprintf("%s Ratus %s", $huruf[$hasil_bagi], Terbilang($hasil_mod)));
	}elseif ($satuan < 2000){
		return trim(sprintf(" Seribu %s" , Terbilang($satuan - 1000)));
	}elseif ($satuan < 1000000){
		$hasil_bagi = (int) ($satuan / 1000);
		$hasil_mod = $satuan % 1000;
		return trim(sprintf("%s Ribu %s", Terbilang($hasil_bagi), Terbilang($hasil_mod)));
	}elseif ($satuan < 1000000000){
		$hasil_bagi = (int) ($satuan / 1000000);
		$hasil_mod = $satuan % 1000000;
		return trim(sprintf("%s Juta %s", Terbilang($hasil_bagi), Terbilang($hasil_mod)));
	}elseif ($satuan < 1000000000000){
		$hasil_bagi = (int) ($satuan / 1000000000);
		$hasil_mod = fmod($satuan, 1000000000);
		return trim(sprintf("%s Milyar %s", Terbilang($hasil_bagi), Terbilang($hasil_mod)));
	}elseif ($satuan < 1000000000000000){
		$hasil_bagi = (int) ($satuan / 1000000000000);
		$hasil_mod = fmod($Satuan, 1000000000000);
		return trim(sprintf("%s triliun %s", Terbilang($hasil_bagi), Terbilang($hasil_mod)));
	}else{
		return "pusing";
	}
}
	
?>
<html>
<head> 
<title></title> 
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--
<link rel="shortcut icon" href="img/bca.ico" />
<link rel="stylesheet" type="text/css" href="js/css/flexigrid.pack.css" />
<link rel="stylesheet" href="css/jquery.mobile-1.2.0.min.css" />
-->
<!--<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>-->
<!--<script src="js/jquery-ui.js"></script>-->
<!--<script type="text/javascript" src="js/flexigrid.pack.js"></script>-->
<!--<script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>-->
<!--<script src="js/common.js"></script>-->
<!--<script src="js/sprintf.js"></script>-->
<!--<script src="js/date.format.js"></script>-->
<!--<script type="text/javascript" src="timerPing.js?20151015b"></script>-->
<style type="text/css">

@media print {
a{display: none;}
#hide { display:none; }
}
@media screen and projection {
a{ display :inline; }
}
*/
@media print{
body{ background-color:#FFFFFF, background-image:none; color:#000000}
#ad{ display: none;}
#leftbar{ diaplay:none;}
#contentarea{ width: 100%;}
}
*/
</style>
<!--
<script type="text/javascript">
function print(divName){
var printContents = documents.getElementById(divName).innerHTML;
var originalContents = document.body.innerHTML;
document.getElementById('header').style.display = 'none';					
document.getElementById('footer').style.display = 'none';					
document.boyd.innnerHMTL = printContents;
window.print();
document.body.innerHTML = originalContents;
}
</script>
-->
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script>
	$( function() {
		$( "#datepicker" ).datepicker();
	} );
</script>
</head> 
<body>
<!--
<div id="print">
<div id="header" style="backgorund.color:White;"></div>
<div id="footer" style="backgorund.color:White;"></div>
</div>
-->
<div id="print">
</br></br>
<div>
<h1 style="margin-top: 5px;margin-bottom: 5px; text-align:center;">Permohonan Penarikan Dana</h1>
<h2 style="margin-top: 3px;margin-bottom: 3px; text-align:center; font-style:italic;">(Request transfer for fund)</h2>
</br>
<table border="0" width="1100">
<tr><td colspan="4">Yang bertanda tangan dibawah ini, saya:</td></tr>
<tr><td>Nama </br> <i>Client Name</i></td> 
<td><input type='text' value='<?php echo "<b>".$Name."</b>"; ?>' disabled></input> </td>
<td>&nbsp; Kode Nasabah </br> &nbsp; <i>Client Code </i> </td><td><input type='text' value='<?php echo "<b>".$clientNo2."</b>"; ?>' disabled ></input></td>
</tr>
<tr>
<td>No KTP </br> <i>ID Number</i></td><td><input type='text' value='<?php echo "<b>".$no_id."</b>"; ?>' disabled></input></td>
</tr>
</table>
<p>Dengan  ini  memberikan instruksi kepada PT.BCA Sekuritas untuk melakukan transfer dana milik saya yang tersedia di Rekening Dana Investor atas nama saya ke rekening sebagai berikut :<br>
<i>Herewith give to instructed PT.BCA Securities to transfer my funds are available in account investor fund under my name to the following account:</i></p>
<table border="0" width="750" align="center">
<tr>
	<td>Nama Bank </br> <i>Name of Bank</i></td><td> <input type='text' value='<?php echo "<b>".$Bank."</b>"; ?>' disabled /></td>
</tr>
<tr>
	<td>Nama Rekening </br> <i>Account Name</i></td><td> <input type='text' value='<?php echo "<b>".$aName."</b>"; ?>' disabled /></td>
</tr>
<tr>
	<td>Nomor Rekening </br> <i>Account Number</i></td><td> <input type='text' value='<?php echo "<b>".$aNo."</b>"; ?>' disabled /></td>
</tr>
<tr>
	<td colspan="2">*)WAJIB DIISI (MUST BE FILLED)</td>
</tr>
<tr>
	<td>Tanggal Transfer </br> <i>Transfer Date</i>*</td><td><input type="text" id="datepicker"/></td>
</tr>
<tr>
	<td>Jumlah Transfer </br> <i>Transfer Account</i>*</td><td> <label>Rp.</label><input type='text' name='transfer_account'></input></td>
</tr>

<!--<tr>
	<td>Terbilang </br> <i>be calculated</i></td><td><?php echo Terbilang($tAccount)." Rupiah"; ?></td>
</tr>-->

<tr>
	<td>Deskripsi Transfer </br> <i>Transfer Description</i>*</td>
<td>
	<label><?php echo "<b>".$tDesc."</b>"; ?></label>
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
<div>
<table border="0">
<tr>
	<td width="800">Yang Mengajukan,</td>
	<td width="700">Mengetahui,</td>
	<td width="1000" align="right">Menyetujui,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	<td width="500"></td>
</tr>
<tr>
	<td></br></td>
	<td></br></td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td></br></td><td></td><td></td>
	<td></br></td>
</tr>
<tr>
	<td></br></td><td></td><td></td>
	<td></br></td>
</tr>
<tr>
	<td></br></td><td></td><td></td>
	<td></br></td>
</tr>
<tr>
	<td></td><td></td><td></td>
	<td ></td>
</tr>
<tr>
	<td></td><td></td><td></td>
	<td></td>
</tr>
<tr>
	<td width="700">&emsp;&emsp;<?php echo "<b>" .$clientNo. "</b>"; ?></td>
	<td width="800" align="right"></td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td width="800"><?php echo "<b>" .$Name. "</b>"; ?></td>
	<td><b>EQUITY SALES</b></td>
	<td width="1000" align="right"><b>RISK MANAGEMENT </b> &nbsp;&nbsp;&nbsp;</td>
	<td width="500"><b> FINANCE</td>
</tr>
</table>
<!--
<div align="left">
Yang Mengajukan:

</br></br></br>
<p>&emsp;&emsp;<?php echo "<b>".$clientNo."</b>"; ?></p>
<p><?php echo "<b>".$Name."</b>"; ?></p>
		</div>
		<div align="right">
			Menyetujui,
			</br></br></br>
			<p>Risk Managaement</p>
		</div>
		-->
	<div>
</br>
	<p align="center"><input id="hide" type="button" value="PRINT" id="save" onClick="window.print()"></p>
</div>
</body>
</html>
