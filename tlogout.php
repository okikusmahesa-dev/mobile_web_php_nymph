<?php
	session_start();
/*
putenv("LD_LIBRARY_PATH=/usr/lib:/usr/local/freetds/lib:/home/HTS_V1/lib_ap:/usr/local/lib:/usr/local/firstworks/lib");
HTSDB_NAME=NYMH
HTSDB_PRE=NYMH.dbo
HTSDB_PW='OLTDB1!'
HTSDB_SVR=SQL-Mas
*/

/*
$handle = popen("cat /home/HTS_V1/bin/global.cfg | grep 'HTSDB_ID' | grep -v '^#' | cut -d'=' -f 2", 'r');
$dbID = fread($handle, 128);
pclose($handle);

$handle = popen("cat /home/HTS_V1/bin/global.cfg | grep 'HTSDB_PW' | grep -v '^#' | cut -d'=' -f 2", 'r');
$dbPW = fread($handle, 128);
pclose($handle);

$dbID = str_replace("\r", "", $dbID);
$dbPW = str_replace("\r", "", $dbPW);
$dbID = str_replace("\n", "", $dbID);
$dbPW = str_replace("\n", "", $dbPW);
*/

function getDB() {
    global $dbID, $dbPW;
    error_log("db=" . $dbID . ", " . $dbPW);
	//$dbconn = odbc_connect("SQL-Mas", $dbID, $dbPW);
	$dbconn = odbc_connect("SQL-Mas", "sa", "OLTDEV1!");
	if ($dbconn == FALSE) {
		printf("error:%s\n", odbc_errormsg());
		exit(1);
	}
	odbc_exec($dbconn, "use NYMH");
	return $dbconn;
}

	$dbcon = getDB();
	$loginId = $_SESSION["loginId"];
	$sql = "
		UPDATE TB_LOGIN_MASTER
		   SET last_ping_time = DATEADD(mi, -2, getdate())
		 WHERE login_id = '$loginId'
		";
    error_log("tlogout=". $loginId);
	$res = odbc_exec($dbcon, $sql);

	session_destroy();
?>
<html>
<script>
window.location = "login.php";
</script>
</html>
