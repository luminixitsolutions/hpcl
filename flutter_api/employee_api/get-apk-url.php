<?php
// Set headers for JSON response and CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Define your APK URL
$apk_url = "https://persistsolution.com/flut/s1.apk";

// Prepare response
$response = array(
    "status" => "success",
    "message" => "APK download link fetched successfully",
    "data" => array(
        "apk_name" => "version6",
        "apk_url" => $apk_url,
        "version" => "2.0.6", // Optional, you can change
        "updated_on" => "2025-10-22", // Optional, dynamic date possible
        "status" => "0"
    )
);

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
