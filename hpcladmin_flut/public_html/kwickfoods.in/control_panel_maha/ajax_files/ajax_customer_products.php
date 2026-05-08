<?php
session_start();
include_once '../config.php';
include('../../libs/phpqrcode/qrlib.php');
$user_id = $_SESSION['Admin']['id'];
include 'incuserdetails.php';
if($_POST['action'] == 'Add'){
    $TempPrdId = addslashes(trim($_POST['TempPrdId']));
    $ProductName = addslashes(trim($_POST['ProductName']));
    $CatId = $_POST['CatId'];
    $MinPrice = $_POST['MinPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $ProdPrice = addslashes(trim($_POST['ProdPrice']));
    $CgstAmt = addslashes(trim($_POST['CgstAmt']));
    $SgstAmt = addslashes(trim($_POST['SgstAmt']));
    $IgstAmt = addslashes(trim($_POST['IgstAmt']));
    $BarcodeNo = addslashes(trim($_POST['BarcodeNo']));
    $StockQty = addslashes(trim($_POST['StockQty']));
    $Transfer = addslashes(trim($_POST['Transfer']));
    $MinQty = addslashes(trim($_POST['MinQty']));
    $ProdType2 = addslashes(trim($_POST['ProdType2']));
    $SubCatId = addslashes(trim($_POST['SubCatId']));
    $PurchasePrice = addslashes(trim($_POST['PurchasePrice']));

    $CreatedDate = date('Y-m-d');
    $Status = $_POST['Status'];
    $SrNo = $_POST['SrNo'];
    $randno = rand(1,100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Photo = $imagepath ;
    } 
    else{
      $Photo = $_POST['OldPhoto'];
    }
    $modified_time = gmdate('Y-m-d H:i:s.').gettimeofday()['usec'];
    
    function RandomStringGenerator($n)
    {
        $generated_string = "";   
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++)
        {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        return $generated_string;
    } 
    $n = 10;
    $Code = RandomStringGenerator($n); 
    
    $sql = "INSERT INTO tbl_cust_products SET PurchasePrice='$PurchasePrice',ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',CreatedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',BarcodeNo='$BarcodeNo',StockQty='$StockQty',TempPrdId='$TempPrdId',CreatedBy='$BillSoftFrId',modified_time='$modified_time',Transfer='$Transfer',Photo='$Photo',MinQty='$MinQty',ProdType2='$ProdType2',SubCatId='$SubCatId'";
    $conn->query($sql);
    $ProdId = mysqli_insert_id($conn); 
  
    $sql = "INSERT INTO tbl_cust_prod_stock SET ProdId='$ProdId',Qty='$StockQty',Status='Cr',CreatedDate='$CreatedDate',Narration='Opening Stock',Main=1,StockDate='$CreatedDate',CreatedBy='$BillSoftFrId',UserId='$BillSoftFrId',FrId='$BillSoftFrId'";
    $conn->query($sql);
$Code2 = $Code."".$ProdId; 
$sql2 = "UPDATE tbl_cust_products SET code='$Code2' WHERE id='$ProdId'";
$conn->query($sql2);
if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_cust_product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script type="text/javascript">
    alert("New Product Added Successfully!");
    window.location.href="../view-customer-products.php";
</script>
<?php
}


if($_POST['action'] == 'Edit'){
    $id = $_POST['id'];
    $ProductName = addslashes(trim($_POST['ProductName']));
    $CatId = $_POST['CatId'];
    $MinPrice = $_POST['MinPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $ProdPrice = addslashes(trim($_POST['ProdPrice']));
    $CgstAmt = addslashes(trim($_POST['CgstAmt']));
    $SgstAmt = addslashes(trim($_POST['SgstAmt']));
    $IgstAmt = addslashes(trim($_POST['IgstAmt']));
    $BarcodeNo = addslashes(trim($_POST['BarcodeNo']));
    $StockQty = addslashes(trim($_POST['StockQty']));
    $Transfer = addslashes(trim($_POST['Transfer']));
    $MinQty = addslashes(trim($_POST['MinQty'])); 
    $ProdType2 = addslashes(trim($_POST['ProdType2']));
    $SubCatId = addslashes(trim($_POST['SubCatId']));
    $PurchasePrice = addslashes(trim($_POST['PurchasePrice']));
    $CreatedDate = date('Y-m-d');
    $modified_time = gmdate('Y-m-d H:i:s.').gettimeofday()['usec'];
    $Status = $_POST['Status'];
    $SrNo = $_POST['SrNo'];
    $randno = rand(1,100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Photo = $imagepath ;
    } 
    else{
      $Photo = $_POST['OldPhoto'];
    }
    
    $sql = "UPDATE tbl_cust_products SET PurchasePrice='$PurchasePrice',ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',Photo='$Photo',ModifiedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',BarcodeNo='$BarcodeNo',StockQty='$StockQty',modified_time='$modified_time',push_flag=1,Transfer='$Transfer',MinQty='$MinQty',ProdType2='$ProdType2',SubCatId='$SubCatId' WHERE id='$id'";
    $conn->query($sql);
    $ProdId = $_POST['id'];
    
   /* $sql = "DELETE FROM tbl_cust_prod_stock WHERE ProdId='$ProdId' AND Status='OB' AND Main=1";
    $conn->query($sql);*/
   /* $sql = "INSERT INTO tbl_cust_prod_stock SET ProdId='$ProdId',Qty='$StockQty',Status='Cr',CreatedDate='$CreatedDate',Narration='Opening Stock',Main=1,StockDate='$CreatedDate',CreatedBy='$BillSoftFrId',UserId='$BillSoftFrId',FrId='$BillSoftFrId'";
    $conn->query($sql);*/
    
if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB'; 
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_cust_product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script type="text/javascript">
    alert("Product Updated Successfully!");
    window.location.href="../view-customer-products.php";
</script>
<?php
}

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_cust_products SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Product Photo Delete Successfully";
} 
if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $pid = $_POST['pid'];
    $Photo = $_POST['Photo'];
        $q = "DELETE FROM tbl_cust_product_images WHERE id=$id AND ProductId='$pid'";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Product Photo Delete Successfully";
}

