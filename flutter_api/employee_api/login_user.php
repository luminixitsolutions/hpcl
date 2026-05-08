<?php
include "db.php";
$mobile = $_REQUEST['Phone']; // ✅ Changed from $_POST to $_REQUEST

$sql = "SELECT * FROM tbl_users_bill WHERE Phone='$mobile' AND Roll IN (5,63,1)";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
    $data = mysqli_fetch_assoc($res);
    echo json_encode(['status' => true, 'user' => $data]);
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid Mobile']);
}
?>