<?php
	session_start();
/*
putenv("LD_LIBRARY_PATH=/usr/lib:/usr/local/freetds/lib:/home/HTS_V1/lib_ap:/usr/local/lib:/usr/local/firstworks/lib");
HTSDB_NAME=NYMH
HTSDB_PRE=NYMH.dbo
HTSDB_PW='OLTDB1!'
HTSDB_SVR=SQL-Mas
*/
	unset($_SESSION["pin"]);
    unset($_SESSION["pinState"]);
?>
<html>
<script>
alert('Pin Hilang');
window.location = "inPin.php";
</script>
</html>
