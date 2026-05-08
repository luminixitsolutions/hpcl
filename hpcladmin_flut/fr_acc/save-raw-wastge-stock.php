<?php
session_start();
include_once 'config.php';
$user_id = $_SESSION['Admin']['id'];
$fr_id = $_SESSION['fr_admin'];
    $sql77 = "SELECT Roll FROM tbl_users_bill WHERE id='$fr_id'";
	$row77 = getRecord($sql77);
    $Roll = $row77['Roll'];
    if($Roll == 5){
        $BillSoftFrId = $_SESSION['fr_admin'];
    }
    else{
        $BillSoftFrId = $row77['BillSoftFrId'];
    }
$StockDate = addslashes(trim($_POST['StockDate']));
    $CreatedDate = date('Y-m-d H:i:s');
    $Narration = addslashes(trim($_POST['Narration']));
    


        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
            $PurchasePrice = addslashes(trim($product['PurchasePrice']));
            $TotalPrice = addslashes(trim($product['TotalPrice']));

            $Qty = addslashes(trim($product['Qty']));
            $Unit = addslashes(trim($product['QtyUnit']));
            $Qty2 = addslashes(trim($product['Qty2']));
            $Unit2 = addslashes(trim($product['QtyUnit2']));



    $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET Wastage='1',SellPrice='$TotalPrice',PurchasePrice='$PurchasePrice',ProdType=1,UserId='$BillSoftFrId',Qty2='$Qty2',Unit2='$Unit2',FrId='$BillSoftFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Dr',CreatedDate='$CreatedDate'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);

 
  
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Wastage Product Stock Added Successfully!');window.location.href='view-wastage-raw-stocks.php';</script>";