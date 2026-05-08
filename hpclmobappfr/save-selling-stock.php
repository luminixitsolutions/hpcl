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
$sql = "SELECT COALESCE(MAX(SrNo), 0) + 1 AS NextId FROM tbl_cust_stock_inv_2025";
$row = getRecord($sql);
$InvNo = "00".$row['NextId'];
$SrNo = $row['NextId'];
$sql = "INSERT INTO tbl_cust_stock_inv_2025 SET FrId='$fr_id',SrNo='$SrNo',InvNo='$InvNo',StockDate='$StockDate',TotalQty='$TotQty',Narration='$Narration',bill='$bill',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);
$SaveInvId = mysqli_insert_id($conn);
        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
    $Qty = addslashes(trim($product['Qty']));
    $PurchasePrice = addslashes(trim($product['PurchasePrice']));
    $SellPrice = addslashes(trim($product['SellPrice']));
     $qx = "INSERT INTO tbl_cust_prod_stock_2025_temp SET InvId='$SaveInvId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
       $conn->query($qx);
       $InvId = mysqli_insert_id($conn);
       
     
  
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Product Stock Request Sent Successfully!');window.location.href='view-cust-stocks.php';</script>";