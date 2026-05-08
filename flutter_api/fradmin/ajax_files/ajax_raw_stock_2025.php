<?php
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';
$user_id = $_SESSION['Admin']['id'];
$frid = $_SESSION['fr_admin'];
if($_POST['action'] == 'getAvailProdStock'){
	$id = $_POST['id'];

	 $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' GROUP by Status) as a";
	$row = getRecord($sql);
	echo $row['balqty'];
}

if($_POST['action'] == 'getAvailRawProdStock'){
	$id = $_POST['id'];
$sql = "SELECT tu.Name2, tp.Unit
FROM tbl_cust_products2 AS tp
LEFT JOIN tbl_units AS tu ON tp.Unit = tu.Name
WHERE tp.ProdType = 1 AND tp.id = '$id'";
$row = getRecord($sql);
$Unit = $row['Name2'];
$Unit2 = $row['Unit'];
$sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM 
(SELECT (case when Status='Dr' then sum(Qty2) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty2) else '0' end) as creditqty 
FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$id' AND ProdType=1 AND FrId='$frid' GROUP by Status) as a";
	$row2 = getRecord($sql2);
	if($row2['balqty'] > 0){
	echo json_encode(array('Unit'=>$Unit,'Unit2'=>$Unit2,'balqty'=>$row2['balqty']));
	}
	else{
	    echo json_encode(array('Unit'=>$Unit,'Unit2'=>$Unit2,'balqty'=>0));
	}
}
?>