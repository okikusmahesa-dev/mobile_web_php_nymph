<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

include "config-db.php";

//Connection to webservice
function getDB() {
    global  $env;
    global  $gDBName, $gDBUser, $gDBPass;
    //error_log("dbconn=" . $gDBName);
    //print_r($env);
    $dbconn = odbc_connect($gDBName, $gDBUser, $gDBPass);
    //$dbconn = odbc_connect("SQL-Mas", "sa", "OLTDB1!");
    if ($dbconn == FALSE) {
        printf("error:%s\n", odbc_errormsg());
        exit(1);
    }
    odbc_exec($dbconn, "use NYMH");
    return $dbconn;
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
					,A.stock_code as stock_code
					,A.login_id as login_id
					,A.user_id as user_id
					,A.user_name as user_name
					,A.price as price
					,convert(varchar(15),cast(A.ex_date as date),103) as date_ex
					,convert(varchar(15),cast(A.apply_date as date),23) as date_app
					,A.qty as qty
					,A.amount as amount
					,A.status as status 
					,A.sas_status as sas_status
					,A.sas_upddate as sas_update
					,A.sas_note as sas_note
					,(select C.SalesNo from OLT_Client C WHERE C.ClientNo = A.user_id) salesNo
                    ,A.entry_date as date_ent
			FROM TB_APPL_RIGHT A
			WHERE seq = '$seq'
			";
			
			//error_log("sql=" . $sql);
			// Get Result
			$res = odbc_exec($dbcon, $sql);

			// Get Data From Result
			while (odbc_fetch_row($res)){
				// $entryDate  = odbc_result($res,"date_ent");
				$LoginID    = odbc_result($res,"login_id");
				$Name       = odbc_result($res,"user_name");
				$UserID     = odbc_result($res,"user_id");
				$Qty        = odbc_result($res,"qty");
				$Code       = odbc_result($res,"stock_code");
				$ExDate     = odbc_result($res,"date_ex");
				$Price      = odbc_result($res,"price");
				$Amount     = odbc_result($res,"amount");
				$ApplyDate  = odbc_result($res,"date_app");
				$Sales      = odbc_result($res,"salesNo");
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
			  border: 1px outset black;
			  
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
			<div class="myDiv"><h2 style="margin-top: 5px;margin-bottom: 5px; text-align:left;">PT. BCA SEKURITAS</h2><br></div>
				<div class="myDiv"><h2>FORMULIR PERMOHONAN <br>MELAKUKAN EXERCISE HAK ATAS EFEK</h2></div>
				
				<table border="0" width="500">
					<tr>
						<td colspan="4">Yang bertanda tangan dibawah ini, saya :</td>
					</tr>
					<tr>
						<td width="10%">Nama Nasabah</td> 
						<td width="1%">:</td>
						<td width="25%"><?php echo "<b>".$Name."</b>"; ?></td>
					</tr>
					<tr>
						<td width="10%">Kode Nasabah</td> 
						<td width="1%">:</td>
						<td width="25%"><?php echo "<b>".$UserID."</b>"; ?></td>
					</tr>
				</table>
				<p>Dengan ini mengajukan permohonan untuk dapat dilakukan exercise terhadap hak atas efek saya sebagai berikut :<br>
				<table border="0" width="500">
					<tr>
						<td width="10%">Kode Saham</td> 
						<td width="1%">:</td>
						<td width="25%"><?php echo "<b>".$Code."</b>"; ?></td>
					</tr>
					<tr>
						<td width="10%">Jumlah HMETD</td> 
						<td width="1%">:</td>
						<td width="25%"><?php echo "<b>".$Qty."</b>"; ?></td>
					</tr>
					<tr>
						<td width="10%">Jumlah Rupiah </td> 
						<td width="1%">:</td>
						<td width="25%"><?php echo "<b><u>".$Qty." lbr / Rp. ".$Price." = Rp. ".number_format($Amount)."</u></b>"; ?></td>
					</tr>
					<tr>
						<td width="10%">Tanggal Exercise</td> 
						<td width="1%">:</td>
						<td width="25%"><?php echo "<b>".$ExDate."</b>"; ?> (tanggal, bulan, tahun)</td>
					</tr>
						
					</tr>
				</table>
				<br><br><br><br>
				<p>Demikian permohonan ini kami sampaikan, mohon untuk dapat dipergunakan sebagaimana mestinya
					dalam proses pelaksanaan Exercise atas Efek milik saya.<br><br><br>
				</p>
			
				<br><br><br><br>
				<table border="0" width="900" >
				    <tr>
				      <th class="myTab">Diajukan oleh, </th>
				      <th class="myTab" align="center" width="15%">Diketahui oleh,
				      	<!-- <b><?php echo $Sales; ?><br>Sales Equity</b> --></th>
				      <th class="myTab" align="center"  class="myTab" colspan="10"><b>Disetujui oleh,</b></th>
				  	</tr>
				    <tr>
				      <td class="myTab"><br><br><br><br><br><br><br><br><br></td>
				      <td class="myTab"><br><br></td>
				      <td class="myTab"><br><br></td>
				      <td class="myTab"><br><br></td>
				      <td class="myTab"><br><br></td>
				    </tr>
				    <tr>
				      <td class="myTab" align="center" width="30%"><?php echo "<b>" .$LoginID. "</b>"; ?><br><?php echo "<b>" .$Name. "</b>"; ?></td>
				      <td class="myTab" align="center"><b><?php echo $Sales; ?><br>Sales Equity</b></td>
				      <td class="myTab" align="center" colspan="1" width="20%"><b>Risk Management </b></td>
				      <!-- <td class="myTab" align="center"><b>Risk Management </b></td> -->
				      <td class="myTab" align="center" width="20%"><b>Settlement</b></td>
				      <td class="myTab" align="center" colspan="5" width="20%"><b>Finance</b></td>
				  	</tr>
				  	<tr>
				      <td class="myTab" colspan="15">Settlement<br>Tanggal : <?php echo $ApplyDate; ?> </td>
					</tr>
				</table>
				<?php } ?>
				</br>
				<p align="center"><input id="hide" type="button" value="PRINT" id="save" onClick="window.print()"></p>
		</div>
	</body>
</html>
