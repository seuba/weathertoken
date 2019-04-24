<?php
/********************************************************/
/*			Control the weather V1.0					*/
/*			By Albert Seuba	- 042319					*/
/********************************************************/

/* Consultamos el tiempo actual (ciudad=Barcelona), 
podemos variar la url pasando una variable desde inArguments y transformando country a su código */
// parseamos el json por cada user que entra en el journey
$json4 = file_get_contents('php://input');
$object = json_decode($json4, true);
$temps = $object['inArguments'][0]['message'];
$token = $object['inArguments'][1]['message2'];

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://dataservice.accuweather.com/currentconditions/v1/307297?apikey=".$token,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

	$tempsbarcelona = json_decode($response);
	$accuweather_temps = $tempsbarcelona[0]->{'WeatherText'};
};

	if ($temps == $accuweather_temps){
		$temps = 'true';      
	} 
	else{
		$temps = 'false';             
	}
	// cuando paramos el journey, a utilizar si queremos resetear una data extension guardando el valor anterior 
    if (isset($_GET['ready'])){
		$temps = '';       
	}
//devolvemos el outArgument al config.json para utilizar en la split activity (true | false)
echo '{"temps":"'.$temps.'"}';
echo $accuweather_temps;
echo $object['inArguments'][0]['message'];
?>
