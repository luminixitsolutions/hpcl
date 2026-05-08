<?php 
include_once 'config.php';
$sql = "SELECT id,ProductName,MinQty,BarcodeNo FROM `tbl_cust_products` WHERE CreatedBy='253' AND ProdType=0 AND BarcodeNo NOT IN ('',0)";
$row = getList($sql);
foreach($row as $result){
    $ProductName = $result['ProductName'];
    $MinQty = $result['MinQty'];
    $BarcodeNo = $result['BarcodeNo'];
    $sql2 = "SELECT id FROM `tbl_cust_products` WHERE ProductName='$ProductName' AND MinQty<=0 AND CreatedBy!='253'";
    $row2 = getList($sql2);
    foreach($row2 as $result2){
        $id = $result2['id'];
        $sql = "UPDATE tbl_cust_products SET BarcodeNo='$BarcodeNo',MinQty='$MinQty',modified_time='2024-10-22 16:47:23.686' WHERE id='$id'";
        $conn->query($sql);
    }
}
?>