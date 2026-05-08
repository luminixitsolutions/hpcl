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
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));
    $VedId = addslashes(trim($_POST['VedId']));


        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
    $Qty = addslashes(trim($product['Qty']));
    $PurchasePrice = addslashes(trim($product['PurchasePrice']));
    $SellPrice = addslashes(trim($product['SellPrice']));
    $MfgDate = addslashes(trim($product['MfgDate']));
    $ExpDate = addslashes(trim($product['ExpDate']));
     $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET MfgDate='$MfgDate',ExpDate='$ExpDate',VedId='$VedId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
       $conn->query($qx);
       $InvId = mysqli_insert_id($conn);
       
     $qx = "INSERT INTO tbl_cust_prod_stock_2025_backup SET MfgDate='$MfgDate',ExpDate='$ExpDate',VedId='$VedId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice',orgstockid='$InvId'";
  $conn->query($qx);
  
  // Fetch the inserted records
$result = $conn->query("SELECT * FROM tbl_cust_prod_stock_2025 WHERE id = '$InvId'");
$row = $result->fetch_assoc();

// Create SQL Dump
$sqlDump = "INSERT INTO tbl_cust_prod_stock_2025 (ProdId, Qty, CreatedBy, StockDate, Narration, Status, UserId, CreatedDate, FrId, PurchasePrice, SellPrice) 
            VALUES ('" . implode("','", array_values($row)) . "');\n";

file_put_contents('stock_backup/'.$BillSoftFrId.'_backup.sql', $sqlDump, FILE_APPEND | LOCK_EX);

  
  $sql = "UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
  $conn->query($sql);
  $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new customer product stock added',invid='$InvId',createddate='$createddate',roll='customer-product-stock'";
  $conn->query($sql);
  
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Product Stock Added Successfully!');window.location.href='view-cust-stocks-2025.php';</script>";