<?php 
session_start();
include_once 'config.php';
$id=$_REQUEST['id'];
$latitude=$_REQUEST['latitude'];
$longitude=$_REQUEST['longitude'];

if($id != ''){
$sql = "UPDATE tbl_users SET Lattitude='$latitude',Longitude='$longitude' WHERE id='$id'";
$conn->query($sql);
echo "location updated";
}
else{
 //echo "0 Row affected";   
}
?>