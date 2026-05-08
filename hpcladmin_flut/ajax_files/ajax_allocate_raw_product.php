<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'saveCart'){
    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $id = $_POST['id'];
    $frid = $_POST['frid'];
    $sql = "UPDATE tbl_cust_products_2025 SET checkstatus=1,push_flag=1,delete_flag=0,modified_time='$modified_time' WHERE CreatedBy='$frid' AND id='$id'";
    $conn->query($sql);
    
    $sql2 = "SELECT CatId FROM tbl_cust_products_2025 WHERE id='$id'";
    $row2 = getRecord($sql2);
    $CatId = $row2['CatId'];
    
    $sql="UPDATE tbl_cust_category_2025 SET push_flag=1,modified_time='$modified_time' WHERE id='$CatId'";
    $conn->query($sql);
}

if($_POST['action'] == 'deletCart'){
    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $id = $_POST['id'];
    $frid = $_POST['frid'];
    $sql = "UPDATE tbl_cust_products_2025 SET checkstatus=0,push_flag=1,delete_flag=1,modified_time='$modified_time' WHERE CreatedBy='$frid' AND id='$id'";
    $conn->query($sql);
    
    $sql2 = "SELECT CatId FROM tbl_cust_products_2025 WHERE id='$id'";
    $row2 = getRecord($sql2);
    $CatId = $row2['CatId'];
    
    $sql="UPDATE tbl_cust_category_2025 SET push_flag=1,modified_time='$modified_time' WHERE id='$CatId'";
    $conn->query($sql);
}
?>