if($_POST['action'] == 'showProdImages'){ 
    $id = $_POST['id'];
  $sql2 = "SELECT * FROM tbl_cust_product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_POST["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} } 

if($_POST['action'] == 'savePrint'){ 
    $CellNo = addslashes(trim($_POST['CellNo']));
    $CustId2 = $_POST['CustId'];
    $CustName = addslashes(trim($_POST['CustName']));
    $AccCode = addslashes(trim($_POST['AccCode']));
    $PayType = addslashes(trim($_POST['PayType']));
    $Featured = addslashes(trim($_POST['Featured']));
    $Print = addslashes(trim($_POST['Print']));
    $value = $_POST['value'];
    foreach ($_SESSION["cart_item"] as $product){
      $total_price3 += ($product["price"]*$product["quantity"]);
    }
    $InvoiceDate = date('Y-m-d');
    $InvoiceDate2 = date('d/m/Y');
    $CreatedTime = date('H:i:s');
   $DiscPer = $_POST['DiscPer'];
    $SubTotal = $total_price3;
    $Discount = $_POST['Discount'];
   

    if($_POST['PackageId'] == 0){
        $PkgId = addslashes(trim($_POST['PkgId']));
    }
    else{
        $PkgId = addslashes(trim($_POST['PackageId'])); 
    }
              
     $PkgAmt = addslashes(trim($_POST['PkgAmt']));
    $PkgDiscount = addslashes(trim($_POST['PkgDiscount']));
    $PkgValidity = addslashes(trim($_POST['PkgValidity']));
    $PrimeDiscount = addslashes(trim($_POST['PrimeDiscount']));
    $NetAmount = $total_price3-$PrimeDiscount-$Discount;
    $Advance = $total_price3-$PrimeDiscount-$Discount;

    $Balance = 0;
    $tempDir = '../../barcodes/';
    $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2";
    $row8 = getRecord($sql8);
    $MaxId = $row8['MaxId'] + 1;
    $InvoiceNo = "" . $MaxId;
    
    $sql81 = "SELECT MAX(OrderNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='$InvoiceDate' AND FrId='$BillSoftFrId'";
    $row81 = getRecord($sql81);
    $MaxId22 = $row81['MaxId'] + 1;
    $OrderNo = $MaxId22;
    
    if($CustId2==0){

      if($CustName!='' && $CellNo!=''){
        $sql = "INSERT INTO tbl_users SET Fname='$CustName',Phone='$CellNo',EmailId='$EmailId',Address='$Address',UnderFr='0',CreatedBy='$user_id',CreatedDate='$CreatedDate',Roll=55,Status=1";
        $conn->query($sql);
        $CustId = mysqli_insert_id($conn);

        $filename = $CustId.".png";
        $codeContents = $CellNo;
        QRcode::png($codeContents, $tempDir.''.$filename, QR_ECLEVEL_L, 5);
        $Barcode = $filename;
        $CustomerId = "C".$CustId;
        $sql3 = "UPDATE tbl_users SET Barcode='$Barcode',CustomerId='$CustomerId' WHERE id='$CustId'";
        $conn->query($sql3);  
         if($PkgId!=0){
                            $sql3 = "UPDATE tbl_users SET PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',Prime=1 WHERE id='$CustId'";
                        $conn->query($sql3);
                        }
    }
    
    else{
                            $CustId = 0;
                        }
    
    
    }
    else{
          $CustId = $_POST['CustId'];
          if($PkgId!=0){
                            $sql3 = "UPDATE tbl_users SET PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',Prime=1 WHERE id='$CustId'";
                        $conn->query($sql3);
                        }
    }
    $sql = "INSERT INTO tbl_customer_invoice SET id='$InvoiceNo',AccCode='$AccCode',SrNo='$MaxId',CustId='$CustId',CustName='$CustName',
                            CellNo='$CellNo',Address='$Address',EmailId='$EmailId',InvoiceNo='$InvoiceNo',
                            InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',
                            CgstPer='$CgstPer',SgstPer='$SgstPer',
                            IgstPer='$IgstPer',SubTotal='$SubTotal',
                            GstAmt='$GstAmt',CreatedBy='$user_id',CreatedDate='$CreatedDate',
                            ExtraAmount='$ExtraAmount',TotalAmount='$TotalAmount',Discount='$Discount',
                            NetAmount='$NetAmount',Advance='$Advance',Balance='$Balance',
                            ExtraReason='$ExtraReason',ChequeNo='$ChequeNo',ChqDate='$ChqDate',
                            BankName='$BankName',UpiNo='$UpiNo',PayType2='$PayType2',
                            ChequeNo2='$ChequeNo2',ChqDate2='$ChqDate2',
                            BankName2='$BankName2',UpiNo2='$UpiNo2',Amount1='$Amount1',Amount2='$Amount2',Status='$Featured',Roll=2,PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',PrimeDiscount='$PrimeDiscount',OrderNo='$OrderNo',CreatedTime='$CreatedTime',FrId='$BillSoftFrId',DiscPer='$DiscPer'";
                    $conn->query($sql);
                    $SellId = mysqli_insert_id($conn);
                    //$SellId = $InvoiceNo;
                    $filename = $SellId . ".png";
                    $randno = rand(1000,9999);
                    $BillNo = $randno."".$SellId;
                    $sql3 = "UPDATE tbl_customer_invoice SET Barcode='$Barcode',BillNo='$BillNo',id='$SellId' WHERE Unqid='$SellId'";
                    $conn->query($sql3);


                    $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
                    $row2 = getRecord($sql2);
                    if ($row2['maxid'] == '') {
                        $SrNo = 1;
                        $Code = "PR" . $SrNo;
                    } else {
                        $SrNo = $row2['maxid'] + 1;
                        $Code = "PR" . $SrNo;
                    }

                    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='OB',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$NetAmount',CrDr='dr',Roll=5,
                            Type='OB',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='Total Invoice Amount',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',Main=1,CreatedBy='$user_id',BillSoftFrId='$BillSoftFrId'";
                    $conn->query($sql4);
                    if ($Advance > 0) {
                        if($PayType == 'Borrowing'){
                            $BorrowFlag = 1;
                        }
                        else{
                            $BorrowFlag = 0;
                        }
                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='cr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',Main=1,CreatedBy='$user_id',BorrowFlag='$BorrowFlag',BillSoftFrId='$BillSoftFrId'";
                        $conn->query($sql4);
                        $PostId = mysqli_insert_id($conn);

                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='0',
                            AccCode='AC1',
                            AccountName='Comapny Account',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='dr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Company',PostId='$PostId',Main=1,CreatedBy='$user_id',BorrowFlag='$BorrowFlag',BillSoftFrId='$BillSoftFrId'";
                        $conn->query($sql4);
                    }

 foreach ($_SESSION["cart_item"] as $product){
                        
                     $ProdCode = $product["code"];
                     $sql = "SELECT * FROM tbl_cust_products WHERE code='$ProdCode'";
                     $row = getRecord($sql);
                     $CatId = $row['CatId'];
                     $ActPrice =  $product["price"];
                     $ProdPrice = $row['ProdPrice'];
                     $Qty = $product["quantity"];
                     $TotalPrice = $ProdPrice*$Qty;
                    //  $CgstAmt = $TotalPrice*($row['CgstPer']/100);
                    //  $SgstAmt = $TotalPrice*($row['SgstPer']/100);
                    //  $IgstAmt = $TotalPrice*($row['IgstPer']/100);
                     $CgstAmt = $row['CgstAmt']*$Qty;
                     $SgstAmt = $row['SgstAmt']*$Qty;
                     $IgstAmt = $row['IgstAmt']*$Qty;
                     $CgstPer = $row['CgstPer'];
                     $SgstPer = $row['SgstPer'];
                     $IgstPer = $row['IgstPer'];
                     $GstAmt = $CgstAmt+$SgstAmt+$IgstAmt;
                     $Total = $GstAmt+$TotalPrice;
                     $ProdId = $row['id'];
                     
                     $sql22 = "INSERT INTO tbl_customer_invoice_details SET ServerInvId='$SellId',FrId='$BillSoftFrId',InvId='$SellId',ProdId='$ProdId',Qty='$Qty',Price='$ProdPrice',CreatedDate='$InvoiceDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',Total='$Total',CustProd=1,CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ActPrice='$ActPrice',CatId='$CatId'";
                    $conn->query($sql22);
                    $InvDetId = mysqli_insert_id($conn);
                     $sql33 = "UPDATE tbl_customer_invoice_details SET id='$InvDetId' WHERE Unqid='$InvDetId'";
                    $conn->query($sql33);
                    $Narration = "Stock Used Against Invoice No : ".$InvoiceNo;
                    $qx = "INSERT INTO tbl_cust_prod_stock SET FrId='$BillSoftFrId',ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$InvoiceDate',Narration='$Narration',Status='Dr',UserId='0',CreatedDate='$InvoiceDate',InvId='$SellId'";
                    $conn->query($qx);
                    
                    if($BillSoftFrId == 9){
                        $sql2 = "SELECT * FROM tbl_fr_raw_counts WHERE ProdId='$ProdId' AND FrId='$BillSoftFrId'";
                        $rncnt2 = getRow($sql2);
                        if($rncnt2 > 0){
                            $sql = "UPDATE tbl_fr_raw_counts SET Cnt=Cnt+$Qty WHERE ProdId='$ProdId' AND FrId='$BillSoftFrId'";
                            $conn->query($sql);
                        }
                        else{
                    $sql = "INSERT INTO tbl_fr_raw_counts SET ProdId='$ProdId',FrId='$BillSoftFrId',Cnt=Cnt+$Qty";
                    $conn->query($sql);
                        }
                        
                        $sql33 = "SELECT * FROM tbl_raw_prod_make_qty WHERE CustProdId='$ProdId'";
                        $row33 = getRecord($sql33);
                        $RawProdId = $row33['RawProdId'];
                        $MakingQty = $row33['MakingQty'];
                        $RawQty = $row33['RawQty'];
                        $RawUnit = $row33['RawUnit'];
                        
                        $row211 = getRecord($sql2);
                        $count = $row211['Cnt'];
                        if($count == $MakingQty){
                            $sql = "INSERT INTO tbl_fr_raw_stock SET FrId='$BillSoftFrId',ProdId='$RawProdId',Qty='$RawQty',Unit='$RawUnit',
                            StockDate='$InvoiceDate',Narration='Stock Used',Status='Dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate'";
                            $conn->query($sql);
                            $sql = "UPDATE tbl_fr_raw_counts SET Cnt=0 WHERE ProdId='$ProdId' AND FrId='$BillSoftFrId'";
                            $conn->query($sql);
                        }
                    }
       }
       unset($_SESSION["cart_item"]);
       
       if($Featured == 1){
        $smstxt = "POS Receipt: https://kwickfoods.in/in/invoice/$BillNo 
Thank you for purchasing with MAHACHAI.(www.mahachai.in) 
Total Amount $NetAmount $InvoiceDate2 $CreatedTime
Your Feedback Matters : https://kwickfoods.in/feedback";
$Phone = $CellNo;
if($Phone!=''){
    $dltentityid = "1501701120000037351";
  $dlttempid = "1707169839028393861";
       include '../../incsmsapi.php';
}
}
       $sql55 = "SELECT * FROM tbl_users_bill WHERE id='$BillSoftFrId'";
            $row55 = getRecord($sql55);
            $title = str_replace(" ","#",$row55['PrintCompName']);
            $Address = str_replace(" ","#",$row55['Address']);
            $Phone = str_replace(" ","#",$row55['PrintMobNo']);
            $GstNo = str_replace(" ","#",$row55['GstNo']);
            //$terms_condition = str_replace(" ","#",$row55['terms_condition']);
            $bottom_title = str_replace(" ","#",$row55['bottom_title']);
            //$bottom_title.="#".date('h:i a');
            
            $sql = "SELECT * FROM tbl_customer_invoice WHERE id='$SellId'";
    $row = getRecord($sql);
    $CustName = str_replace(" ","#",$row['CustName']);
        $emparray = array();
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='".$row['id']."'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName FROM  tbl_cust_products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=number_format((float)$result12['GstAmt'], 2, '.', '');
                //$TotGst+=round($result12['GstAmt'],2);
                $Product_name2 = str_replace(" ","#",$row13['ProductName']);
                $Product_name = substr($Product_name2,0,16);
                 $emparray[] = array('item'=>$Product_name,'rate'=>$result12['Price'],'quantity'=>$result12['Qty'],'amount'=>$result12['Total']);
            }
            $terms_condition = "Time:".date('H:i:s')."#|Pay#Mode:#".$row['PayType'];
    $dados2 =  json_encode(array('productList' => $emparray));
    $date = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));
    $dados1 = json_encode(array('title'=>$title,'address'=>$Address,'mobile'=>$Phone,'gstin'=>$GstNo,'customer_name'=>$CustName,'customer_phone_no'=>$row['CellNo'],'receipt_title'=>'Retail#Receipt','invoice_no'=>$row['InvoiceNo'],'date'=>$date,'sub_total'=>$row['SubTotal'],'discount'=>$row['Discount'],'service_charge'=>0,'gst'=>$TotGst,'total_bill'=>$row['NetAmount'],'total_payable'=>$row['NetAmount'],'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title)); 
    $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
     if($Print == 1){
    echo $invoice_data;
     }
     else{
         echo 1;
     }
   //echo 1;    
}


if($_POST['action'] == 'showProduct'){?>
  
     <?php 
    $catid = $_POST['catid'];
                                $i=1;
                                
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 AND id='$catid' ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                    $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' ORDER BY SrNo asc";
                                    $row2 = getList($sql2);
                                    foreach($row2 as $result2){
                                        $code = $result2['code'];
                                        $sessqty2 =  $_SESSION["cart_item"][$code]['quantity'];
                                        if($sessqty2 == ''){
                                            $sessqty = 0;
                                        }
                                        else{
                                            $sessqty = $sessqty2;
                                        }
                            ?>
                            <div align="center" class="gallery-thumbnail col-sm-4 col-md-6  col-4 col-xl-3 mb-2" data-tag="cat<?php echo $result['id'];?>" style="">
                                <a href="javascript:void(0)" onclick="addCart(<?php echo $result2['id'];?>)">
                               
                                    <?php if($result2['Photo']==''){?>
                                     <img src="no_image.jpg" class="img-fluid" alt="images" style="width:163px;110px;">
                                    <?php } else { ?>
                                    <img src="../uploads/<?php echo $result2['Photo'];?>" class="img-fluid" alt="images" style="width:163px;110px;">
                                    <?php } ?>
                                </a>
                                <div align="center" style="padding-top:2px;">
                                <strong style="text-align:center;font-size: 12px;letter-spacing: 0.5px;"><?php echo $result2['ProductName'];?></strong><br>
                                <strong style="font-size:17px;color:#f06721;">&#8377; <?php echo number_format($result2['MinPrice'],2); ?></strong>
                               
                                  <input class="wid-35 text-center" type="hidden" id="qntno<?php echo $result2["code"];?>" value="<?php echo $sessqty;?>">                               
                              
                                </div>
                            </div>

                            
                            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">

   <input type="hidden" id="pid<?php echo $result2["id"];?>" value="<?php echo $result2["id"];?>">
  
    <input type="hidden" id="code<?php echo $result2["id"];?>" value="<?php echo $result2['code'];?>">
     <input type="hidden" id="prd_price<?php echo $result2["id"];?>" value="<?php echo $result2['MinPrice'];?>"> 

                            <?php } $i++;} 
}


