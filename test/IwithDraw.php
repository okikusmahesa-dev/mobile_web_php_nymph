<?php
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
	
	//Query
		$sql = "
			SELECT id_mesg,
				date,
				subject,
				message,
				sender,
				receiver,
				status,
				BACA

		   FROM [NYMH].[dbo].[TB_MAIL_BOX]
		   order by entry_date DESC
		";
		//echo "sql=$sql";
		// Get Result
		$res = odbc_exec($dbcon, $sql);
		echo '<table with="80%" border="0">';
		echo '<thead with="80%">';
		echo '<th>Detail</th>';
		echo '<th>Date</th>';
		echo '<th>Subject</th>';
		echo '<th>Sender</th>';
		echo '</thead>';

		echo '<tbody with="80%">';

		// Get Data From Result
		while (odbc_fetch_row($res)){

			echo '<tr>';
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
		echo '</tbody>';
		echo '</table>';
	
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
	<link rel="shortcut icon" href="../img/bca.ico" />
	<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.pack.css" />
	<link rel="stylesheet" href="../css/jquery.mobile-1.2.0.min.css" />
	<!--<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>-->
	<!--<script src="js/jquery-ui.js"></script>-->
	<!--<script type="text/javascript" src="js/flexigrid.pack.js"></script>-->
	<!--<script type="text/javascript" src="js/jquery.mobile-1.2.0.min.js"></script>-->
	<script src="../js/common.js"></script>
	<script src="../js/sprintf.js"></script>
	<script src="../js/date.format.js"></script>
	<!--<script type="text/javascript" src="timerPing.js?20151015b"></script>-->
	<style type="text/css">
	<? include "../css/icon.css" ?>
	<? include "../css/marketSummary.css" ?>
	</style>
	<!--
	<script type="text/javascript">
	</script>
	-->
</head> 
<body style="backgound:#ffffff;">
<div data-role="page" id="home" data-cache="never">
	<input type="text" id="idTxt"><input type="submit" id="OK">
	<div>
	<h1 style="margin-top: 5px;margin-bottom: 5px; text-align:center;">List Permohonan Penarikan Dana</h1>
	</br>
	</div>
		<table with="80%" border="0">
			<thead with="80%">
				<th>Detail</th>
				<th>Date</th>
				<th>Subject</th>
				<th>Sender</th>
			</thead>

			<tbody with="80%">
				<tr>
					<th>view</th>
					<th>20151104</th>
					<th>Test</th>
					<th>Fak Youyyyjkdajdjaldjaljdajdjaldjlajldjasjdalsjdlajsldjalsdjlajdlajldjasjdlasjdlasjldjaljdlajdlasjdlajldjal</th>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>
