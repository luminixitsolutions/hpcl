<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'config.php'; // uses $conn

// Read POST JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate input
if (!isset($data['userid']) || !isset($data['config'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User ID and config are required',
        'data' => null
    ]);
    exit;
}

$userid = mysqli_real_escape_string($conn, trim($data['userid']));

// Convert config array to JSON
$config_json = json_encode(
    $data['config'],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
);

// Escape JSON before inserting
$config_json = mysqli_real_escape_string($conn, $config_json);

// INSERT or UPDATE logic
$sql = "
INSERT INTO setup_configurations (userid, config_json, updated_at)
VALUES ('$userid', '$config_json', NOW())
ON DUPLICATE KEY UPDATE 
    config_json = '$config_json',
    updated_at = NOW()
";

// Execute Query
if (mysqli_query($conn, $sql)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Setup configuration updated successfully',
        'data' => [
            'updated_at' => date('c')
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . mysqli_error($conn),
        'data' => null
    ]);
}

mysqli_close($conn);
?>
