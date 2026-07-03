<?php
$user_id = $_SESSION['Admin']['id'];
$uid = $_REQUEST['uid'] ?? $_REQUEST['user_id'] ?? '';
if ($uid === '') {
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}
include_once __DIR__ . '/shop_admin_helper.php';
shopAdminEnforcePageAccess();
// if($_SESSION['Admin']['id'] == 404){
//     header('Location:index.php');
// }
if(!isset($_SESSION['Admin'])){
  header('Location:index.php');
}
?>