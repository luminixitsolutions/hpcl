<?php
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}
// if($_SESSION['User']['id'] == 404){
//     header('Location:index.php');
// }
if(!isset($_SESSION['User'])){
  //header('Location:login.php');
}
?>