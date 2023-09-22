<?
require_once("config.php");

$tr_in = "tr190000in.dat";
$size = filesize($tr_in);
$fp = fopen($tr_in, "r");
$data_in = fread($fp, $size);
fclose($fp);

$socket = socket_create ( AF_INET , SOCK_STREAM , SOL_TCP );
$ret = socket_connect($socket, $gHtsIp, $gHtsPort);
if ($ret == FALSE) {
	exit(0);
}
socket_write($socket, $data_in, $size);
//
$out_len = socket_read ($socket, 4);
$data_len = unpack("N", $out_len);
print_r($data_len);
//echo "len=" . $data_len[1];
$data_out = socket_read ($socket, $data_len[1]);
echo "data=" . $data_out;
socket_close($socket);

$pos = strpos($data_out, "BZh91AY&SY");
if ($pos > 0) {
		echo "\nn\ntag=" . $tag . ")";
	$data_out = bzdecompress(substr($data_out, $pos));
	echo $data_out;
}

$pkt = explode("|", $data_out);
print_r($pkt);
$price = $pkt[2];
$chg = $pkt[3] . $pkt[4];
$chr = $pkt[5];
$chkP = $pkt[3];

$chkP = "3";
if ($chg > 0) $chkP = "2";
if ($chg < 0) $chkP = "4";
echo sprintf("001,0,0,0,%s,%s,%s,%s", $price, $chg, $chr, $chkP);
?>
