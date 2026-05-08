<?php
include 'db.php';
$result = $conn->query("SELECT * FROM tbl_users_bill WHERE Roll IN (5,63)");
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode(['status' => true, 'data' => $data]);
