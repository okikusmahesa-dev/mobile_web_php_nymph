<?
function encStr($ss) {
    $curDt = strftime("%Y-%m-%d %H:%M:%S");
    $curHHMMSS = strftime("%M%S");
    $head = base64_encode($curDt);
    $head = substr($head, 0, 6);
    $encMsg = base64_encode($curHHMMSS . $ss);
    return $head . $encMsg;
}

function decStr($ss) {
    $encMsg = substr($ss, 6);
    $msg = base64_decode($encMsg);
    return substr($msg, 4, strlen($msg));
}
?>
