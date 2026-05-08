<?php
$contacts = $Phone;
$sms_text = urlencode($smstxt);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.pinnacle.in/index.php/sms/send/MHCHAI/'.$contacts.'/'.$sms_text.'/TXT?apikey=4a8797-801f87-21e9d2-5fc4c2-5745fa',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
//print_r($response);
curl_close($curl);
?>

