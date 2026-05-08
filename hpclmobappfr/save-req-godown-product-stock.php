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
    $VedId = addslashes(trim($_POST['VedId']));
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
$sql = "SELECT COALESCE(MAX(SrNo), 0) + 1 AS NextId FROM tbl_fr_req_stock_inv";
$row = getRecord($sql);
$InvNo = "G00".$row['NextId'];
$SrNo = $row['NextId'];
$sql = "INSERT INTO tbl_fr_req_stock_inv SET VedId='$VedId',FrId='$fr_id',SrNo='$SrNo',InvNo='$InvNo',StockDate='$StockDate',TotalQty='$TotQty',Narration='$Narration',bill='$bill',CreatedBy='$user_id',CreatedDate='$CreatedDate',ProdType=2";
$conn->query($sql);
$SaveInvId = mysqli_insert_id($conn);
        foreach ($_SESSION["cart_item"] as $product) {
            $ProdId = $product['id'];
    $Qty = addslashes(trim($product['Qty']));
     $Unit = addslashes(trim($product['QtyUnit']));
            $Qty2 = addslashes(trim($product['Qty2']));
            $Unit2 = addslashes(trim($product['QtyUnit2']));
    $PurchasePrice = addslashes(trim($product['PurchasePrice']));
    $SellPrice = addslashes(trim($product['SellPrice']));
     $qx = "INSERT INTO tbl_fr_req_prod_stock SET VedId='$VedId',InvId='$SaveInvId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice',PurchaseQty='$Qty2',PurchaseComment='Pending',ProdType2=3,Qty2='$Qty2',Unit2='$Unit2',Unit='$Unit'";
       $conn->query($qx);
     
        }
        
        unset($_SESSION["cart_item"]);
      echo "<script>alert('Godown Product Stock Request Sent Successfully!');window.location.href='view-req-godown-product-stocks.php';</script>";