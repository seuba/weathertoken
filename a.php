<?php

require  '/app/vendor/autoload.php';

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
 

?>
