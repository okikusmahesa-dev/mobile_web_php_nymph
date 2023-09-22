<?
$today = strftime("%Y%m%d");
$cwd = "/home/HTS_V1/bat/script/srvChker";
chdir($cwd);

// mkdir yyyymmdd
$logDir = sprintf("%s/%s", $cwd, $today);
mkdir($logDir);

//// cp log to yyyymmdd
//$cmd = sprintf("cp -f *.log* %s", $logDir);
//system($cmd);
// gzip yyyymmdd
//$cmd = sprintf("/bin/gzip -r %s", $logDir);
//system($cmd);


// rm yesterday log
//$cmd = sprintf("rm -f *.log* %s", $logDir);
//system($cmd);
/*
// cp log to yyyymmdd
$cmd = sprintf("cp -f atord*.log %s", $logDir);
system($cmd);
// cp log to yyyymmdd
$cmd = sprintf("cp -f bookord*.log %s", $logDir);
system($cmd);
*/

//rmOldLog();

//function rmOldLog() {
//	global $cwd;
//	for ($i = 0; $i < 20; $i ++) {
//		$old30 = strftime("%Y%m%d", time() - (60 * 60 * 24 * (30 + $i)));
//		$target = sprintf("%s/%s", $cwd, $old30);
//		//printf("remove dir:%s\n", $target);
//		$cmd = sprintf("rm -rf %s", $target);
//		//printf("$cmd\n");
//		//system($cmd);
//	}
//}

?>
