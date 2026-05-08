<?php
session_start();
include('../config.php');
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
if($Roll == 5){
    $BillSoftFrId = $_SESSION['Admin']['id'];
}
else{
    $BillSoftFrId = $row77['BillSoftFrId'];
}

if(isset($_POST["checkbox_value"]))
{
     $sql = "UPDATE tbl_cust_products SET Display=0 WHERE CreatedBy='$BillSoftFrId'";
    $conn->query($sql);
 for($count = 0; $count < count($_POST["checkbox_value"]); $count++)
 {
  $sql = "UPDATE tbl_cust_products SET Display=1 WHERE id = '".$_POST['checkbox_value'][$count]."'";
 $conn->query($sql);
 }
}
?>