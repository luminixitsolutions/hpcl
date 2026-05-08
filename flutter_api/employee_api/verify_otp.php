<?php
include "db.php";

$mobile = $_POST['mobile'];
$otp = $_POST['otp'];

$sql = "SELECT * FROM otp_verification WHERE mobile='$mobile' AND otp='$otp' ORDER BY id DESC LIMIT 1";
$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) > 0) {
    // OTP matched - fetch user
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile='$mobile' LIMIT 1"));
    echo json_encode(['status' => 'success', 'user' => $user]);
} else {
    echo json_encode(['status' => 'fail', 'message' => 'Invalid OTP']);
}
?>