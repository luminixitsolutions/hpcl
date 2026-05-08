<?php
include 'db.php';
$result = $conn->query("SELECT * FROM tbl_payment_mode");
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(['status' => true, 'data' => $data]);
