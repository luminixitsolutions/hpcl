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
    $VedId = addslashes(trim($_POST['VedId']));
    


        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
            $PurchasePrice = addslashes(trim($product['PurchasePrice']));
            $TotalPrice = addslashes(trim($product['TotalPrice']));

            $Qty = addslashes(trim($product['Qty']));
            $Unit = addslashes(trim($product['QtyUnit']));
            $Qty2 = addslashes(trim($product['Qty2']));
            $Unit2 = addslashes(trim($product['QtyUnit2']));



    $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET VedId='$VedId',SellPrice='$TotalPrice',PurchasePrice='$PurchasePrice',ProdType=1,UserId='$BillSoftFrId',Qty2='$Qty2',Unit2='$Unit2',FrId='$BillSoftFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',CreatedDate='$CreatedDate'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);
  
   $qx = "INSERT INTO tbl_cust_prod_stock_2025_backup SET VedId='$VedId',SellPrice='$TotalPrice',PurchasePrice='$PurchasePrice',ProdType=1,UserId='$BillSoftFrId',Qty2='$Qty2',Unit2='$Unit2',FrId='$BillSoftFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',CreatedDate='$CreatedDate',orgstockid='$InvId'";
  $conn->query($qx);
  
  // Fetch the inserted record
$result = $conn->query("SELECT * FROM tbl_cust_prod_stock_2025 WHERE id = '$InvId'");
$row = $result->fetch_assoc();

// Create SQL Dump
$sqlDump = "INSERT INTO tbl_cust_prod_stock_2025 (SellPrice, PurchasePrice, ProdType, UserId, Qty2, Unit2, FrId, ProdId, Qty, Unit, CreatedBy, StockDate, Narration, Status, CreatedDate) 
            VALUES ('" . implode("','", array_values($row)) . "');\n";

file_put_contents('raw_stock_backup/'.$BillSoftFrId.'_backup.sql', $sqlDump, FILE_APPEND | LOCK_EX);
  
  $sql = "UPDATE tbl_cust_products2 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
  $conn->query($sql);
  $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new customer raw product stock added',invid='$InvId',createddate='$createddate',roll='customer-raw-product-stock'";
  $conn->query($sql);
  
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Product Stock Added Successfully!');window.location.href='view-fr-raw-stock-2025.php';</script>";