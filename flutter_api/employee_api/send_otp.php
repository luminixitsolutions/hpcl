<?php
include "db.php";

$mobile = $_POST['mobile'];
$otp = '12345'; // fixed OTP for demo

// Delete old OTP if exists
mysqli_query($conn, "DELETE FROM otp_verification WHERE mobile='$mobile'");

// Insert new OTP
mysqli_query($conn, "INSERT INTO otp_verification (mobile, otp) VALUES ('$mobile', '$otp')");

// Send SMS
$smstxt = "Please enter $otp OTP on our platform to complete the verification process. Thank you for choosing Maha Chai.";
$sms_text = urlencode($smstxt);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.pinnacle.in/index.php/sms/send/MHCHAI/$mobile/$sms_text/TXT?apikey=4a8797-801f87-21e9d2-5fc4c2-5745fa",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo json_encode(['status' => 'success']);
?>