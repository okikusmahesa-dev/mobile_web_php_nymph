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
		return $huruf[$satuan - 10] . " Belas ";
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
		$seq = $params[$i];
		//$clientNo = '';
		//Query
			$sql = "
				SELECT TOP 1 
					A.seq
					,A.stock_code
					,A.login_id
					,A.user_id
					,A.user_name
					,A.price
					,convert(varchar(15),cast(A.ex_date as date),103) as date_ex
					,convert(varchar(15),cast(A.apply_date as date),23) as date_app
					,A.qty
					,A.amount
					,A.status
					,A.sas_status
					,A.sas_upddate
					,A.sas_note
					,sales_no = (select C.SalesNo from OLT_Client C WHERE C.ClientNo = A.user_id)
					,no_id = (select C.no_id from OLT_Client C WHERE C.ClientNo = A.user_id)
			FROM TB_APPL_RIGHT A
			WHERE seq = '$seq'
			";
			
			//echo "sql=$sql";
			// Get Result
			$res = odbc_exec($dbcon, $sql);

			// Get Data From Result
			while (odbc_fetch_row($res)){
				$entryDate  = odbc_result($res,"entry_date");
				$LoginID    = odbc_result($res,"login_id");
				$Name       = odbc_result($res,"user_name");
				$UserID     = odbc_result($res,"user_id");
				$Qty        = odbc_result($res,"qty");
				$Code       = odbc_result($res,"stock_code");
				$ExDate     = odbc_result($res,"date_ex");
				$Price      = odbc_result($res,"price");
				$Amount     = odbc_result($res,"amount");
				$ApplyDate  = odbc_result($res,"date_app");
				$Sales      = odbc_result($res,"sales_no");
				$NoId       = odbc_result($res,"no_id");
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
			.myDiv {
			  /*color:#ffffff;*/
			  color:#000000;
			  /*background-color: rgb(31, 78, 121);*/
			  text-align: center;
			  margin-top: 5px;
			  margin-bottom: 5px;
			  text-align:center;
			}
			.myTab {
			  border: 0px outset black;
			}
			.myTab1 {
			  border: 1px outset black;
			}
			.size {
  				font-size: 12px;
			}
			input[type="radio"]{
  accent-color:green;
}
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
			<img src="img/logo_bcas.png" width="180" height="30">
			<div class="myDiv"><h2 style="margin-top: 5px;margin-bottom: 5px; text-align:left;">PT. BCA SEKURITAS</h2><br></div>
			<div>
				<div class="myDiv"><h4>FORMULIR PERMOHONAN PELAKSANAAN HAK<br><i>EXERCISE OF RIGHTS REQUEST FORM</i></h4></div>
				<p>
					Saya/Kami yang bertandatangan dibawah ini / <i>I/We the undersigned below, </i><br>
					Nama / <i>Client Name</i> : <?php echo "<b>".$Name."</b>"; ?> &emsp; Kode Nasabah / <i> Client code </i> : <?php echo "<b>".$UserID."</b>"; ?><br>
					Nomor Identitas / <i>valid ID Number</i> : <?php echo "<b>".$NoId."</b>"; ?> 
				</p>
				<p align="justify">dengan ini memberikan instruksi kepada PT BCA Sekuritas ("BCAS") untuk melaksanakan Hak atas Efek, baik berbentuk Hak Memesan Efek Terlebih Dahulu ("HMETD") maupun berbentuk Waran, dengan detail sebagai berikut: <br>
				<i>hereby instruct PT BCA Sekuritas ("BCAS") to exercise the Rights of Securities, either in the form of exercise pre-emptive rights<br> ("Right Issue") or Warrant, with the following details: </i></p>
				<table border="0" width="750" >
					<tr >
						<td class="myTab" width="30%">a.&emsp;Jenis Efek/<i>Type of Securites</i></td> 
						<td class="myTab" width="1%">:</td>
						<td class="myTab" width="10%"><label><img src="img/checklist.png" width="15" height="15" >&ensp;HMETD/<i>Right Issue</i></label></td>
        				<td class="myTab" width="20%"><label><img src="img/unchecklist.png" width="15" height="15" >&ensp;Waran/<i>Warrant Issue</i></label></td>
					</tr>
					<tr>
						<td width="10%"></td> 
						<td width="1%"></td>
						<td width="25%">(note: pilih salah satu/<i>choose one</i>)</td>
					</tr>
					<tr><td><br></td></tr>
					<tr>
						<td class="myTab" width="30%">b.&emsp;Kode Efek/<i>Securites Code</i></td> 
						<td class="myTab" width="1%">:</td>
						<td class="myTab" width="10%"><?php echo "<b>".$Code."</b>"; ?></td>
					</tr>
					<tr><td><br></td></tr>
					<tr>
						<td class="myTab" width="30%">c.&emsp;Jumlah Hak yang <br>&emsp;&ensp;dilaksanakan/<i>Number of Rights to be <br>&emsp;&ensp;exercised</i></i></td> 
						<td class="myTab" width="1%">:</td>
						<td class="myTab" width="10%"><?php echo "<b>".number_format($Qty)."</b>"; ?> &nbsp;lembar/shares 
						<br>Terbilang/<i>amount in word:</i></td>
					<tr>
						<td class="myTab" width="10%"></td> 
						<td class="myTab" width="1%"></td>
						<td class="myTab" width="30%"><?php echo Terbilang($Qty)." Lembar"; ?></td>
					</tr>
					<tr><td><br></td></tr>
					<tr>
						<td class="myTab" width="30%">d.&emsp;Harga Pelaksanaan/<i>Execution price</i></td> 
						<td class="myTab" width="1%">:</td>
						<td class="myTab" width="10%">Rp. <?php echo "<b>".number_format($Price)."</b>"; ?> per lembar/<i>per shares</i></td>
					</tr>
					<tr><td><br></td></tr>
					<tr>
						<td class="myTab" width="30%">e.&emsp;Harga Pelaksanaan/<i>Execution  <br> &emsp;&ensp;Amount</i> ( c. x d. ) </td> 
						<td class="myTab" width="1%">:</td>
						<td class="myTab" width="10%"><?php echo "<b>".number_format($Amount)."</b>"; ?> &nbsp;lembar/shares 
						<br>Terbilang/<i>amount in word:</i></td>
					<tr>
					<tr>
						<td class="myTab" width="10%"></td> 
						<td class="myTab" width="1%"></td>
						<td class="myTab" width="30%"><?php echo Terbilang($Amount) ?></td>
					</tr>
				</table>
				<br>
				<p align="justify">Formulir permohonan ini adalah sah dan benar untuk dijalankan sampai BCAS mendapatkan perintah perubahan atau pembatalan secara tertulis. Saya/Kami menyadari bahwa BCAS memiliki hak secara penuh untuk melaksanakan atau tidak melaksanakan instruksi ini berdasarakan jumlah Hak atas Efek yang Saya/Kami miliki dan/atau berdasarkan ketersediaan dana di Rekening Dana Nasabah.<br><br>
				<i>This request form is legitimate and true to executed until BCAS receives further written instruction to modify or revoke this instruction. I/We indemnify BCAS from any losses, claims, or obligation arises directly or inderectly from the execution of this request form. I/We realize that BCAS has a full right to execute or not execute this instruction based on the Right of Securities that I/We owned and/or based on the availability of fund in client's fund account (Rekening Dana Nasabah/RDN)</i>
				</p>
				<br>
				<table border="0" width="900" >
				    <tr>
				      <td class="myTab1" align="left" rowspan="2">Diajukan oleh/ <br><i>Proposed by</i></td>
				      <td class="myTab1" align="left" colspan="5">Internal BCAS / <i>For Internal BCAS</i></td>
				    </tr>
				    <tr>
				    	<td class="myTab1" align="left" width="20%">Diketahui oleh / <br><i>Acknowledged</i></td>

				    	<td class="myTab1" align="left" colspan="2" >Disetujui oleh / <br><i>Approved by</i></td>
						
				    	
				    	<td class="myTab1" align="left" width="20%">Diproses oleh / <br><i>Processed by</i></td>
				    </tr>
				    <tr>
				    	<td class="myTab1" align="left"><br><br><br><br><br><br></td>
				    	<td class="myTab1" align="left"><br><br><br><br><br><br></td>
				    	<td class="myTab1" align="left"><br><br><br><br><br><br></td>
				    	<td class="myTab1" align="left"><br><br><br><br><br><br></td>
				    	<td class="myTab1" align="left"><br><br><br><br><br><br></td>
				    </tr>
				    <tr>
				    	<td class="myTab1" align="center"><b>Nasabah / Client</b></td>
				    	<td class="myTab1" align="center"><b>Sales</b></td>
				    	<td class="myTab1" align="center" width="20%"><b>Risk Management</b></td>
				    	<td class="myTab1" align="center" width="20%"><b>Finance</b></td>
				    	<td class="myTab1" align="center"><b>Settlement</b></td>
				    </tr>
				    <tr>
				    	<td class="myTab1" align="left">Nama<i>/Name</i> :<br><br><?php echo "<b><div class='size'>".$Name."</div></b>"; ?></td>
				    	<td class="myTab1" align="left">Nama<i>/Name</i> :<br><br>.......................................</td>
				    	<td class="myTab1" align="left">Nama<i>/Name</i> :<br><br>.......................................</td>
				    	<td class="myTab1" align="left">Nama<i>/Name</i> :<br><br>.......................................</td>
				    	<td class="myTab1" align="left">Nama<i>/Name</i> :<br><br>.......................................</td>
				    </tr>
				</table>
				<?php } ?>
				</br>
				<p align="center"><input id="hide" type="button" value="PRINT" id="save" onClick="window.print()"></p>
			
		</div>
	</body>
</html>
