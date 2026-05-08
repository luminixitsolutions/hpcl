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
    $TotQty = addslashes(trim($_POST['TotQty']));
    
$randno = rand(1,100);
$src = $_FILES['bill']['tmp_name'];
$fnm = substr($_FILES["bill"]["name"], 0,strrpos($_FILES["bill"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["bill"]["name"],strpos($_FILES["bill"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$bill = $imagepath ;
} 
else{
	$bill = $_POST['Oldbill'];
}

$CreatedDate = date('Y-m-d H:i:s');
$sql = "SELECT COALESCE(MAX(SrNo), 0) + 1 AS NextId FROM tbl_mrp_available_stock_inv";
$row = getRecord($sql);
$InvNo = "00".$row['NextId'];
$SrNo = $row['NextId'];
$sql = "INSERT INTO tbl_mrp_available_stock_inv SET FrId='$fr_id',SrNo='$SrNo',InvNo='$InvNo',StockDate='$StockDate',TotalQty='$TotQty',Narration='$Narration',bill='$bill',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
$SaveInvId = mysqli_insert_id($conn);
        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
    $Qty = addslashes(trim($product['Qty']));
    $PurchasePrice = addslashes(trim($product['PurchasePrice']));
    $SellPrice = addslashes(trim($product['SellPrice']));
     $qx = "INSERT INTO tbl_mrp_available_stock SET InvId='$SaveInvId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
       $conn->query($qx);
       $InvId = mysqli_insert_id($conn);
       
       $sql = "SELECT * FROM tbl_cust_prod_stock_2025 WHERE Available='1' AND ProdId='$ProdId' AND FrId='$BillSoftFrId' AND ProdType=0";
       $rncnt = getRow($sql);
       if($rncnt > 0){} else{
       $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET Available='1',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
       $conn->query($qx);
       }
       
     /*$sql33 = "SELECT COALESCE(sum(creditqty), 0) AS creditqty,COALESCE(sum(debitqty), 0) AS debitqty,COALESCE(sum(creditqty)-sum(debitqty), 0) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE ProdId='$ProdId' AND ProdType=0 AND FrId='$BillSoftFrId' GROUP by Status) as a";
    $row33 = getRecord($sql33);
    $TotalSystemStock = $row33['balqty'];*/
    
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Product Available Stock Sent Successfully!');window.location.href='view-available-mrp-stocks.php';</script>";