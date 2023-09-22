<?
require_once("config.php");
require_once("json/trmap.php");
/*
$_POST["tr"] = "100000";
$_POST["code"] = "ENRG";
$_POST["board"] = "RG";
*/

session_start();
if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] != 1) {
	exit(0);
}

//
$_POST["time"] = "00:00:00";
$_POST["id"] = $_SESSION["loginId"];
//

//$trNo = $_POST["tr"];
$trNo = "000101";
$trFile = "json/" . $trmap[$trNo][0];

$trIn = getTrIn($trFile, $_POST);
//printf("input=%s\n", $trIn);
$trOut = getTrOut($trNo, $trIn);
$res = getResult($trFile, $trOut);
//print_r($res);
//$res["status"] = 1;
$jsOut = json_encode($res);
print_r($jsOut);
//print($jsOut);

function getTrIn($ioFile, $inMap) {
	$arrCnt = 0;
	$inData = "";
	$isInput = FALSE;
	$isOutput = FALSE;
	$isRecord = FALSE;
	$arXml = readXml($ioFile);
	foreach($arXml as $key => $arNode) {
		switch ($arNode["type"]) {
		case "open" :
			//printf("open node>");
			//print_r($arNode);
			if ($arNode["level"] == 2) {
				if ($arNode["tag"] == "input") {
					$isInput = TRUE;
				}
				if ($arNode["tag"] == "output") {
					$isOutput = FALSE;
				}
			}
			if ($arNode["level"] == 3) {
				if ($arNode["tag"] == "record") {
					$isRecord = TRUE;
					$arrCnt = 1;
				}
			}
			break;
		case "complete" :
			if ($isInput == TRUE) {
				if ($isRecord == TRUE) {
					// input
					$attr = $arNode["attributes"];
					//printf("input node>" . $attr["bindname"]);
					//printf("\n");
					//print_r($arNode);
					$key = $attr["bindname"];
					$inData .= $inMap[$key] . "|";
				}
			}
			break;
		case "close" :
			if ($arNode["level"] == 2) {
				if ($arNode["tag"] == "input") {
					$isInput = FALSE;
				}
				if ($arNode["tag"] == "output") {
					$isOutput = FALSE;
				}
			}
			if ($arNode["level"] == 3) {
				if ($arNode["tag"] == "record") {
					$isRecord = FALSE;
				}
			}
			break;
		}
	}
	if (strlen($inData) > 0) {
		$ret = sprintf("%06d|%s", $arrCnt, $inData);
	}
	else {
		$ret = "00000|";
	}
	return $ret;
}

