<?
require_once("../config.php");
require_once("trmap.php");

/*
$_POST["tr"] = "100000";
$_POST["code"] = "ENRG";
$_POST["board"] = "RG";
*/
/*
$_POST["tr"] = "120001";
$_POST["mktNo"] = "0";
$_POST["cnt"] = "10";
*/

session_start();

// new API
if (isset($_POST["phash"]) == TRUE) {
    $pHashOrg = base64_decode($_POST["phash"]);
    //error_log("phashorg>" . $pHashOrg);
    //
    $hashIn = Array();
    parse_str($pHashOrg, $hashIn);
    // overwrite $_POST value
    foreach($hashIn as $key => $val) {
        $_POST[$key] = $val;
        //error_log("force>> $key = $val");
    }
}

$trNo = $_POST["tr"];
$trFile  = $trmap[$trNo][0];
$service = $trmap[$trNo][1];



$lastTime = time();

error_log("trFile=" . $trFile);
/*
if ( $trNo != "120001" && $trNo != "100000" && $trNo != "100001" ) {
	$curTime = time();
	if ( isset($_SESSION["lastTime"]) ) {
		$lastTime = $_SESSION["lastTime"];
	}
	
	$diffTime = ($curTime - $lastTime) / 60;

	if ( $diffTime > 2.0 ) {
		$_SESSION["pin"] = "";
		$_SESSION["pinState"] = 0;
	}
	else {
		$_SESSION["lastTime"] = time();
	}
}
else {
	//$_SESSION["lastTime"] = time();
}
*/

// check TR
// check TR xml
if ($trFile == "") {
    error_log("Unknown TR=" . $trNo);
    $trIn = "999997|";
} else {
    $trIn = getTrIn($trFile, $_POST);
    //printf("input=%s tr=%s\n", $trIn, $trNo);
    error_log("input=" . $trIn . " tr=" . $trNo);
}

// 20210818     check error code
// 999999|      invalid login id    login
// 999998|      invalid client id   pin
if ($trIn == "999999|") {
    $res = setError(0, $trIn, "Login First");
} else if ($trIn == "999998|") {
    $res = setError(0, $trIn, "Input PIN First");
} else if ($trIn == "999997|") {
    $res = setError(0, $trIn, "Unknown TR Code : " . $trNo);
} else if ($trIn == "999996|") {
    $res = setError(0, $trIn, "No such TR IO : " . $trNo . ", " . $trFile);
} else {
    $trOut = getTrOut($trNo, $trIn, $service);
//error_log("tr=" . $trNo . " output=" . $trOut);
//print_r($trOut);
    $res = getResult($trFile, $trOut);
//print_r($res);
    $res["status"] = 1;
}
$jsOut = json_encode($res);

//print_r($jsOut);
print($jsOut);

function setError($errstatus, $errcode, $errmesg) {
    $err["status"] = $errstatus;
    $err["errcode"] = $errcode;
    $err["errmesg"] = $errmesg;

    return $err;
}



