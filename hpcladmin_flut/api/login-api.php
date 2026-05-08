<?php
// ================= CORS HEADERS (REQUIRED FOR FLUTTER WEB) =================
header("Access-Control-Allow-Origin: *"); // use domain in prod
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight (important for browser)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ================= DB =================
include "../config.php";

// ================= INPUT =================
$mobile = trim($_REQUEST['Phone'] ?? '');

// Validate
if ($mobile === '') {
    echo json_encode([
        'status' => false,
        'message' => 'Phone number required'
    ]);
    exit;
}

// ================= QUERY =================
$sql = "
    SELECT id, Phone, Fname
    FROM tbl_users_bill
    WHERE Phone = '$mobile'
    AND Status = 1
    LIMIT 1
";

$res = mysqli_query($conn, $sql);

// ================= RESPONSE =================
if ($res && mysqli_num_rows($res) > 0) {

    $data = mysqli_fetch_assoc($res);

    echo json_encode([
        'status' => true,
        'user'   => $data
    ]);

} else {

    echo json_encode([
        'status' => false,
        'message' => 'Invalid Mobile'
    ]);
}
?>
