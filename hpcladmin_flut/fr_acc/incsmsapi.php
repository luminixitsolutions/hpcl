<?php
$api_key = '35FA2ABB1E6201';
$contacts = $Phone;
$from = 'SPTSMS';

$sms_text = urlencode($smstxt);

$api_url = "https://api.pinnacle.in/index.php/sms/send/MHCHAI/".$contacts."/".$sms_text."/TXT?apikey=4a8797-801f87-21e9d2-5fc4c2-5745fa&dltentityid=".$dltentityid."&&dlttempid=".$dlttempid;

//echo $api_url;
//Submit to server
$response = file_get_contents( $api_url);
//echo $response;

?>