<?php

function request_url($method){
	return "https://api.telegram.org/bot397754770:AAHa2FAoRgVZMbDOMxjI1izd6j1mQuFq9SM/".$method;
}

function send_reply($chatid, $msgid, $text){
	$data = array(
		'chat_id' => $chatid,
		'parse_mode' => 'HTML',
		'text' => $text,
		'reply_to_message_id' => $msgid
	);

	$options = array(
		'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($data),
		),
	);
	$context = stream_context_create($options);
	$result = file_get_contents(request_url('sendMessage'), false, $context);
}

function process_message($message){
	$updateid = $message["update_id"];
	$message_data = $message["message"];
	$pesan = $message_data["text"];

	if (isset($message_data["text"])){
		$chatid = $message_data["chat"]["id"];
		$message_id = $message_data["message_id"];

		$command = explode(" ",$pesan);
		switch (strtolower(str_replace("/","",$command[0]))){
			case 'start':
				$response = "start";
				break;
			case 'summary':
				$response = summary();
				break;
			default:
				$response = "perintah tidak ditemukan";
				break;
		}
		send_reply($chatid, $message_id, $response);
	}
	return $updateid;
}

function summary(){
	$data = array(
		'tr' => '190000',
		'index' => 'COMPOSITE'
	);
	$options = array(
		'http' => array(
			'header'=> "Content-type: application/x-www-form-urlencoded\r\n",
			'method'=> "POST",
			'context'=> http_build_query($data),
		),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents("https://mobile.bcasekuritas.co.id/json/trproc.php", false, $context);
	$rslt = json_decode($result,true);
	$hsl = $rslt['out'][0];
	//return( implode("|",$hsl));
	return ("<b>SUMMARY</b>\n==================\nIndex\t\t\t\t\t\t\t: $hsl[index]\nChange\t\t\t: $hsl[chgP]$hsl[chg]\nchg Rate\t: $hsl[chgP]$hsl[chgR]\n==================\nPrev\t\t\t\t\t\t\t\t\t: $hsl[prevPrice]\nOpen\t\t\t\t\t\t\t\t: $hsl[open]\nHigh\t\t\t\t\t\t\t\t\t: $hsl[high]\nLow\t\t\t\t\t\t\t\t\t\t: $hsl[low]\n==================\nup\t\t\t\t\t\t\t\t\t\t\t\t\t\t: $hsl[up]\ndown\t\t\t\t\t\t\t\t\t: $hsl[down]\nunchange\t: $hsl[unchg]\nno trade\t\t\t\t: $hsl[noTrd]\n
	        ");
}

$entityBody = file_get_contents('php://input');
$message = json_decode($entityBody, true);
process_message($message);

?>

