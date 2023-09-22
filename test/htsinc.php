<?
function getHtsEnv() {
    $env["HTSDB_SVR"] = getenv("HTSDB_SVR");		// SQL-Mas(freetds alias)
    $env["HTSDB_ID"] = getenv("HTSDB_ID");
    $env["HTSDB_PW"] = getenv("HTSDB_PW");
    $env["HTSDB_NAME"] = getenv("HTSDB_NAME");		// NYMH
    $env["HTSDB_PRE"] = getenv("HTSDB_PRE");		// NYMH.dbo
    $env["FODB_NAME"] = getenv("FODB_NAME");		// FO_PRD.dbo
    $env["FODBH_NAME"] = getenv("FODBH_NAME");		// FO_PRD.dbo
    //print_r($env);
    return $env;
}

function getDB() {
    global  $env;
	print_r($env);
    $dbconn = odbc_connect($env["HTSDB_SVR"], $env["HTSDB_ID"], $env["HTSDB_PW"]);
    if ($dbconn == FALSE) {
        printf("error:%s\n", odbc_errormsg());
        exit(1);
    }
	odbc_exec($dbconn, "use " . $env["HTSDB_NAME"]);
    return $dbconn;
}

function sql($sql, $myEnv = FALSE) {
    global $env;
    foreach($env as $key => $val) {
        $f = sprintf("<%s>", $key);
        $sql = str_replace($f, $env[$key], $sql);
    }
    if ($myEnv != FALSE) {
        foreach($myEnv as $key => $val) {
            $f = sprintf("<%s>", $key);
            $sql = str_replace($f, $myEnv[$key], $sql);
        }
    }
    return $sql;
}

function getWorkCurDay($dbcon) {
	$now_YYYYMMDD = strftime("%Y%m%d");
	$val["TRADE_DATE"] = $now_YYYYMMDD;
	$sql = sql( "
		select top 1 trade_date from TB_BUSINESS_DAY where trade_date <= '<TRADE_DATE>' and trade_flag = 1 order by trade_date desc
	", $val);
	$res = odbc_exec($dbcon, $sql);
	while( false !== ( $row = @odbc_fetch_array( $res ) ) ) {
		$ret = $row["trade_date"];
		break;
	}
	return $ret;
}

function getWorkYDay($dbcon) {
	$now_YYYYMMDD = strftime("%Y%m%d");
	$val["TRADE_DATE"] = $now_YYYYMMDD;
	$sql = sql( "
		select top 2 trade_date from TB_BUSINESS_DAY where trade_date <= '<TRADE_DATE>' and trade_flag = 1 order by trade_date desc
	", $val);
	$res = odbc_exec($dbcon, $sql);
	// skip first one
	$row = @odbc_fetch_array( $res );
	// get 2th data
	while( false !== ( $row = @odbc_fetch_array( $res ) ) ) {
		$ret = $row["trade_date"];
		break;
	}
	return $ret;
}
?>
