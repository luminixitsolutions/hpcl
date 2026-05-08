<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Final-Financer";
$Page = "Final-Financer-Pending-Request-Product-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Raw Stock
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
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
     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT tp.*,tu.Fname AS GodownName,tu2.Fname,tu2.CustomerId AS VedCode FROM tbl_request_product_stock tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FromFrId 
            LEFT JOIN tbl_users tu2 ON tu2.id=tp.VedId 
            WHERE tp.id='$id'";
$row7 = getRecord($sql7);
$InvoiceNo = $row7['id'];
$VedId = $row7['VedId'];
$VedCode = $row7['VedCode'];
$VedName = $row7['Fname'];

$sql2 = "SELECT * FROM tbl_request_product_stock_items WHERE TransferId='$id'";
$rncnt2 = getRow($sql2);

$EmpId = $row7['UserId'];




if(isset($_POST['submit'])){
    $PaidAmt = addslashes(trim($_POST["PaidAmt"]));
     $BalanceAmt = addslashes(trim($_POST["BalanceAmt"]));
  $PayType = addslashes(trim($_POST["PayType"]));
  $ChequeNo = addslashes(trim($_POST["ChequeNo"]));
  $ChqDate = addslashes(trim($_POST["ChqDate"]));
  $BankName = addslashes(trim($_POST["BankName"]));
  $UpiNo = addslashes(trim($_POST["UpiNo"]));
  $NextDueDate = addslashes(trim($_POST["NextDueDate"]));
  $FinancerApproveDate2 = addslashes(trim($_POST["FinancerApproveDate2"]));
  $FinancerStatus2 = addslashes(trim($_POST["FinancerStatus2"]));
  $FinancerComment2 = addslashes(trim($_POST["FinancerComment2"]));
  $TotalAmount = addslashes(trim($_POST["TotalAmount"]));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_request_product_stock SET PaidAmt='$PaidAmt',BalanceAmt='$BalanceAmt',PayType='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',NextDueDate='$NextDueDate',FinancerApproveDate2='$FinancerApproveDate2',FinancerComment2='$FinancerComment2',FinancerStatus2='$FinancerStatus2',FinancerBy2='$user_id' WHERE id = '$id'";
  $conn->query($query2);

  //total payment
  $sql = "INSERT INTO tbl_vendor_ledger SET UserId='$VedId',AccCode='$VedCode',AccountName='$VedName',InvoiceNo='$id',Amount='$TotalAmount',PaymentDate='$FinancerApproveDate2',CrDr='dr',Roll=3,Type='INV',PayMode='$PayType',Narration='$Narration',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
  $conn->query($sql);

$sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_vendor_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }
  //paid amt
   $sql = "INSERT INTO tbl_vendor_ledger SET UserId='$VedId',AccCode='$VedCode',AccountName='$VedName',InvoiceNo='$id',Amount='$PaidAmt',PaymentDate='$FinancerApproveDate2',CrDr='cr',Roll=3,Type='PR',PayMode='$PayType',Narration='$Narration',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
  $conn->query($sql);

  echo "<script>alert('Approved Successfully!');window.location.href='final-financer-pending-request-product-stock.php';</script>";


    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Final Approve Product Stock Request</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Franchise Name</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['GodownName']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                            <label class="form-label">Vendor Name</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['Fname']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-3">
                                            <label class="form-label">Total Product</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $rncnt2; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-3">
                                            <label class="form-label">Total Qty</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotQty"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">GST Amount</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["GstAmount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Total Amount</label>
                                            <input type="text" id="TotalAmount" name="TotalAmount" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotalAmount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Paid Amount</label>
                                            <input type="text" name="PaidAmt" id="PaidAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaidAmt"]; ?>" required oninput="calBalAmt(document.getElementById('TotalAmount').value,document.getElementById('PaidAmt').value)">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Balance Amount</label>
                                            <input type="text" id="BalanceAmt" name="BalanceAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["BalanceAmt"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
<label class="form-label">Payment Type <span class="text-danger">*</span></label>
  <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
<option selected="" disabled="" value="">Select Payment Type</option>
<option <?php if($row7['PayMode'] == 'Cash') {?> selected <?php } ?> value="Cash">Cash</option>
<option <?php if($row7['PayMode'] == 'Cheque') {?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
<option <?php if($row7['PayMode'] == 'UPI') {?> selected <?php } ?> value="UPI">UPI</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Cheque No <span class="text-danger">*</span></label>
<input type="text" name="ChequeNo" class="form-control" id="ChequeNo" placeholder="Cheque No" value="<?php echo $row7['ChequeNo']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Cheque Date <span class="text-danger">*</span></label>
<input type="date" name="ChqDate" class="form-control" id="ChqDate" placeholder="Cheque Date" value="<?php echo $row7['ChqDate']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" class="form-control" id="BankName" placeholder="Bank Name" value="<?php echo $row7['BankName']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12 upioption" style="display: none;">
<label class="form-label">UPI No/ Transaction Id <span class="text-danger">*</span></label>
<input type="text" name="UpiNo" class="form-control" id="UpiNo" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo']; ?>">
<div class="clearfix"></div>
</div>
                                        
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Next Due Date</label>
                                            <input type="date" name="NextDueDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["NextDueDate"]; ?>" >
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                       
                                        

 <div class="form-group col-md-4">
                                            <label class="form-label">Payment Date</label>
                                            <input type="date" name="FinancerApproveDate2" class="form-control"
                                                placeholder="" value="<?php echo $row7["FinancerApproveDate2"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-4">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="FinancerStatus2" name="FinancerStatus2" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["FinancerStatus2"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($row7["FinancerStatus2"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($row7["FinancerStatus2"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
 <div class="form-group col-md-12">
                                            <label class="form-label">Comment</label>
                                            <textarea name="FinancerComment2" class="form-control"
                                                placeholder=""></textarea>
                                            <div class="clearfix"></div>
                                        </div>



                                        
</div>

  


                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Approve</button>
                                    </div>

                
                                    </div>
                               </div>


 <div class="col-lg-5" id="emidetails" style="display:none;">
    

 </div>

  
                                

 </div>
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
    function calBalAmt(totamt,paidamt){
        var BalAmt = Number(totamt) - Number(paidamt);
        $('#BalanceAmt').val(parseFloat(BalAmt).toFixed(2));
    }
    function getPayType(val){
    if(val == 'Cheque'){
      $('.chequeoption').show();
      $('.upioption').hide();
    }
    else if(val == 'UPI'){
      $('.chequeoption').hide();
      $('.upioption').show();
    }
    else{
      $('.chequeoption').hide();
      $('.upioption').hide();
    }
  }
 </script>
</body>

</html>