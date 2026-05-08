<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$sessionid = session_id();
$frid = $_POST['frid'];
$prodid = "";

if (!empty($_POST['selected_ids_combined'])) {
    //$selectedIds = explode(",", $_POST['selected_ids_combined']);
    $AllocateProd = rtrim($_POST['selected_ids_combined'], ',');
   $sql = "UPDATE tbl_users SET AllocateRawProd='$AllocateProd' WHERE id='$frid' AND Roll=5";
$conn->query($sql);
$sql = "UPDATE tbl_users_bill SET AllocateRawProd='$AllocateProd' WHERE id='$frid' AND Roll=5";
$conn->query($sql);
}

/*$sql = "DELETE FROM tbl_allocated_fr_raw_prd WHERE FrId='$frid'";
$conn->query($sql);
$sql2 = "SELECT * FROM tbl_temp_allocated_fr_raw_prd WHERE FrId='$frid' AND sessionid='$sessionid' AND checkstatus=1";
$row2 = getList($sql2);
foreach($row2 as $result){
    $sql3 = "INSERT INTO tbl_allocated_fr_raw_prd SET ProdId='".$result['ProdId']."',FrId='$frid',checkstatus='".$result['checkstatus']."'";
    $conn->query($sql3);
    $prodid .= $result['ProdId'] . ",";
}
$AllocateProd = rtrim($prodid, ","); 

$sql = "UPDATE tbl_users SET AllocateRawProd='$AllocateProd' WHERE id='$frid' AND Roll=5";
$conn->query($sql);
$sql = "UPDATE tbl_users_bill SET AllocateRawProd='$AllocateProd' WHERE id='$frid' AND Roll=5";
$conn->query($sql);
$sql = "DELETE FROM tbl_temp_allocated_fr_raw_prd WHERE sessionid='$sessionid'";
$conn->query($sql);*/

echo "<script>alert('Raw Product Assign Successfully');window.location.href='view-allocate-raw-products.php';</script>";
?>