if($_POST['action'] == 'sendSms'){
    $id = $_POST['id'];
         $sql = "SELECT * FROM tbl_customer_invoice WHERE id='$id'";
    $row = getRecord($sql);
    $BillNo = $row['BillNo'];
    $NetAmount = $row['NetAmount'];
    $InvoiceDate2 = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));
    $CreatedTime = $row['CreatedTime'];
    $CellNo = $row['CellNo'];
    $smstxt = "POS Receipt: https://kwickfoods.in/in/invoice/$BillNo 
Thank you for purchasing with MAHACHAI.(www.mahachai.in) 
Total Amount $NetAmount $InvoiceDate2 $CreatedTime
Your Feedback Matters : https://kwickfoods.in/feedback";
$Phone = $CellNo;
if($Phone!=''){
    $dltentityid = "1501701120000037351";
  $dlttempid = "1707169839028393861";
       include '../../incsmsapi.php';
}
}


if($_POST['action'] == 'countCart'){
echo count($_SESSION["cart_item"]);

    }
 
 if($_POST['action'] == 'showPendingOrders'){
    $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND Status=0 AND FrId='$BillSoftFrId'";
   /* if($Roll == 1){
        $sql.=" AND FrId='$BillSoftFrId'";
    }
    else{
        $sql.=" AND CreatedBy='$user_id'";
    }*/
    $sql.=" ORDER BY InvoiceDate DESC";
    $row = getList($sql);
    foreach($row as $result){
 ?>
      <div class="col-lg-4" style="padding-bottom: 10px;">
                                            <a  href="javascript:void(0)" onclick="editOrder('<?php echo $result['id'];?>')" class="btn btn-danger  mt-md-0 mt-2" style="width: 95px;">
                                                                <?php echo $result['InvoiceNo'];?>
                                                            </a>
                                        </div>
 <?php } } 
    
    
    
    if($_POST['action'] == 'updatePrint'){ 
    $CellNo = addslashes(trim($_POST['CellNo']));
    $CustId2 = $_POST['CustId'];
    $CustName = addslashes(trim($_POST['CustName']));
    $AccCode = addslashes(trim($_POST['AccCode']));
    $PayType = addslashes(trim($_POST['PayType']));
    $Featured = addslashes(trim($_POST['Featured']));
    $Print = addslashes(trim($_POST['Print']));
    $InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
    $BillNo = addslashes(trim($_POST['BillNo']));
    $OrderNo = addslashes(trim($_POST['OrderNo']));
    $InvId = addslashes(trim($_POST['InvId']));
    $MaxId = addslashes(trim($_POST['SrNo']));
    $value = $_POST['value'];
    foreach ($_SESSION["cart_item"] as $product){
      $total_price3 += ($product["price"]*$product["quantity"]);
    }
    $InvoiceDate = date('Y-m-d');
    $InvoiceDate2 = date('d/m/Y');
    $CreatedTime = date('H:i:s');
    $SubTotal = $total_price3;
    $Discount = 0;
   

    if($_POST['PackageId'] == 0){
        $PkgId = addslashes(trim($_POST['PkgId']));
    }
    else{
        $PkgId = addslashes(trim($_POST['PackageId'])); 
    }
              
     $PkgAmt = addslashes(trim($_POST['PkgAmt']));
    $PkgDiscount = addslashes(trim($_POST['PkgDiscount']));
    $PkgValidity = addslashes(trim($_POST['PkgValidity']));
    $PrimeDiscount = addslashes(trim($_POST['PrimeDiscount']));
    $NetAmount = $total_price3-$PrimeDiscount;
    $Advance = $total_price3-$PrimeDiscount;

    $Balance = 0;
    $tempDir = '../../barcodes/';
   /* $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2";
    $row8 = getRecord($sql8);
    $MaxId = $row8['MaxId'] + 1;
    $InvoiceNo = "00" . $MaxId;
    
    $sql81 = "SELECT MAX(OrderNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='$InvoiceDate'";
    $row81 = getRecord($sql81);
    $MaxId = $row81['MaxId'] + 1;
    $OrderNo = $MaxId;*/
    
    $sql = "DELETE FROM tbl_customer_invoice WHERE id='$InvId'";
    $conn->query($sql);
    $sql = "DELETE FROM tbl_customer_invoice_details WHERE InvId='$InvId'";
    $conn->query($sql);
    if($CustId2==0){

      if($CustName!='' && $CellNo!=''){
        $sql = "INSERT INTO tbl_users SET Fname='$CustName',Phone='$CellNo',EmailId='$EmailId',Address='$Address',UnderFr='0',CreatedBy='$user_id',CreatedDate='$CreatedDate',Roll=55,Status=1";
        $conn->query($sql);
        $CustId = mysqli_insert_id($conn);

        $filename = $CustId.".png";
        $codeContents = $CellNo;
        QRcode::png($codeContents, $tempDir.''.$filename, QR_ECLEVEL_L, 5);
        $Barcode = $filename;
        $CustomerId = "C".$CustId;
        $sql3 = "UPDATE tbl_users SET Barcode='$Barcode',CustomerId='$CustomerId' WHERE id='$CustId'";
        $conn->query($sql3);  
         if($PkgId!=0){
                            $sql3 = "UPDATE tbl_users SET PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',Prime=1 WHERE id='$CustId'";
                        $conn->query($sql3);
                        }
    }
    
    else{
                            $CustId = 0;
                        }
    
    
    }
    else{
          $CustId = $_POST['CustId'];
          if($PkgId!=0){
                            $sql3 = "UPDATE tbl_users SET PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',Prime=1 WHERE id='$CustId'";
                        $conn->query($sql3);
                        }
    }
    $sql = "INSERT INTO tbl_customer_invoice SET AccCode='$AccCode',SrNo='$MaxId',CustId='$CustId',CustName='$CustName',
                            CellNo='$CellNo',Address='$Address',EmailId='$EmailId',InvoiceNo='$InvoiceNo',
                            InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',
                            CgstPer='$CgstPer',SgstPer='$SgstPer',
                            IgstPer='$IgstPer',SubTotal='$SubTotal',
                            GstAmt='$GstAmt',CreatedBy='$user_id',CreatedDate='$CreatedDate',
                            ExtraAmount='$ExtraAmount',TotalAmount='$TotalAmount',Discount='$Discount',
                            NetAmount='$NetAmount',Advance='$Advance',Balance='$Balance',
                            ExtraReason='$ExtraReason',ChequeNo='$ChequeNo',ChqDate='$ChqDate',
                            BankName='$BankName',UpiNo='$UpiNo',PayType2='$PayType2',
                            ChequeNo2='$ChequeNo2',ChqDate2='$ChqDate2',
                            BankName2='$BankName2',UpiNo2='$UpiNo2',Amount1='$Amount1',Amount2='$Amount2',Status='$Featured',Roll=2,PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',PrimeDiscount='$PrimeDiscount',OrderNo='$OrderNo',CreatedTime='$CreatedTime',FrId='$BillSoftFrId'";
                    $conn->query($sql);
                    $SellId = mysqli_insert_id($conn);
                    $filename = $SellId . ".png";
                    $randno = rand(1000,9999);
                    $BillNo = $randno."".$SellId;
                    $sql3 = "UPDATE tbl_customer_invoice SET Barcode='$Barcode',BillNo='$BillNo',id='$SellId' WHERE Unqid='$SellId'";
                    $conn->query($sql3);


                    $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
                    $row2 = getRecord($sql2);
                    if ($row2['maxid'] == '') {
                        $SrNo = 1;
                        $Code = "PR" . $SrNo;
                    } else {
                        $SrNo = $row2['maxid'] + 1;
                        $Code = "PR" . $SrNo;
                    }

                    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='OB',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$NetAmount',CrDr='dr',Roll=5,
                            Type='OB',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='Total Invoice Amount',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',Main=1,CreatedBy='$user_id',BillSoftFrId='$BillSoftFrId'";
                    $conn->query($sql4);
                    if ($Advance > 0) {
                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='cr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',Main=1,CreatedBy='$user_id',BillSoftFrId='$BillSoftFrId'";
                        $conn->query($sql4);
                        $PostId = mysqli_insert_id($conn);

                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='0',
                            AccCode='AC1',
                            AccountName='Comapny Account',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='dr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Company',PostId='$PostId',Main=1,CreatedBy='$user_id',BillSoftFrId='$BillSoftFrId'";
                        $conn->query($sql4);
                    }

 foreach ($_SESSION["cart_item"] as $product){
                        
                     $ProdCode = $product["code"];
                     $sql = "SELECT * FROM tbl_cust_products WHERE code='$ProdCode'";
                     $row = getRecord($sql);
                     $ActPrice =  $product["price"];
                     $ProdPrice = $row['ProdPrice'];
                     $Qty = $product["quantity"];
                     $TotalPrice = $ProdPrice*$Qty;
                    //  $CgstAmt = $TotalPrice*($row['CgstPer']/100);
                    //  $SgstAmt = $TotalPrice*($row['SgstPer']/100);
                    //  $IgstAmt = $TotalPrice*($row['IgstPer']/100);
                     $CgstAmt = $row['CgstAmt']*$Qty;
                     $SgstAmt = $row['SgstAmt']*$Qty;
                     $IgstAmt = $row['IgstAmt']*$Qty;
                     $CgstPer = $row['CgstPer'];
                     $SgstPer = $row['SgstPer'];
                     $IgstPer = $row['IgstPer'];
                     $GstAmt = $CgstAmt+$SgstAmt+$IgstAmt;
                     $Total = $GstAmt+$TotalPrice;
                     $ProdId = $row['id'];
                     
                     $sql22 = "INSERT INTO tbl_customer_invoice_details SET ServerInvId='$SellId',InvId='$SellId',ProdId='$ProdId',Qty='$Qty',Price='$ProdPrice',CreatedDate='$InvoiceDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',Total='$Total',CustProd=1,CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ActPrice='$ActPrice'";
                    $conn->query($sql22);
                    $InvDetId = mysqli_insert_id($conn);
                    $sql33 = "UPDATE tbl_customer_invoice_details SET id='$InvDetId' WHERE Unqid='$InvDetId'";
                    $conn->query($sql33);
       }
       unset($_SESSION["cart_item"]);
       
       if($Featured == 1){
        $smstxt = "POS Receipt: https://kwickfoods.in/in/invoice/$BillNo 
Thank you for purchasing with MAHACHAI.(www.mahachai.in) 
Total Amount $NetAmount $InvoiceDate2 $CreatedTime
Your Feedback Matters : https://kwickfoods.in/feedback";
$Phone = $CellNo;
if($Phone!=''){
    $dltentityid = "1501701120000037351";
  $dlttempid = "1707169839028393861";
       include '../../incsmsapi.php';
}
}
       $sql55 = "SELECT * FROM tbl_users_bill WHERE id='$BillSoftFrId'";
            $row55 = getRecord($sql55);
            $title = str_replace(" ","#",$row55['PrintCompName']);
            $Address = str_replace(" ","#",$row55['Address']);
            $Phone = str_replace(" ","#",$row55['PrintMobNo']);
            $GstNo = str_replace(" ","#",$row55['GstNo']);
            //$terms_condition = str_replace(" ","#",$row55['terms_condition']);
            $bottom_title = str_replace(" ","#",$row55['bottom_title']);
           // $bottom_title.="#".date('h:i a');
            $sql = "SELECT * FROM tbl_customer_invoice WHERE id='$SellId'";
    $row = getRecord($sql);
    $CustName = str_replace(" ","#",$row['CustName']);
        $emparray = array();
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='".$row['id']."'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName FROM  tbl_cust_products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=number_format((float)$result12['GstAmt'], 2, '.', '');
                //$TotGst+=round($result12['GstAmt'],2);
                $Product_name2 = str_replace(" ","#",$row13['ProductName']);
                $Product_name = substr($Product_name2,0,16);
                 $emparray[] = array('item'=>$Product_name,'rate'=>$result12['Price'],'quantity'=>$result12['Qty'],'amount'=>$result12['Total']);
            }
            $terms_condition = "Time:".$row['CreatedTime']."#|Pay#Mode:#".$row['PayType'];
    $dados2 =  json_encode(array('productList' => $emparray));
    $date = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));
    $dados1 = json_encode(array('title'=>$title,'address'=>$Address,'mobile'=>$Phone,'gstin'=>$GstNo,'customer_name'=>$CustName,'customer_phone_no'=>$row['CellNo'],'receipt_title'=>'Retail#Receipt','invoice_no'=>$row['InvoiceNo'],'date'=>$date,'sub_total'=>$row['SubTotal'],'discount'=>$row['Discount'],'service_charge'=>0,'gst'=>$TotGst,'total_bill'=>$row['NetAmount'],'total_payable'=>$row['NetAmount'],'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title)); 
    $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
     if($Print == 1){
    echo $invoice_data;
     }
     else{
         echo 1;
     }
   //echo 1;    
}

if($_POST['action'] == 'getProdDetails'){
    $BarcodeNo = addslashes(trim($_POST['BarcodeNo']));
    $sql = "SELECT * FROM tbl_cust_products WHERE BarcodeNo='$BarcodeNo'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        $row = getRecord($sql);
        echo json_encode($row);
    }
    else{
        echo 0;
    }
}


