<?
function remove_inject($str) {
   $ret = preg_replace("/'/", "\"", $str);
   $ret = preg_replace("/;/", "", $ret);
   $ret = preg_replace("/ OR /i", "\"", $ret);
   return $ret;
}

function check_inject() {
	foreach($_GET as $k => $v) {
		//error_log ("GET ". $k . "=" . "(" . $v . ")");
		$_GET[$k] = remove_inject($v);
	}
	foreach($_POST as $k => $v) {
		//error_log ("POST ". $k . "=" . "(" . $v . ")");
		$_POST[$k] = remove_inject($v);
	}
}

check_inject();
?>
