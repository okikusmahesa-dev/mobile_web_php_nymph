<?
	session_start();
?>
<html>
<?
//$loginId = $_SESSION["loginId"];

//putenv("LD_LIBRARY_PATH=/usr/lib:/usr/local/freetds/lib:/home/HTS_V1/lib_ap:/usr/local/lib:/usr/local/firstworks/lib");
/*
HTSDB_NAME=NYMH
HTSDB_PRE=NYMH.dbo
HTSDB_PW='OLTDB1!'
HTSDB_SVR=SQL-Mas

export SYBASE=/usr/local/freetds
export FREETDSCONF=/usr/local/etc/freetds.conf
*/

/*
echo "LD_LIBRARY_PATH=" . getenv("LD_LIBRARY_PATH") . "<br />";
echo "SYBASE=" . getenv("SYBASE") . "<br />";
echo "FREETDSCONF=" . getenv("FREETDSCONF") . "<br />";
*/
//echo "loginId=" . $loginId . "<br />";

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
	/*
	$sql = sql( "
			select top 1 trade_date from TB_BUSINESS_DAY where trade_date <= '<TRADE_DATE>' and trade_flag = 1 order by trade_date desc
		", $val);
	$res = odbc_exec($dbcon, $sql);
	*/

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
       WHERE id_msg = '15'
	";
	//echo "sql=$sql";
	// Get Result
	$res = odbc_exec($dbcon, $sql);
	
	// Get Data From Result
	while (odbc_fetch_row($res)){
		$entryDate	= odbc_result($res,"entry_date");
		$clientName = odbc_result($res,"ClientNo");
		$Name 		= odbc_result($res,"Name");
		$no_id		= odbc_result($res,"no_id");
		$aBalance	= odbc_result($res,"available_balance");
		$Bank		= odbc_result($res,"Bank");
		$aName		= odbc_result($res,"AccountName");
		$aNo		= odbc_result($res,"transfer_date");
		$tDate		= odbc_result($res,"transfer_account");
		$tAccount	= odbc_result($res,"transfer_date");
		$status		= odbc_result($res,"status");
		echo $clientName;
		echo $entryDate;
		echo $Name;
		echo $no_id;
		echo $aBalance;
		echo $Bank;
		echo $aName;
		echo $tDate;
		echo $tAccount;
		echo $status;

	}

	//print_r($data);
	//echo $res;
	//11111session_destroy();
	
?>
<script>
//window.location = "login.php";
</script>
</html>
