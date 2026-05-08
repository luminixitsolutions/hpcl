<?php 
session_start();
require_once 'config.php';
$amount = $_POST['amount'];
$userid = $_POST['userid'];
$phone = $_POST['phone'];
$pkgid = $_POST['pkgid'];
$OrderDate = date('Y-m-d');
$OrderTime = date('h:i a');
$CreatedDate = date('Y-m-d');

$sql = "INSERT INTO wallet SET UserId='$userid',Amount='$amount',Narration='Amount Added into wallet',Status='Cr',CreatedDate='$CreatedDate',CreatedTime='$OrderTime'";
$conn->query($sql);
//echo "<script>window.location.href='my-orders.php';</script>";
echo "https://rjorg.in/teasoftware/custapp/profile.php";
?>