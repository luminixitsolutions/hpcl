<?php
// ============================================
// Get APK Update Info API
// ============================================
// Author: Maha Chai Pvt. Ltd.
// Date: 2025-10-16
// Description: Fetch latest APK URL and status for a given user or default if user_id is blank.
// ============================================

// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Include database connection
include_once 'db.php'; // Replace with your DB connection file

// Initialize default response
$response = [
    "status" => "error",
    "message" => "Something went wrong."
];

// Get and sanitize user_id
$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;

// Function to extract version number from APK filename
function extractVersionFromFile($filename) {
    preg_match('/v?([\d\.]+)/', $filename, $match);
    return $match[1] ?? "1.0.0";
}

// Function to build structured response
function buildResponse($apk_url, $status, $updated) {
    $fileName = basename($apk_url);
    $version  = extractVersionFromFile($fileName);

    return [
        "status"  => "success",
        "message" => "APK link fetched successfully.",
        "data" => [
            "apk_name"   => $fileName ?: "app_latest.apk",
            "apk_url"    => $apk_url,
            "version"    => $version,
            "updated_on" => $updated,
            "status"     => $status
        ]
    ];
}

// CASE 1: If user_id is blank → directly fetch default active APK
if ($user_id <= 0) {
    $sql_default = "SELECT ApkUrl, Status, ModifiedDate FROM tbl_apk_update WHERE Status = 1 ORDER BY id DESC LIMIT 1";
    $result_default = mysqli_query($conn, $sql_default);

    if ($result_default && mysqli_num_rows($result_default) > 0) {
        $row = mysqli_fetch_assoc($result_default);
        $response = buildResponse($row['ApkUrl'], $row['Status'], $row['ModifiedDate']);
    } else {
        $response["message"] = "No active APK found.";
    }
} else {
    // CASE 2: user_id is provided → try user-specific record
    $sql_user = "SELECT ApkUrl, Status, ModifiedDate FROM tbl_apk_update WHERE FrId = '$user_id' LIMIT 1";
    $result_user = mysqli_query($conn, $sql_user);

    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $row = mysqli_fetch_assoc($result_user);
        $response = buildResponse($row['ApkUrl'], $row['Status'], $row['ModifiedDate']);
    } else {
        // Fallback to default global APK
        $sql_default = "SELECT ApkUrl, Status, ModifiedDate FROM tbl_apk_update WHERE Status = 1 ORDER BY id DESC LIMIT 1";
        $result_default = mysqli_query($conn, $sql_default);

        if ($result_default && mysqli_num_rows($result_default) > 0) {
            $row = mysqli_fetch_assoc($result_default);
            $response = buildResponse($row['ApkUrl'], $row['Status'], $row['ModifiedDate']);
        } else {
            $response["message"] = "No APK record found.";
        }
    }
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
