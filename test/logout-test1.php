<?
	session_start();
?>
<html>
<?
$loginId = $_SESSION["loginId"];

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
echo "loginId=" . $loginId . "<br />";

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

	$loginId = $loginId;
	$sql = "
		UPDATE TB_LOGIN_MASTER
		SET last_ping_time = DATEADD(mi, -2, getdate())
		WHERE login_id = '$loginId'
	";
	echo "sql=$sql";
	//$res = odbc_exec($dbcon, $sql);

	//11111session_destroy();
?>
<script>
//window.location = "login.php";
</script>
</html>
