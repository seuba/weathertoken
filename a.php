<?php

require __DIR__ . '/vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;



   $sid    = "ACd5eb13d4300bc2cff4ab7ed0591754a9"; 
$token  = "a5b8187af48f86fec4134c98bd7183c6"; 
$twilio = new Client($sid, $token); 
$journey = 'hola';
$telefono = '692489551';
$keyword = 'hola';

$message = $twilio->messages 
                  ->create("whatsapp:+34".$telefono, // to 
                           array( 
                               "from" => "whatsapp:+14155238886",       
                               "body" => $journey,
							   "statusCallback" => "https://pub.s10.exacttarget.com/ko3e4hvfb2p?phone=".$telefono."&keyword".$keyword
                           )
                  ); 
 
$status = $message->status;
$sid = $message->sid;
$from = $message->from;
$from = str_replace("whatsapp:+","",$from);
$to = $message->to;
$to = str_replace("whatsapp:+","",$to);
$direction = 'outbound';
$mensaje = $message->body;
$mensaje =  rawurlencode($mensaje);
$ur = 'https://pub.s10.exacttarget.com/0qrbgkddaqj?MessageId='.$sid.'&Phone='.$from.'&Message='.$mensaje.'&From='.$from.'&To='.$to.'&Direction='.$direction.'&Date=none&Status='.$status.'&keyword='.$keyword;

$ch = curl_init($ur);
$http_headers = array(
'User-Agent: Junk', // Any User-Agent will do here
);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

return response( ['status' => $status]);
