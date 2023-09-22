<?
$file_in = "findex_in.dat";
$code = "INDEXDJX:.DJI";

// get file
downloadGoogle($file_in, $code);

// read file
$html_in = readFileHtml($file_in);

// get value
$data = getValue($html_in);

$chkP = "3";
if ($data["chg"] > 0) $chkP = "2";
if ($data["chg"] < 0) $chkP = "4";
echo sprintf("%s,0,0,0,%s,%s,%s,%s", $code, $data["price"], $data["chg"], $data["chr"], $chkP);

function downloadGoogle($file_out, $code) {
	$ch = curl_init("http://www.google.com/finance?q=" . $code);
	$fp = fopen($file_out, "w");

	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}

function readFileHtml($file_in) {
	$size = filesize($file_in);
	//echo "size=" . $size;
	$fp = fopen($file_in, "r");
	$html_in = fread($fp, $size);
	fclose($fp);
	return $html_in;
}

function getValue($html_in) {
	$file_in = "findex_in.dat";
	$size = filesize($file_in);
	//echo "size=" . $size;
	$fp = fopen($file_in, "r");
	$html_in = fread($fp, $size);
	fclose($fp);

	$pos_pr = strpos($html_in, '"pr"', 0);
	//echo "pos_pr=" . $pos_pr;
	$pos_cl = strpos($html_in, ">", $pos_pr + 6);
	//echo "pos_cl=" . $pos_cl;
	$pos_op = strpos($html_in, "<", $pos_cl);
	//echo "pos_op=" . $pos_op;
	$len_val = $pos_op - $pos_cl;
	$price = substr($html_in, $pos_cl, $len_val );
	$price = str_replace(",", "", $price);
	$price = str_replace(">", "", $price);
	#echo "price=" . $price;

	// chg
	$pos_pr = strpos($html_in, ' id="', $pos_op + 6);
	//echo "pos_pr=" . $pos_pr;
	$pos_cl = strpos($html_in, ">", $pos_pr + 6);
	//echo "pos_cl=" . $pos_cl;
	$pos_op = strpos($html_in, "<", $pos_cl);
	//echo "pos_op=" . $pos_op;
	$len_val = $pos_op - $pos_cl;
	$chg = substr($html_in, $pos_cl, $len_val );
	$chg = str_replace(">", "", $chg);
	//echo "chg=" . $chg;

	// chr
	$pos_pr = strpos($html_in, ' id="', $pos_op + 6);
	//echo "pos_pr=" . $pos_pr;
	$pos_cl = strpos($html_in, ">", $pos_pr + 6);
	//echo "pos_cl=" . $pos_cl;
	$pos_op = strpos($html_in, "<", $pos_cl);
	//echo "pos_op=" . $pos_op;
	$len_val = $pos_op - $pos_cl;
	$chr = substr($html_in, $pos_cl, $len_val );
	$chr = str_replace("(", "", $chr);
	$chr = str_replace(")", "", $chr);
	$chr = str_replace(">", "", $chr);
	$chr = str_replace("-", "", $chr);
	$chr = str_replace("%", "", $chr);
	//echo "chr=" . $chr;
	$ret["price"] = $price;
	$ret["chg"] = $chg;
	$ret["chr"] = $chr;
	return $ret;
}

?>
