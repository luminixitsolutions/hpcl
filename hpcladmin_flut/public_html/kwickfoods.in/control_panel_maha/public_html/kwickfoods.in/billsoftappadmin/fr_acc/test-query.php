<?php 
require_once 'config.php';
$sql = "SELECT * FROM tbl_transfer_franchise_prod_stock_items WHERE Receive=1";
$row = getList($sql);
foreach($row as $result){
    $sql = "INSERT INTO tbl_cust_prod_stock SET ProdId='".$result['FrProdId']."',UserId='".$result['ToFrId']."',Qty='".$result['Qty']."',Status='Cr',StockDate='".$result['StockDate']."',Narration='Stock Received',CreatedBy='".$result['ToFrId']."',FrId='".$result['ToFrId']."'";
    
    $conn->query($sql);
}

?>