if($_POST['action'] == 'searchProduct'){
    $search = mysqli_real_escape_string($conn, $_POST["query"]);
    ?>
    <div class="gallery-sizer col-sm-6 col-md-6 col-3 col-xl-3 position-absolute"></div>
                        
                            <?php 
                                $i=1;
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 AND CreatedBy IN (0,$BillSoftFrId) ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                    $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' AND (ProductName LIKE '%".$search."%') AND CreatedBy='$BillSoftFrId' ORDER BY SrNo asc ";
                                    $row2 = getList($sql2);
                                    foreach($row2 as $result2){
                                        $code = $result2['code'];
                                        $sessqty2 =  $_SESSION["cart_item"][$code]['quantity'];
                                        if($sessqty2 == ''){
                                            $sessqty = 0;
                                        }
                                        else{
                                            $sessqty = $sessqty2;
                                        }
                                        
                                        $sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock` WHERE ProdId='".$result2['id']."' GROUP by Status) as a";
                                    	$row2 = getRecord($sql2);
                                    	if($row2['balqty'] > 0){
                                    	$stockqty = $row2['balqty'];
                                    	}
                                    	else{
                                    	    $stockqty = 0;
                                    	}
                            ?>
                            <div align="center" class="gallery-thumbnail col-sm-4 col-md-6  col-4 col-xl-3 " data-tag="cat<?php echo $result['id'];?>" style="border:1px solid #e6e6e6;margine-right:10px;padding-top: 5px;border-radius:10px;" >
                       
                                <a href="javascript:void(0)" onclick="addCart(<?php echo $result2['id'];?>)">
                                    <div align="center" style="padding:6px;">
                                <strong style="text-align:center;font-size: 12px;letter-spacing: 0.5px;color:#000;padding:5px;"><?php echo $result2['ProductName'];?></strong><br>
                                <span style="font-size: 10px;color: black;">Stock : <?php echo $stockqty;?></span><br>
                                <strong style="font-size:17px;color:#f06721;">&#8377; <?php echo number_format($result2['MinPrice'],2); ?></strong>
                          
                                                                
                             <input class="wid-35 text-center" type="hidden" id="qntno<?php echo $result2["code"];?>" value="<?php echo $sessqty;?>">                       
                                </div> </a>
                            </div>

                            
                            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">

   <input type="hidden" id="pid<?php echo $result2["id"];?>" value="<?php echo $result2["id"];?>">
  
    <input type="hidden" id="code<?php echo $result2["id"];?>" value="<?php echo $result2['code'];?>">
     <input type="hidden" id="prd_price<?php echo $result2["id"];?>" value="<?php echo $result2['MinPrice'];?>"> 

                            <?php } $i++;} ?>
<?php } 

 if($_POST['action'] == 'showRecentOrders'){
    
 ?>
      <div class="col-xl-12 col-md-12">
                                        <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Amount</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND InvoiceDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId'";
    $sql.=" ORDER BY id DESC LIMIT 3";
    //echo $sql;
    $row = getList($sql);
    foreach($row as $result){?>
                                                <tr>
                                                    <td><?php echo $result['InvoiceNo']; ?></td>
                                                    <td>&#8377; <?php echo number_format($result['NetAmount'],2); ?></td>
                                                   
                                                </tr>
                                                <?php } ?>
                                                
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                
                            </div>
 <?php } 
 
 if($_POST['action'] == 'changeQrStatus'){ 
    $id = $_POST['id'];
    $status = $_POST['status'];
    $sql = "UPDATE tbl_cust_products SET QrDisplay='$status' WHERE id='$id'";
    $conn->query($sql);
    echo "status updated successfully!";
}
?>