function readXml($xmlFile) {
	$xmlData = file_get_contents($xmlFile);
	$rXml = xml_parser_create();
	$arXml = array();
	xml_parser_set_option($rXml, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($rXml, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($rXml, $xmlData, $arXml);
	xml_parser_free($rXml);
	return $arXml;
}

function getTrOut($trNo, $trIn) {
	global $gHtsIp, $gHtsPort;
	$serverIp = $gHtsIp;
	$serverPort = $gHtsPort;
	$socket = socket_create ( AF_INET , SOCK_STREAM , SOL_TCP );
	$ret = socket_connect($socket, $serverIp, $serverPort);
	if ($ret == FALSE) {
		exit(0);
	}
	// pkt = length(4) + flag(2) + clikey(8) + route(8) + trno(6) + service(8)
	$pktIn = pack("cc", 0xc0, 0x34)
	       . pack("NN", 0x1234, 0x0000)
	       . sprintf("php1") . pack("N", 0x0000)
	       . $trNo
	       . pack("NN", 0x0000, 0x0000)
	       . $trIn;
	$pktIn = pack("N", strlen($pktIn))
	       . $pktIn;
	socket_write($socket, $pktIn, strlen($pktIn));
	//
	while (1) {
		if (false == ($out_len = socket_read($socket, 4))) {
            error_log("socket_read():1 failed: reason: "
                . socket_strerror(socket_last_error($socket))) ;
            error_log("socket_read():1 " . $out_len );
            break;
        }
		$data_len = unpack("N", $out_len);
		//print_r($data_len);
		//echo "len=" . $data_len[1];
 //echo "len=" . $data_len[1];
        error_log("trping len=" . $data_len[1]);
        $data_out = "";
        $data_buf = "";
        while ($data_len[1] > 0) {
            if (false == ($data_buf = socket_read ($socket, $data_len[1]))) {
                error_log("socket_read():2 failed: reason: "
                    . socket_strerror(socket_last_error($socket))) ;
                error_log("socket_read():2 " . $data_buf);
                $log2 = sprintf("socket_read():2 read length = %d from %d", strlen($data_buf), $data_len[1]);
                error_log($log_2);
            }
            $data_out = $data_out . $data_buf;
            $data_len[1] = $data_len[1] - strlen($data_buf);
        }
		//echo "data=" . $data_out;
		$pos = strpos($data_out, "BZh91AY&SY");
		if ($pos > 0) {
			$data_out = bzdecompress(substr($data_out, $pos));
			//echo $data_out;
		}
		else {
			$data_out = substr($data_out, 10);
			//echo "data1=" . $data_out;
		}
		if (strpos($data_out, "QUOT") == FALSE) {
			$pkt = explode("|", $data_out);
			break;
		}
	}
	socket_close($socket);
	//
	//getTrIn($trFile, $_POST)
	//
	return $pkt;
}

function getResult($ioFile, $trOut) {
	// parse outStr
	$outArr = $trOut;
	$outIdx = 0;
	// parse xml
	$arrCnt = 0;
	$ret = array();
	//
	$isInput = FALSE;
	$isOutput = FALSE;
	$isRecord = FALSE;
	$arXml = readXml($ioFile);
	foreach($arXml as $key => $arNode) {
		switch ($arNode["type"]) {
		case "open" :
			//printf("open node>");
			//print_r($arNode);
			if ($arNode["level"] == 2) {
				if ($arNode["tag"] == "input") {
					$isInput = TRUE;
				}
				if ($arNode["tag"] == "output") {
					$isOutput = TRUE;
				}
			}
			if ($arNode["level"] == 3) {
				if ($arNode["tag"] == "record") {
					$isRecord = TRUE;
					if ($isOutput == TRUE) {
						$attr = $arNode["attributes"];
						$outName = $attr["name"];
						$fldArr = array();
						$fldIdx = 0;
						//
						$arrCnt = intval($outArr[$outIdx]);
						$outIdx += 1;
					}
				}
			}
			break;
		case "complete" :
			if ($isOutput == TRUE) {
				if ($isRecord == TRUE) {
					if ($arNode["tag"] == "field") {
						// output
						$attr = $arNode["attributes"];
						//printf("output node>" . $attr["bindname"]);
						//printf("\n");
						//print_r($arNode);
						// except const field
						if ($attr["type"] < 10) {
							$fldArr[$fldIdx] = $attr["bindname"];
							$fldIdx += 1;
						}
					}
				}
			}
			break;
		case "close" :
			if ($arNode["level"] == 2) {
				if ($arNode["tag"] == "input") {
					$isInput = FALSE;
				}
				if ($arNode["tag"] == "output") {
					$isOutput = FALSE;
				}
			}
			if ($arNode["level"] == 3) {
				if ($arNode["tag"] == "record") {
					$isRecord = FALSE;
					// init record array
					if ($isOutput == TRUE) {
						//print_r($fldArr);
						//printf("arr cnt=%d\n", $arrCnt);
						$ret[$outName] = array();
						$fldCnt = count($fldArr);
						for ($i = 0; $i < $arrCnt; $i ++) {
							$ret[$outName][$i] = array();
							for ($j = 0; $j < $fldCnt; $j ++) {
								// init field array
								$ret[$outName][$i][$fldArr[$j]] = $outArr[$outIdx];
								$outIdx += 1;
							}
						}
					}
					$arrCnt = 0;
				}
			}
			break;
		}
	}
	return $ret;
}

?>
