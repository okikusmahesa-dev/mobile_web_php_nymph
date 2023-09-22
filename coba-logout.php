<?php
	session_start();
/*
putenv("LD_LIBRARY_PATH=/usr/lib:/usr/local/freetds/lib:/home/HTS_V1/lib_ap:/usr/local/lib:/usr/local/firstworks/lib");
HTSDB_NAME=NYMH
HTSDB_PRE=NYMH.dbo
HTSDB_PW='OLTDB1!'
HTSDB_SVR=SQL-Mas
*/

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
	//$loginId = $_SESSION["userId"];
	$sql = "
		UPDATE TB_LOGIN_MASTER
		   SET last_ping_time = DATEADD(mi, -2, getdate())
		 WHERE login_id = 'Admin3'
		";
	//echo "sql=$sql";
	$res = odbc_exec($dbcon, $sql);

	session_destroy();
?>
<html>
<script>

</script>
</html>
