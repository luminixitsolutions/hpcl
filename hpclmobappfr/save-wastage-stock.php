<?php
session_start();
include_once 'config.php';
$user_id = $_SESSION['User']['id'];
$fr_id = $_SESSION['FranchiseId'];
    $sql77 = "SELECT Roll FROM tbl_users_bill WHERE id='$fr_id'";
	$row77 = getRecord($sql77);
    $Roll = $row77['Roll'];
    if($Roll == 5){
        $BillSoftFrId = $_SESSION['FranchiseId'];
    }
    else{
        $BillSoftFrId = $row77['BillSoftFrId'];
    }
$StockDate = addslashes(trim($_POST['StockDate']));
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));
    
    $sql = "INSERT INTO tbl_wastage_invoice SET FrId='$fr_id',UserId='$user_id',StockDate='$StockDate',Narration='$Narration',CreatedDate='$CreatedDate',Type=0";
$conn->query($sql);    
$InvId = mysqli_insert_id($conn);


        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
    $Qty = addslashes(trim($product['Qty']));
    $PurchasePrice = addslashes(trim($product['PurchasePrice']));
    $SellPrice = addslashes(trim($product['SellPrice']));
    $Photo = addslashes(trim($product['Photo']));
     $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET Wastage='1',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Dr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice',WastageInvId='$InvId',Photo='$Photo'";
       $conn->query($qx);
       //$InvId = mysqli_insert_id($conn);
  
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Wastage Product Stock Added Successfully!');window.location.href='view-wastage-stocks.php';</script>";