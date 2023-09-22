<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

//Connection to webservice
function getDB() {
    global  $env;
    print_r($env);
    //$dbconn = odbc_connect("SQL-Mas", "sa", "OLTDB1!");
	$dbconn = odbc_connect("SQL-Dev", "sa", "OLTDEV1!");
    if ($dbconn == FALSE) {
        printf("error:%s\n", odbc_errormsg());
        exit(1);
    }
    odbc_exec($dbconn, "use NYMH");
    return $dbconn;
}

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
		$hasil_mod = fmod($satuan, 1000000000000);
		return trim(sprintf("%s triliun %s", Terbilang($hasil_bagi), Terbilang($hasil_mod)));
	}else{
		return "pusing";
	}
}

	$url = $_SERVER['REQUEST_URI'];
	$url_components = parse_url($url);
	parse_str($url_components['query'], $params); 
	for($i=0; $i < $params['cnt']; $i++){
		
		$dbcon = getDB();
		$id_msg = $params[$i];
		$clientNo = '';
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
			WHERE id_msg = '$id_msg'
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

			$sql = "SELECT user_id FROM TB_LOGIN_MASTER where login_id='$clientNo'";
			$res2 = odbc_exec($dbcon,$sql);
			while(odbc_fetch_row($res2)){
				$user_id = odbc_result($res2, "user_id");
			}

			
		
?>
<html>
	<head> 
		<title></title> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style type="text/css">

			@media print {
				a{display: none;}
				#hide { display:none; }
			}
			@media screen and projection {
				a{ display :inline; }
			}
			@media all {
				.page-break { display: none; }
			}

			@media print {
				.page-break { display: block; page-break-before: always; }
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
	</head> 
	<body>
	<?php
		if($i != 0){
			echo "<div class='page-break'></div>";
		}
	?>
	
		<div id="print">
			</br></br>
			<div>
				<h1 style="margin-top: 5px;margin-bottom: 5px; text-align:center;">Permohonan Penarikan Dana</h1>
				<h2 style="margin-top: 3px;margin-bottom: 3px; text-align:center; font-style:italic;">(Request transfer for fund)</h2>
				</br>
				<table border="0" width="1100">
					<tr>
						<td colspan="4">Yang bertanda tangan dibawah ini, saya:</td>
					</tr>
					<tr>
						<td>Nama </br> <i>Client Name</i></td> 
						<td><label><?php echo "<b>".$Name."</b>"; ?></label> </td>
						<td>&nbsp; Kode Nasabah </br> &nbsp; <i>Client Code </i> </td><td><label><?php echo "<b>".$user_id."</b>"; ?></label></td>
					</tr>
					<tr>
						<td>No KTP </br> <i>ID Number</i></td><td><label><?php echo "<b>".$no_id."</b>"; ?></label></td>
					</tr>
				</table>
				<p>Dengan  ini  memberikan instruksi kepada PT.BCA Sekuritas untuk melakukan transfer dana milik saya yang tersedia di Rekening Dana Investor atas nama saya ke rekening sebagai berikut :<br>
				<i>Herewith give to instructed PT.BCA Securities to transfer my funds are available in account investor fund under my name to the following account:</i></p>
				<table border="0" width="750" align="center">
					<tr>
						<td>Nama Bank </br> <i>Name of Bank</i></td><td> <label><?php echo "<b>".$Bank."</b>"; ?></label></td>
					</tr>
					<tr>
						<td>Nama Rekening </br> <i>Account Name</i></td><td> <label><?php echo "<b>".$aName."</b>"; ?></label></td>
					</tr>
					<tr>
						<td>Nomor Rekening </br> <i>Account Number</i></td><td> <label><?php echo "<b>".$aNo."</b>"; ?></label></td>
					</tr>
					<tr>
						<td colspan="2">*)WAJIB DIISI (MUST BE FILLED)</td>
					</tr>
					<tr>
						<td>Tanggal Transfer </br> <i>Transfer Date</i>*</td><td><label><?php echo "<b>".date("d-F-Y", strtotime($tDate))."</b>"; ?></label></td>
					</tr>
					<tr>
						<td>Jumlah Transfer </br> <i>Transfer Account</i>*</td><td> <label>Rp. <?php echo "<b>".number_format($tAccount)."</b>"; ?></label></td>
					</tr>
					<tr>
						<td>Terbilang </br> <i>be calculated</i></td><td><?php echo Terbilang($tAccount)." Rupiah"; ?></td>
					</tr>
					<tr>
						<td>Deskripsi Transfer </br> <i>Transfer Description</i>*</td>
						<td><label><?php echo "<b>".$tDesc."</b>"; ?></label></td>
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
				<?php } ?>
				</br>
				<p align="center"><input id="hide" type="button" value="PRINT" id="save" onClick="window.print()"></p>
			</div>
		</div>
	</body>
</html>
