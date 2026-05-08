<?php
include "../config.php";
$mobile = $_REQUEST['Phone']; // ✅ Changed from $_POST to $_REQUEST

$sql = "SELECT id,Phone,Fname FROM tbl_users_bill WHERE Phone='$mobile' AND Status=1";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) > 0){
    $data = mysqli_fetch_assoc($res);
    echo json_encode(['status' => true, 'user' => $data]);
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid Mobile']);
}
?>