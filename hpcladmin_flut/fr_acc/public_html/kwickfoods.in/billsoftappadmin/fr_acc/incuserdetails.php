<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Options = explode(',',$row77['Options']);
$Roll = $row77['Roll'];
if($Roll == 5){
    $BillSoftFrId = $_SESSION['Admin']['id'];
}
else{
    $BillSoftFrId = $row77['BillSoftFrId'];
}
?>