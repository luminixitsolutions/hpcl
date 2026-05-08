<?php
include 'db.php';
// Set JSON response header
header('Content-Type: application/json');
// Get and sanitize user_id
$user_id = $_REQUEST['user_id'];
if ($user_id !== '') {
$result = $conn->query("SELECT * FROM tbl_fr_billsoft_discount WHERE FrId='$user_id' AND delete_flag=0");
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(['status' => true, 'data' => $data]);
}
else {
    $response['status'] = false;
    $response['message'] = 'User ID is required';
}