function getTrIn($ioFile, $inMap) {
// return 
//      normal : 000001|
//      error  : 999999|    invalid login id, please login
//             : 999998|    invalid client id, please input pin
//             : 999996|    No such io xml 
    global $trmap, $trNo;
	$arrCnt = 0;
	$inData = "";
	$isInput  = FALSE;
	$isOutput = FALSE;
	$isRecord = FALSE;
	$arXml = readXml($ioFile);
    if ($arXml == "") {
        return "999996|";
    }
    // new API
    error_log("SESSION=" . print_r($_SESSION, TRUE));
    //error_log("_SESSION loginId=" . $_SESSION["loginId"]);
    //error_log("_SESSION clientId=" . $_SESSION["clientId"]);
    //error_log("_SESSION isLogin=" . $_SESSION["isLogin"]);
    //error_log("_SESSION pin=" . $_SESSION["pin"]);
    //error_log("_SESSION pinState=" . $_SESSION["pinState"]);
    if (isset($trmap[$trNo]["ssval"]) == TRUE) {
        foreach($trmap[$trNo]["ssval"] as $key => $val) {
            error_log("key[" . $key . "]=[" . $val . "]");
            if ($key == "pinCheck") {
                error_log("pinCheck " . $key . "," . $val);
                if ($val == 1) {
                    if ($_SESSION["pinState"] != 1) {
	                    return "999998|";
                    }
                }
            }
            if ($val == "_S_loginId") {
                error_log("_S_loginId=" . $_SESSION["loginId"]);
                if (empty($inMap[$key])) {   // if loginId == ""
                    $inMap[$key] = strtoupper($_SESSION["loginId"]);
                }
                if (strtoupper($inMap[$key]) !== (strtoupper($_SESSION["loginId"]))) {
                    //print("Session is invalid. Please login.");
                    error_log("loginId Session is different. _S_loginId" . $ioFile . ":" . $_SESSION["loginId"] . ":" . $inMap[$key] );
                    $inMap[$key] = "00000000";
                    //exit(0);
	                return "999999|";
                } else {
                    $inMap[$key] = $_SESSION["loginId"];
                }
            } else if ($val == "_S_clientId") {
                //error_log("_S_clientId=" . $_SESSION["clientId"]);
                //error_log("inMap[" . $key . "]=" . $inMap[$key] );
                if ($_SESSION["isLogin"] != 1) {
                    return "999999|";
                }
                if (empty($inMap[$key])) {   // if loginId == ""
                    $inMap[$key] = strtoupper($_SESSION["clientId"]);
                }
                if (strtoupper($inMap[$key]) !== (strtoupper($_SESSION["clientId"]))) {
                    //print("Session clientID is invalid. Please login. ");
                    error_log("clientId Session is different. _S_clientId" . $ioFile . ":". $_SESSION["clientId"] . ":" . $inMap[$key] );
                    $inMap[$key] = "00000000";
                    //exit(0);
	                return "999998|";
                } else {
                    $inMap[$key] = $_SESSION["clientId"];
                }
            } else if ($val == "_S_pin") {
                $inMap[$key] = $_SESSION["pin"];
            } else {
                $inMap[$key] = $val;
            }
        }
    }
    /*
    foreach($inMap as $key => $val) {
        if ($val == "_S_pin") {
            $inMap[$key] = $_SESSION["pin"];
        }
        if ($val == "_S_loginId") {
            //error_log("_S_loginId=" . $_SESSION["loginId"]);
            //error_log("inMap[" . $key . "]=" . $inMap[$key] );
            $inMap[$key] = $_SESSION["loginId"];
        }
        if ($val == "_S_clientId") {
            $inMap[$key] = $_SESSION["clientId"];
        }
    }
    */
    //
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
    if ($xmlData == "") {
        return "";
    }
	$rXml = xml_parser_create();
	$arXml = array();
	xml_parser_set_option($rXml, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($rXml, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($rXml, $xmlData, $arXml);
	xml_parser_free($rXml);
	return $arXml;
}

function getTrOut($trNo, $trIn, $service) {

	global $gHtsIp, $gHtsPort;
	$serverIp = $gHtsIp;
	$serverPort = $gHtsPort;
	$socket = socket_create ( AF_INET , SOCK_STREAM , SOL_TCP );
	$ret = socket_connect($socket, $serverIp, $serverPort);
	if ($ret == FALSE) {
		exit(0);
	}
	// pkt = length(4) + flag(2) + clikey(8) + route(8) + trno(6) + servier(8)
    error_log("service=" . $service);

	if (strlen($service) > 0) {
		$serviceIn = pack("a8", $service);
	}
	else {
	    $serviceIn = pack("NN", 0x0000, 0x0000);
	}
	$pktIn = pack("cc", 0xc0, 0x34)
	       . pack("NN", 0x1234, 0x0000)
	       . sprintf("php1") . pack("N", 0x0000)
	       . $trNo
	       . $serviceIn
	       . $trIn;
	$pktIn = pack("N", strlen($pktIn))
	       . $pktIn;
	socket_write($socket, $pktIn, strlen($pktIn));
	//
	while (1) {
		if (false == ($out_len = socket_read($socket, 4))) {
            error_log("socket_read():1-1 failed: reason: "
                . socket_strerror(socket_last_error($socket))) ;
            error_log("socket_read():1-2 " . $out_len);
            break;
        }
		$data_len = unpack("N", $out_len);
		//print_r($data_len);
		//echo "len=" . $data_len[1];
		error_log("len=" . $data_len[1]);
		if ($data_len[1] > 20000 * 4) {
			error_log("invalid data_len : " . $data_len[1]);
			break;
		}	
        $data_out = "";
        $data_buf = "";

        //$data_len[1] = 10000;
        while ($data_len[1] > 0) {
            if (false == ($data_buf = socket_read ($socket, $data_len[1]))) {
                error_log("socket_read():2 failed: reason: "
                    . socket_strerror(socket_last_error($socket))) ;
                error_log("socket_read():2 " . $data_buf);
                $log2 = sprintf("socket_read():2 read length = %d from %d", strlen($data_buf), $data_len[1]);
                error_log($log2);
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
			error_log("data1=" . $data_out);
			$data_out = substr($data_out, 10);
			error_log("data2=" . $data_out);
		}
		if (strpos($data_out, "QUOT") == FALSE) {
			$pkt = explode("|", $data_out);
			break;
		}
	}
	socket_close($socket);
	
	return $pkt;
}

function getResult($ioFile, $trOut) {
    global $trNo;
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

    // Logout reset session variables
    if ( $trNo == "710010" ) {
        error_log("logout:" . $_SESSION["loginId"] . "," . $_SESSION["clientId"]);
        // Logout
        $_SESSION["pin"] = "";
        $_SESSION["pinState"] = 0;
        $_SESSION["loginId"] = "";
        $_SESSION["clientId"] = "";
        $_SESSION["isLogin"] = 0;
    } 
    // Login 

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

						//if ($arrCnt > 20170000) {
						if ($arrCnt > 20000) {
							$log1 = sprintf("Fatal2 error: trFile=%s. arrcnt=%d. fldcnt=%d. outIdx=%d", $ioFile, $arrCnt, $fldCnt, $outIdx);
							$log2 = sprintf("Fatal2 error: trOut(outArr)=%s", $trOut);
							error_log($log1);
							error_log($log2);
							error_log("trOut(outArr)=" . print_r($outArr, TRUE));
                            break; 
						}
						//-------------------------
						for ($i = 0; $i < $arrCnt; $i ++) {
							$ret[$outName][$i] = array();
							for ($j = 0; $j < $fldCnt; $j ++) {
								// init field array
                                //
								$ret[$outName][$i][$fldArr[$j]] = $outArr[$outIdx];
								$outIdx += 1;
							}
						}
						$fldCnt = 0;
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
