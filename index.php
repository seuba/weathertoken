<html>
  <head></head>
  <body>
    <script src="https://albertseuba.herokuapp.com/blocksdk.js"></script>
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.js"></script> 
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	  <script type="text/javascript">
			(function() {
				var config = {
					baseUrl: ''
				};
				var albert = [
					'blocksdk',
					'codi'
				];
				require(config, albert);
			})();
		</script>
<?php
/********************************************************/
/*			Control the weather V1.0					*/
/*			By Albert Seuba	- 042319					*/
/********************************************************/
/* Consultamos el tiempo actual (ciudad=Barcelona), 
podemos variar la url pasando una variable desde inArguments y transformando country a su código */
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://dataservice.accuweather.com/currentconditions/v1/307297?apikey=aE0Mu6wczdfgTIZacsEksP0KBDAUYZjr",
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
	echo $accuweather_temps;
};
?>
  </body>
</html>
