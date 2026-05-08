<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include('../libs/phpqrcode/qrlib.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Invoice";
$Page = "Add-Customer-Invoice"; 

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
    <script src="../ckeditor/ckeditor.js"></script>
</head>

<body>
    <style type="text/css">
        .password-tog-info {
            display: inline-block;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            position: absolute;
            right: 50px;
            top: 30px;
            text-transform: uppercase;
            z-index: 2;
        }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>
                <?php
                foreach ($_SESSION["cart_item"] as $product){
      $total_price3 += ($product["price"]*$product["quantity"]);
       }
                $id = $_GET['id'];
                $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2";
                $row8 = getRecord($sql8);
                $MaxId = $row8['MaxId'] + 1;
                $Invoice_No = "00" . $MaxId;

                if (isset($_POST['submit'])) {
                    
                    $CustId2 = $_POST['CustId'];
                    $AccCode = addslashes(trim($_POST['AccCode']));
                    $CustName = addslashes(trim($_POST['CustName']));
                    $CellNo = addslashes(trim($_POST['CellNo']));
                    $Address = addslashes(trim($_POST['Address']));
                    $EmailId = addslashes(trim($_POST['EmailId']));
                    $InvoiceDate = addslashes(trim($_POST['InvoiceDate']));
                    $PayType = addslashes(trim($_POST['PayType']));
                    $Narration = addslashes(trim($_POST['Narration']));


                    $SubTotal = addslashes(trim($_POST['SubTotal']));
                    //$SgstPer = addslashes(trim($_POST['SgstPer']));
                    //$CgstPer = addslashes(trim($_POST['CgstPer']));
                    //$IgstPer = addslashes(trim($_POST['IgstPer']));
                    //$GstAmt = addslashes(trim($_POST['GstAmt']));
                    //$ExtraAmount = addslashes(trim($_POST['ExtraAmount']));
                    //$TotalAmount = addslashes(trim($_POST['TotalAmount']));
                    $Discount = addslashes(trim($_POST['Discount']));
                    $NetAmount = addslashes(trim($_POST['NetAmount']));
                    $Advance = addslashes(trim($_POST['Advance']));
                    $Balance = addslashes(trim($_POST['Balance']));
                    //$ExtraReason = addslashes(trim($_POST['ExtraReason']));
                    $PayType = addslashes(trim($_POST['PayType']));
                    $ChequeNo = addslashes(trim($_POST['ChequeNo']));
                    $ChqDate = addslashes(trim($_POST['ChqDate']));
                    $BankName = addslashes(trim($_POST['BankName']));
                    $UpiNo = addslashes(trim($_POST['UpiNo']));
                    $PayType2 = addslashes(trim($_POST['PayType2']));
                    $ChequeNo2 = addslashes(trim($_POST['ChequeNo2']));
                    $ChqDate2 = addslashes(trim($_POST['ChqDate2']));
                    $BankName2 = addslashes(trim($_POST['BankName2']));
                    $UpiNo2 = addslashes(trim($_POST['UpiNo2']));
                    $Amount1 = addslashes(trim($_POST['Amount1']));
                    $Amount2 = addslashes(trim($_POST['Amount2']));
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
                    $CreatedDate = date('Y-m-d');
                    //$CreatedTime = date('h:i a');
                    $Status = $_POST['Status'];
                    $InvoiceDate2 = date('d/m/Y');
                    $CreatedTime = date('H:i:s');
                    $tempDir = '../barcodes/';
                    $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2";
                    $row8 = getRecord($sql8);
                    $MaxId = $row8['MaxId'] + 1;
                    $InvoiceNo = "00" . $MaxId;
                    
                    $sql81 = "SELECT MAX(OrderNo) AS MaxId FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='$InvoiceDate'";
                    $row81 = getRecord($sql81);
                    $MaxId = $row81['MaxId'] + 1;
                    $OrderNo = $MaxId;
                    
                    $tempDir = '../barcodes/'; 

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
                            BankName2='$BankName2',UpiNo2='$UpiNo2',Amount1='$Amount1',Amount2='$Amount2',Status='$Status',Roll=2,PkgId='$PkgId',PkgAmt='$PkgAmt',PkgDiscount='$PkgDiscount',PkgDate='$CreatedDate',PkgValidity='$PkgValidity',PrimeDiscount='$PrimeDiscount',OrderNo='$OrderNo',CreatedTime='$CreatedTime'";
                    $conn->query($sql);
                    $SellId = mysqli_insert_id($conn);
                    $filename = $SellId . ".png";
                    //$codeContents = "https://digityservices.com/suchak_travel/admin/invoice.php?id=$SellId";
                    //QRcode::png($codeContents, $tempDir . '' . $filename, QR_ECLEVEL_L, 5);
                    //$Barcode = $filename;
                    $sql3 = "UPDATE tbl_customer_invoice SET Barcode='$Barcode' WHERE id='$SellId'";
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
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',Main=1,CreatedBy='$user_id'";
                    $conn->query($sql4);
                    if ($Advance > 0) {
                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='cr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',Main=1,CreatedBy='$user_id'";
                        $conn->query($sql4);
                        $PostId = mysqli_insert_id($conn);

                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='0',
                            AccCode='AC1',
                            AccountName='Comapny Account',
                            InvoiceNo='$InvoiceNo',Amount='$Advance',CrDr='dr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$InvoiceDate',
                            PayMode='$PayType',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Company',PostId='$PostId',Main=1,CreatedBy='$user_id'";
                        $conn->query($sql4);
                    }

 foreach ($_SESSION["cart_item"] as $product){
                        
                     $ProdCode = $product["code"];
                     $sql = "SELECT * FROM tbl_cust_products WHERE code='$ProdCode'";
                     $row = getRecord($sql);
                     $CatId = $row['CatId'];
                     $ProdPrice = $row['ProdPrice'];
                     $Qty = $product["quantity"];
                     $TotalPrice = $ProdPrice*$Qty;
                     $CgstAmt = $TotalPrice*($row['CgstPer']/100);
                     $SgstAmt = $TotalPrice*($row['SgstPer']/100);
                     $IgstAmt = $TotalPrice*($row['IgstPer']/100);
                     $CgstPer = $row['CgstPer'];
                     $SgstPer = $row['SgstPer'];
                     $IgstPer = $row['IgstPer'];
                     $GstAmt = $CgstAmt+$SgstAmt+$IgstAmt;
                     $Total = $GstAmt+$TotalPrice;
                     $ProdId = $row['id'];
                     
                     $sql22 = "INSERT INTO tbl_customer_invoice_details SET InvId='$SellId',ProdId='$ProdId',Qty='$Qty',Price='$ProdPrice',CreatedDate='$InvoiceDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',Total='$Total',CustProd=1,CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',CatId='$$CatId'";
                    $conn->query($sql22);
                    $InvDetId = mysqli_insert_id($conn);
       }
       
        $smstxt = "POS Receipt: https://mahabuddy.com/in/invoice/$SellId 
Thank you for purchasing with MAHACHAI.(www.mahachai.in) 
Total Amount $NetAmount 
Your Feedback Matters : https://mahabuddy.com/feedback";
$Phone = $CellNo;
if($Phone!=''){
    $dltentityid = "1501701120000037351";
  $dlttempid = "1707169839028393861";
       include '../incsmsapi.php';
}
       
                    /*$number = count($_POST["ProdId"]);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["ProdId"][$i] != '')) {
                                $ProdId = addslashes(trim($_POST['ProdId'][$i]));
                              
                                $Qty = addslashes(trim($_POST['Qty'][$i]));
                                $Price = addslashes(trim($_POST['Price'][$i]));
                               
                                $CgstPer = addslashes(trim($_POST['CgstPer'][$i]));
                                $SgstPer = addslashes(trim($_POST['SgstPer'][$i]));
                                $IgstPer = addslashes(trim($_POST['IgstPer'][$i]));
                                $GstAmt = addslashes(trim($_POST['GstAmt'][$i]));
                                $Total = addslashes(trim($_POST['Total'][$i]));

                                $sql22 = "INSERT INTO tbl_customer_invoice_details SET InvId='$SellId',      ProdId='$ProdId',Qty='$Qty',Price='$Price',CreatedDate='$InvoiceDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',Total='$Total'";
                                $conn->query($sql22);
                                $InvDetId = mysqli_insert_id($conn);

                                $sql22 = "INSERT INTO tbl_product_stocks SET InvDetId='$InvDetId',InvId='$SellId',ProdId='$ProdId',Qty='$Qty',Price='$Price',StockDate='$InvoiceDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',Total='$Total',CrDr='cr',Roll='Customer-Stock',UserId='$CustId'";
                                $conn->query($sql22);

                                 $sql22 = "INSERT INTO tbl_product_stocks SET InvDetId='$InvDetId',InvId='$SellId',ProdId='$ProdId',Qty='$Qty',Price='$Price',StockDate='$InvoiceDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',Total='$Total',CrDr='dr',Roll='Company-Stock',UserId='$CustId'";
                                $conn->query($sql22);
                            }
                        }
                    }*/
unset($_SESSION["cart_item"]);



$sql55 = "SELECT * FROM tbl_users WHERE id=5";
            $row55 = getRecord($sql55);
            $title = str_replace(" ","#",$row55['ShopName']);
            $Address = str_replace(" ","#",$row55['Address']);
            $Phone = str_replace(" ","#",$row55['Phone']);
            $GstNo = str_replace(" ","#",$row55['GstNo']);
            $terms_condition = str_replace(" ","#",$row55['terms_condition']);
            $bottom_title = str_replace(" ","#",$row55['bottom_title']);
            
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
                $TotGst+=$result12['GstAmt'];
                $Product_name = str_replace(" ","#",$row13['ProductName']);
                 $emparray[] = array('item'=>$Product_name,'rate'=>$result12['Price'],'quantity'=>$result12['Qty'],'amount'=>$result12['Total']);
            }
    $dados2 =  json_encode(array('productList' => $emparray));
    $date = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));
    $dados1 = json_encode(array('title'=>$title,'address'=>$Address,'mobile'=>$Phone,'gstin'=>$GstNo,'customer_name'=>$CustName,'customer_phone_no'=>$row['CellNo'],'receipt_title'=>'Retail#Receipt','invoice_no'=>$row['InvoiceNo'],'date'=>$date,'sub_total'=>$row['SubTotal'],'discount'=>$row['Discount'],'service_charge'=>0,'gst'=>$TotGst,'total_bill'=>$row['NetAmount'],'total_payable'=>$row['NetAmount'],'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title)); 
    $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
?>
<script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "Invoice Created Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
      Android.printReceipt(''<?php echo $invoice_data;?>'');
          window.location.href="orders.php";
  }
}); });</script>
                    <!--echo "<script>alert('Invoice Created Successfully!');window.location.href='customer-invoice.php?id=$SellId&header=1&footer=1&logo=1';</script>";-->
                <?php }
                ?>
                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Create Customer Invoice</h4>


                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" enctype="multipart/form-data">

                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <input type="hidden" name="CurrDate" value="<?php echo date('Y-m-d');?>" id="CurrDate">
                                    <div class="form-row">
                                        <!--<div class="form-group col-md-12">
                                            <label class="form-label">Customer </label>
                                            <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="CustId" id="CustId">
                                               <option value="0">Search Customer</option>
                                                <?php
                                                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=55";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                ?>
                                                    <option <?php if ($row7["UserId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname'] . " - " . $result['Phone']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>-->
                                        
                                        <input type="hidden" name="AccCode" id="AccCode">
                                        <input type="hidden" name="CustId" id="CustId">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control" placeholder="" value="<?php echo $row7["CellNo"]; ?>" autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" id="CustName" class="form-control" placeholder="" value="<?php echo $row7["CustName"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control" placeholder="" value="<?php echo $row7["CustName"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address</label>
                                            <textarea name="Address" id="Address" class="form-control"><?php echo $row7['Address']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Invoice No </label>
                                            <input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Invoice Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control" placeholder="" value="<?php echo date('Y-m-d'); ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Package </label>
                                            <select class="form-control" style="width: 100%" data-allow-clear="true" name="PkgId" id="PkgId">
                                               <option value="0">No Package</option>
                                                <?php
                                                $sql4 = "SELECT * FROM tbl_packages WHERE Status=1";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                    if($result['Period'] == 1){
                                                        $Duration = $result['Duration']." Month";
                                                    }
                                                    else{
                                                      $Duration = $result['Duration']." Year";  
                                                    }
                                                ?>
                                                    <option <?php if ($row7["PkgId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']." ( ₹".$result['Amount']." ) - ".$Duration; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <input type="hidden" value="" id="PackageId" name="PackageId">
                                          <div class="form-group col-md-2">
                                            <label class="form-label">Amount </label>
                                            <input type="text" name="PkgAmt" id="PkgAmt" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-2">
                                            <label class="form-label">Prime Discount % </label>
                                            <input type="text" name="PkgDiscount" id="PkgDiscount" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-2">
                                            <label class="form-label">Valid Upto  </label>
                                            <input type="date" name="PkgValidity" id="PkgValidity" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                       

                                    </div>

                                   
                                        <!--<div class="form-row">
                                            <div class="form-group col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                         <div id="dynamic_field">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Product </label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="ProdId[]" id="ProdId1" onchange="getPrice(this.value,document.getElementById('srno1').value)">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM products WHERE Status=1 AND ProdFor IN (1,3)";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($row7["TravelId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                           

                                                          
                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Price </label>
                                                                <input type="text" name="Price[]" id="Price1" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off"
                                                               readonly>
                                                            </div>
                                                            <input type="hidden" id="OrgPrice1">
                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Avail Stock </label>
                                                                <input type="text" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off"
                                                                readonly>
                                                            </div>

                                                             <div class="form-group col-md-1">
                                                                <label class="form-label">Qty </label>
                                                                <input type="text" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,S12" value="1" autocomplete="off"
                                                                oninput="TotAmount(document.getElementById('Price1').value,
                                                                document.getElementById('Qty1').value,
                                                                document.getElementById('SgstPer1').value,
                                                                document.getElementById('CgstPer1').value,
                                                                document.getElementById('IgstPer1').value,
                                                                document.getElementById('srno1').value)">
                                                            </div>

                                                          
                                                             <div class="form-group col-md-1">
                                                                <label class="form-label">CGST %</label>
                                                                <input type="text" name="CgstPer[]" id="CgstPer1" class="form-control" placeholder="" value="0" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price1').value,
                                                                document.getElementById('Qty1').value,
                                                                document.getElementById('SgstPer1').value,
                                                                document.getElementById('CgstPer1').value,
                                                                document.getElementById('IgstPer1').value,
                                                                document.getElementById('srno1').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">SGST %</label>
                                                                <input type="text" name="SgstPer[]" id="SgstPer1" class="form-control" placeholder="" value="0" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price1').value,
                                                                document.getElementById('Qty1').value,
                                                                document.getElementById('SgstPer1').value,
                                                                document.getElementById('CgstPer1').value,
                                                                document.getElementById('IgstPer1').value,
                                                                document.getElementById('srno1').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">IGST %</label>
                                                                <input type="text" name="IgstPer[]" id="IgstPer1" class="form-control" placeholder="" value="0" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price1').value,
                                                                document.getElementById('Qty1').value,
                                                                document.getElementById('SgstPer1').value,
                                                                document.getElementById('CgstPer1').value,
                                                                document.getElementById('IgstPer1').value,
                                                                document.getElementById('srno1').value)">
                                                            </div>


                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">GST Amt</label>
                                                                <input type="text" name="GstAmt[]" id="GstAmt1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                                            </div> 

                                                            <input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">
                                                            <input type="hidden" class="form-control" name="rncnt" id="rncnt" value="1">
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Total[]" id="Total1" class="form-control txt" placeholder="e.g.,5000" value="" autocomplete="off" required readonly>
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                        <button class="btn btn-secondary" type="button" id="add_more"><i class="fa fa-plus"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->



                                    <div class="form-row">
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Sub Total <span class="text-danger">*</span></label>
                                            <input type="text" name="SubTotal" id="SubTotal" class="form-control" placeholder="" value="<?php echo $total_price3; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                      <div class="form-group col-md-1">
                                            <label class="form-label">Pkg Amount </label>
                                            <input type="text" name="Pkg_Amount" id="Pkg_Amount" class="form-control" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                 <div class="form-group col-md-2">
                                            <label class="form-label">Prime Discount </label>
                                            <input type="text" name="PrimeDiscount" id="PrimeDiscount" class="form-control" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-1">
                                            <label class="form-label">Discount <span class="text-danger">*</span></label>
                                            <input type="text" name="Discount" id="Discount" class="form-control" placeholder="" value="0" onKeyPress="return isNumberKey(event)"
                                             oninput="balAmount(document.getElementById('SubTotal').value,
                                             document.getElementById('Discount').value,
                                             document.getElementById('NetAmount').value,
                                             document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Net Payable<span class="text-danger">*</span></label>
                                            <input type="text" name="NetAmount" id="NetAmount" class="form-control" placeholder="" value="<?php echo $total_price3; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="Advance" id="Advance" class="form-control" placeholder="" value="<?php echo $total_price3; ?>" onKeyPress="return isNumberKey(event)" 
                                            oninput="balAmount(document.getElementById('SubTotal').value,
                                             document.getElementById('Discount').value,
                                             document.getElementById('NetAmount').value,
                                             document.getElementById('Advance').value)">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Balance <span class="text-danger">*</span></label>
                                            <input type="text" name="Balance" id="Balance" class="form-control" placeholder="" value="0" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <!--<div class="form-group col-md-12">
                                            <label class="form-label">Extra Charges Reason </label>
                                            <input type="text" name="ExtraReason" class="form-control" placeholder="" value="<?php echo $result["ExtraReason"]; ?>" autocomplete="off">
                                        </div>-->

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
                                                <option disabled="" value="">Select Payment Type</option>
                                                <option selected="" value="Cash">Cash</option>
                                                <option <?php if ($row7['PayType'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($row7['PayType'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($row7['PayType'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($row7['PayType'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-1">
                                            <label class="form-label">Amount </label>
                                            <input type="text" name="Amount1" class="form-control" id="Amount1" placeholder="" value="<?php echo $total_price3; ?>">
                                            <div class="clearfix"></div>
                                        </div>                             


                                        <!--<div class="form-group col-lg-2">
                                            <label class="form-label">Cheque No </label>
                                            <input type="text" name="ChequeNo" class="form-control" id="ChequeNo" placeholder="Cheque No" value="<?php echo $row7['ChequeNo']; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Cheque Date </label>
                                            <input type="date" name="ChqDate" class="form-control" id="ChqDate" placeholder="Cheque Date" value="<?php echo $row7['ChqDate']; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Bank Name </label>
                                            <input type="text" name="BankName" class="form-control" id="BankName" placeholder="Bank Name" value="<?php echo $row7['BankName']; ?>">
                                            <div class="clearfix"></div>

                                        </div>



                                        <div class="form-group col-lg-2">
                                            <label class="form-label">UPI No/ Transaction Id </label>
                                            <input type="text" name="UpiNo" class="form-control" id="UpiNo" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo']; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->

<div class="form-group col-lg-2">
                                            <label class="form-label">Payment Type 2</label>
                                            <select class="form-control" id="PayType2" name="PayType2" onchange="getPayType(this.value);">
                                                <option selected="" disabled="" value="">Select Payment Type</option>
                                                <option selected="" value="Cash">Cash</option>
                                                <option <?php if ($row7['PayType2'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($row7['PayType2'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($row7['PayType2'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($row7['PayType2'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-1">
                                            <label class="form-label">Amount </label>
                                            <input type="text" name="Amount2" class="form-control" id="Amount2" placeholder="" value="<?php echo $row7['Amount2']; ?>"oninput="calAmt1()">
                                            <div class="clearfix"></div>
                                        </div> 
                                        
                                         <div class="form-group col-lg-6">
                                            <label class="form-label">Narration <span class="text-danger">*</span></label>
                                            <input type="text" name="Narration" class="form-control" id="Narration" placeholder="Narration" value="<?php echo $row7['Narration']; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                    </div>

                                    <!--<div class="form-row">
                                                                        


                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Cheque No </label>
                                            <input type="text" name="ChequeNo2" class="form-control" id="ChequeNo2" placeholder="Cheque No" value="<?php echo $row7['ChequeNo2']; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Cheque Date </label>
                                            <input type="date" name="ChqDate2" class="form-control" id="ChqDate2" placeholder="Cheque Date" value="<?php echo $row7['ChqDate2']; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label class="form-label">Bank Name </label>
                                            <input type="text" name="BankName2" class="form-control" id="BankName2" placeholder="Bank Name" value="<?php echo $row7['BankName2']; ?>">
                                            <div class="clearfix"></div>

                                        </div>



                                        <div class="form-group col-lg-2">
                                            <label class="form-label">UPI No/ Transaction Id </label>
                                            <input type="text" name="UpiNo2" class="form-control" id="UpiNo2" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo2']; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                    </div>-->

                                    <div class="form-row">
                                       
                                    </div>
                                    
                                    <input type="hidden" id="Status" name="Status" value="1">
                                    <!-- <div class="form-row">
                                   <div class="form-group col">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Pending</option>
</select>
<div class="clearfix"></div>
</div>
</div> -->

                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Save and Print</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php include_once 'footer.php'; ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>
    <script>
        function TotAmount(Price,Qty,CgstPer,SgstPer,IgstPer,Srno){
            var CgstAmt = Number(Price) * (Number(CgstPer) / 100);
            var SgstAmt = Number(Price) * (Number(SgstPer) / 100);
            var IgstAmt = Number(Price) * (Number(IgstPer) / 100);
            var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
           $('#GstAmt'+Srno).val(parseFloat(GstAmt).toFixed(2));
            var Total_Amount = (Number(Price) + Number(GstAmt)) * Number(Qty);
            $('#Total'+Srno).val(parseFloat(Total_Amount).toFixed(2));
            getSubTotal();
        }
        function calAge(val, srno) {
            //alert(val);alert(srno);
            var dob = new Date(val);
            //calculate month difference from current date in time  
            var month_diff = Date.now() - dob.getTime();

            //convert the calculated difference in date format  
            var age_dt = new Date(month_diff);

            //extract year from date      
            var year = age_dt.getUTCFullYear();

            //now calculate the age of the user  
            var age = Math.abs(year - 1970);

            $('#Age' + srno).val(age);
            //alert(age);
        }

        // function getPayType(val) {
        //     if (val == 'Cheque') {
        //         $('#chequeoption').show();
        //         $('#upioption').hide();
        //     } else if (val == 'UPI') {
        //         $('#chequeoption').hide();
        //         $('#upioption').show();
        //     } else {
        //         $('#chequeoption').hide();
        //         $('#upioption').hide();
        //     }
        // }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        function getSubTotal() {
            /*var sum = 0;
            $(".txt").each(function() {
                if (!isNaN(this.value) && this.value.length != 0) {
                    sum += parseFloat(this.value);
                }
            });
            $('#SubTotal').val(sum);*/

            var SubTotal = $('#SubTotal').val();
            //var SgstPer = $('#SgstPer').val();
            //var CgstPer = $('#CgstPer').val();
            //var IgstPer = $('#IgstPer').val();
            //var GstAmt = $('#GstAmt').val();
            var Discount = $('#Discount').val();
            //var ExtraAmount = $('#ExtraAmount').val();
            var NetAmount = $('#NetAmount').val();
            var Advance = $('#Advance').val();
            
            balAmount(SubTotal, Discount, NetAmount, Advance);
        }

        function balAmount(SubTotal, Discount, NetAmount, Advance) {
            // var CgstAmt = Number(ExtraAmount) * (Number(CgstPer) / 100);
            // var SgstAmt = Number(ExtraAmount) * (Number(SgstPer) / 100);
            // var IgstAmt = Number(ExtraAmount) * (Number(IgstPer) / 100);
            // var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
            // $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
            // var Total_Amount = Number(SubTotal) + Number(GstAmt) + Number(ExtraAmount);
            // $('#TotalAmount').val(parseFloat(Total_Amount).toFixed(2));
            var PrimeDiscount = $('#PrimeDiscount').val();
            var Pkg_Amount = $('#Pkg_Amount').val();
            var NetAmount = Number(SubTotal) + Number(Pkg_Amount) - Number(Discount) - Number(PrimeDiscount);
             $('#Advance').val(parseFloat(NetAmount).toFixed(2));
            $('#NetAmount').val(parseFloat(NetAmount).toFixed(2));
             var Advance = $('#Advance').val();
            var Balance = Number(NetAmount) - Number(Advance);
            $('#Balance').val(parseFloat(Balance).toFixed(2));
           
            $('#Amount1').val(parseFloat(Advance).toFixed(2));
        }

 function calAmt1(){
            var Advance = $('#Advance').val();
            var Amount2 = $('#Amount2').val();
            var Amount1 = Number(Advance) - Number(Amount2);
            $('#Amount1').val(parseFloat(Amount1).toFixed(2));
        }

        function getFareClass(id, srno) {
            var action = "getFareClass";
            $.ajax({
                url: "ajax_files/ajax_customer_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    $('#FareClassId' + srno).html(data);
                }
            });
        }

function getAvailProdStock(id, srno){
     var action = "getAvailProdStock";
            $.ajax({
                url: "ajax_files/ajax_customer_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                  //alert(data);
                    $('#AvailStock' + srno).val(data);
                    if(data > 0){
                        $('#AvailStock'+ srno).css('background-color','');
                    }
                    else{
                    $('#AvailStock'+ srno).css('background-color','#ffa7a7');
                    }
                }
            });
  }
        function getPrice(id, srno) {
            var action = "getPrice";
            var Qty = $('#Qty'+srno).val();
            $.ajax({
                url: "ajax_files/ajax_customer_invoice.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    var CgstPer = res.CgstPer;
                    var SgstPer = res.SgstPer;
                    var IgstPer = res.IgstPer;
                    var ProdPrice = res.ProdPrice;
                    var OrgPrice = res.OrgPrice;
                    //alert(OrgPrice);
                    $('#Price' + srno).val(ProdPrice);
                    $('#CgstPer' + srno).val(CgstPer);
                    $('#SgstPer' + srno).val(SgstPer);
                    $('#IgstPer' + srno).val(IgstPer);
                    $('#OrgPrice' + srno).val(OrgPrice);

                    getAvailProdStock(id, srno);
                    TotAmount(ProdPrice,Qty,CgstPer,SgstPer,IgstPer,srno);
                }
            });

        }

function getCustBalAmt(uid){
        var action = "getCustBalAmt";
            $.ajax({
                url: "ajax_files/ajax_general_ledger.php",
                method: "POST",
                data: {
                    action: action,
                    uid: uid
                }, 
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#TotalInvAmt').val(res.TotAmt);
                    $('#PaidAmount').val(res.PaidAmt);
                    $('#BalAmt').val(res.TotBalAmt);
                    
                }
            });
    }

function getUserDetails(){
    var CellNo = $('#CellNo').val();
    var action = "getUserDetails2";
     var CurrDate = $('#CurrDate').val();
  $.ajax({
  type: "POST",
  url: "ajax_files/ajax_dropdown.php",
  data: {action:action,CellNo:CellNo},
  dataType:"json",  
   success: function(data){
console.log(data);
    
 $('#CustId').val(data.id);
                           $('#Address').val(data.Address);
                        $('#CustName').val(data.Fname);
                       // $('#CellNo').val(data.Phone);
                        $('#EmailId').val(data.EmailId);
                        $('#AccCode').val(data.CustomerId); 
                        if(data.Prime == 0){
                             $('#PackageId').val(0);
                        $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
                        $('#PkgAmt').val(''); 
                        $('#PkgDiscount').val(''); 
                        $('#PkgValidity').val(''); 
                         $('#PrimeDiscount').val('');
                        }
                        else{
                            $('#PackageId').val(data.PkgId);
                        $('#PkgId').val(data.PkgId).attr("disabled",true).attr("selected",true); 
                        $('#PkgAmt').val(data.PkgAmt); 
                        $('#PkgDiscount').val(data.PkgDiscount); 
                        $('#PkgValidity').val(data.PkgValidity); 
                         var SubTotal = $('#SubTotal').val();
                        var PrimeAmt = Number(SubTotal)*(Number(data.PkgDiscount)/100);
                        $('#PrimeDiscount').val(parseFloat(PrimeAmt).toFixed(2));
                        }
                         getSubTotal();
                         
   }
  });
}
        $(document).ready(function() {
            var i = 1;
            $('#add_more').click(function() {
                i++;
                var html = '';
                var action = "getMore";
                $.ajax({
                    url: "ajax_files/ajax_customer_invoice.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: i
                    },
                    success: function(data) {
                        $('#dynamic_field').append(data);
                    }
                });
            });

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                if (confirm("Are you sure you want to delete?")) {
                    $('#row' + button_id + '').remove();
                    getSubTotal();
                }
            });

            $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                getCustBalAmt(val);
                var action = "getUserDetails";
                var CurrDate = $('#CurrDate').val();
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    dataType: "json",
                    success: function(data) {
                        if(val == 0){
                            $('#Address').val('');
                        $('#CustName').val('');
                        //$('#CellNo').val('');
                        $('#EmailId').val('');
                        $('#AccCode').val('');
                        $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
                        $('#PackageId').val(0);
                        $('#PkgAmt').val(''); 
                        $('#PkgDiscount').val(''); 
                        $('#PkgValidity').val(''); 
                         $('#PrimeDiscount').val('');
                        }
                        else{
                           $('#Address').val(data.Address);
                        $('#CustName').val(data.Fname);
                        $('#CellNo').val(data.Phone);
                        $('#EmailId').val(data.EmailId);
                        $('#AccCode').val(data.CustomerId); 
                        if(data.Prime == 0){
                             $('#PackageId').val(0);
                        $('#PkgId').val(0).attr("disabled",false).attr("selected",true); 
                        $('#PkgAmt').val(''); 
                        $('#PkgDiscount').val(''); 
                        $('#PkgValidity').val(''); 
                         $('#PrimeDiscount').val('');
                        }
                        else{
                            $('#PackageId').val(data.PkgId);
                        $('#PkgId').val(data.PkgId).attr("disabled",true).attr("selected",true); 
                        $('#PkgAmt').val(data.PkgAmt); 
                        $('#PkgDiscount').val(data.PkgDiscount); 
                        $('#PkgValidity').val(data.PkgValidity); 
                         var SubTotal = $('#SubTotal').val();
                        var PrimeAmt = Number(SubTotal)*(Number(data.PkgDiscount)/100);
                        $('#PrimeDiscount').val(parseFloat(PrimeAmt).toFixed(2));
                        }
                         getSubTotal();
                        }
                        
                    }
                });

            });
            
            $(document).on("change", "#PkgId", function(event) {
                var val = this.value;
                var action = "getPkgDetails";
                var SubTotal = $('#SubTotal').val();
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        var res = JSON.parse(data);
                        if(val == 0){
                            $('#PkgAmt').val('');
                        $('#PkgDiscount').val('');
                        $('#PkgValidity').val('');
                      $('#PrimeDiscount').val(0);
                      $('#PackageId').val(0);
                        }
                        else{
                            $('#PackageId').val(0);
                           $('#PkgAmt').val(res.PkgAmt);
                        $('#PkgDiscount').val(res.PkgDiscount);
                        $('#PkgValidity').val(res.PkgValidity);
                        $('#Pkg_Amount').val(res.PkgAmt);
                        var PrimeAmt = Number(SubTotal)*(Number(res.PkgDiscount)/100);
                        $('#PrimeDiscount').val(parseFloat(PrimeAmt).toFixed(2));
                        
                        }
                        getSubTotal();
                    }
                });

            });
            
        });
    </script>

</body>

</html>