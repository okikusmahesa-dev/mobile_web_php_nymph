<?php 

function request_url($method)
{
global $TOKEN;
return "https://api.telegram.org/bot218067953:AAEJnpJVxme7p5EPvZawjiTJ3kemdTPA-eo/". $method;
}


$data = array(
'chat_id' => '281922927',
'text'  => '
asdh
',
'parse_mode' => 'HTML' 
);
// use key 'http' even if you send the request to https://...
$options = array(
'http' => array(
'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
'method'  => 'POST',
'content' => http_build_query($data),
),
);
$context  = stream_context_create($options);

$result = file_get_contents(request_url('sendMessage'), false, $context);
print_r($result);

?>
