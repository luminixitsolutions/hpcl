<?php 
include_once 'config.php';
$sql = "SELECT * FROM tbl_cust_products";
$row = getList($sql);
foreach($row as $result){
    $MinPrice = $result['MinPrice'];
    $CgstPer = 2.5;
    $SgstPer = 2.5;
    $CgstAmt2 = $MinPrice*($CgstPer/100);
    $CgstAmt = round($CgstAmt2, 2);
    $SgstAmt2 = $MinPrice*($SgstPer/100);
    $SgstAmt = round($SgstAmt2, 2);
    $GstAmt2 = $CgstAmt + $SgstAmt;
    $GstAmt = round($GstAmt2, 2);
    $ProdPrice2 = $MinPrice - $GstAmt;
    $ProdPrice = round($ProdPrice2, 2);
    $sql = "UPDATE tbl_cust_products SET CgstPer='2.5',SgstPer='2.5',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',ProdPrice='$ProdPrice',GstAmt='$GstAmt' WHERE id='".$result['id']."'";
    $conn->query($sql);
}
?> 