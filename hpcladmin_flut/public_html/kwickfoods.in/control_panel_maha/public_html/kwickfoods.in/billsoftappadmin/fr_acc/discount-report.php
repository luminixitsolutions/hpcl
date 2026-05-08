<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];

$MainPage = "Report";
$Page = "Discount-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
if($_GET["action"]=="delete")
{
  $id = $_GET["id"];
  /*$sql11 = "DELETE FROM tbl_customer_invoice WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_customer_invoice_details WHERE InvId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND Flag='Customer-Invoice'";
  $conn->query($sql11);
  $sql = "DELETE FROM tbl_product_stocks WHERE InvId='$id'";
  $conn->query($sql);
  $sql = "DELETE FROM tbl_cust_prod_stock WHERE InvId='$id'";
  $conn->query($sql);*/
  $modified_time = gmdate('Y-m-d H:i:s.').gettimeofday()['usec'];
  $sql11 = "UPDATE tbl_customer_invoice SET delete_flag=1,modified_time='$modified_time',push_flag=1 WHERE id = '$id'";
    $conn->query($sql11);
    $sql11 = "UPDATE tbl_customer_invoice_details SET delete_flag=1,modified_time='$modified_time',push_flag=1 WHERE InvId = '$id'";
    $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-customer-invoices.php";
    </script>
<?php } 


if($_GET["action"]=="changestatus")
{
  $id = $_GET["id"];
  $status = $_GET['status'];
  if($status == 0){
      $sql = "UPDATE tbl_customer_invoice SET Status=1 WHERE id='$id'";
      $conn->query($sql);
  }
  else{
      $sql = "UPDATE tbl_customer_invoice SET Status=0 WHERE id='$id'";
      $conn->query($sql);
  }
  ?>
    <script type="text/javascript">
      alert("Status Updated Successfully!");
      window.location.href="view-customer-invoices.php";
    </script>
<?php }
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Discount Invoice Report

</h4>
<br>

<div class="card">
    <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">


 <div class="form-group col-lg-2">
                                            <label class="form-label">Select Report</label>
                                            <select class="form-control" id="ReportType" name="ReportType" onchange="showdate(this.value)">
                                               
                                                <option value="Today" <?php if ($_POST['ReportType'] == 'Today') { ?> selected <?php } ?>>Today</option>
                                                <option <?php if ($_POST['ReportType'] == 'Yesterday') { ?> selected <?php } ?> value="Yesterday">Yesterday</option>
                                                <option <?php if ($_POST['ReportType'] == 'Week') { ?> selected <?php } ?> value="Week">This Week</option>
                                                <option <?php if ($_POST['ReportType'] == 'Month') { ?> selected <?php } ?> value="Month">This Month</option>
                                                 <option <?php if ($_POST['ReportType'] == 'Custom') { ?> selected <?php } ?> value="Custom">Custom</option>
                                               
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-2 customfmdt" <?php if ($_POST['ReportType'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2 customtodt" <?php if ($_POST['ReportType'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>

<div class="form-group col-lg-2">
                                            <label class="form-label">Payment Type</label>
                                            <select class="form-control" id="PayType" name="PayType">
                                                <option selected=""  value="all">All</option>
                                                <option value="Cash" <?php if ($_REQUEST['PayType'] == 'Cash') { ?> selected <?php } ?>>Cash</option>
                                                <option <?php if ($_REQUEST['PayType'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($_REQUEST['PayType'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($_REQUEST['PayType'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($_REQUEST['PayType'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                                 
                                                   <option <?php if ($_REQUEST['PayType'] == 'Borrowing') { ?> selected <?php } ?> value="Borrowing">Credit / उधार</option>
                                                   
                                                      <option <?php if ($_POST['PayType'] == 'Zomato') { ?> selected <?php } ?> value="Zomato">Zomato</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
   
  
                                        
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>

<?php
$sql = "SELECT  SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND FrId='$BillSoftFrId' AND delete_flag=0";
           
            if($_POST['ReportType'] !=''){
                $ReportType = $_POST['ReportType'];
                if($ReportType == 'Today'){
                    $sql.=" AND InvoiceDate='".date('Y-m-d')."'";
                }
                if($ReportType == 'Yesterday'){
                    $Yesterday = date('Y-m-d',strtotime("-1 days"));
                    $sql.=" AND InvoiceDate='$Yesterday'";
                }
                if($ReportType == 'Week'){
                    $Week = date('Y-m-d',strtotime("-7 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Month'){
                    $Week = date('Y-m-d',strtotime("-30 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Custom'){
                    $sql.=" ";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            if($_REQUEST['PayType']){
                $PayType = $_REQUEST['PayType'];
                if($PayType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND PayType='$PayType'";
                }
            }
            $sql.=" ORDER BY InvoiceDate DESC";

$row = getRecord($sql);
?>
<div class="form-group col-md-3" style="padding-top:25px;padding-left: 50px;">
    <h5>Total: &#8377;<?php echo $row['NetAmount'];?></h5>
</div>

</div>

</form>

<?php 
    function calAmt($type,$BillSoftFrId){
        global $conn;
         $sql = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND FrId='$BillSoftFrId' AND PayType='$type' AND delete_flag=0";
         if($_POST['ReportType'] !=''){
                $ReportType = $_POST['ReportType'];
                if($ReportType == 'Today'){
                    $sql.=" AND InvoiceDate='".date('Y-m-d')."'";
                }
                if($ReportType == 'Yesterday'){
                    $Yesterday = date('Y-m-d',strtotime("-1 days"));
                    $sql.=" AND InvoiceDate='$Yesterday'";
                }
                if($ReportType == 'Week'){
                    $Week = date('Y-m-d',strtotime("-7 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Month'){
                    $Week = date('Y-m-d',strtotime("-30 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Custom'){
                    $sql.=" ";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            if($_REQUEST['PayType']){
                $PayType = $_REQUEST['PayType'];
                if($PayType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND PayType='$PayType'";
                }
            }
        $res = $conn->query($sql);
	    $row = $res->fetch_assoc();
	    if($row['NetAmount']==''){
	        $NetAmount = 0;
	    }
	    else{
	        $NetAmount = $row['NetAmount'];
	    }
	    return $NetAmount;
    }

 ?>
<div class="form-row">
            <div class="form-group col-md-2">
<label class="form-label">Cash</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Cash',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Phone Pay</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Phone Pay',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Google Pay</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('UPI',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Paytm</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Paytm',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Other UPI</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Other UPI',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-1">
<label class="form-label">Credit</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Borrowing',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-1">
<label class="form-label">Zomato</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Zomato',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

</div>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
        <thead class="thead-light">
            <tr>
             
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
               
                <th>Sub Total</th>
                <th>Discount</th>
                <th>Total Amount</th>
               
                <th>Payment Mode</th>
                
                
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND FrId='$BillSoftFrId' AND NetAmount>0 AND delete_flag=0 AND Discount>0";
          
            if($_POST['ReportType'] !=''){
                $ReportType = $_POST['ReportType'];
                if($ReportType == 'Today'){
                    $sql.=" AND InvoiceDate='".date('Y-m-d')."'";
                }
                if($ReportType == 'Yesterday'){
                    $Yesterday = date('Y-m-d',strtotime("-1 days"));
                    $sql.=" AND InvoiceDate='$Yesterday'";
                }
                if($ReportType == 'Week'){
                    $Week = date('Y-m-d',strtotime("-7 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Month'){
                    $Week = date('Y-m-d',strtotime("-30 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Custom'){
                    $sql.=" ";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            if($_REQUEST['PayType']){
                $PayType = $_REQUEST['PayType'];
                if($PayType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND PayType='$PayType'";
                }
            }
            $sql.=" ORDER BY InvoiceDate DESC";
           // echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                $TotAmt+=$row['NetAmount'];
                $TotSubTotal+=$row['SubTotal'];
                $TotDiscount+=$row['Discount'];
               
             ?>
            <tr>
             
            
            
            
              <td class="align-middle"><?php echo $row['InvoiceNo']; ?></td>
              <td class="align-middle"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
           
                <td class="align-middle"><?php echo $row['CustName']; ?></td>
             
             <td class="align-middle">&#8377; <?php echo number_format($row['SubTotal'],2); ?></td>
             <td class="align-middle">&#8377; <?php echo number_format($row['Discount'],2); ?></td>
                
                  <td class="align-middle">&#8377; <?php echo number_format($row['NetAmount'],2); ?></td>
               
                  <td class="align-middle"><?php echo $row['PayType']; ?></td>
                
            
         
              
            </tr>
           <?php $i++;} ?>
           
           <tr>
               <th></th>
               <th></th>
               
                
               <th>Total</th>
               <th>&#8377;<?php echo number_format($TotSubTotal,2);?></th>
               <th>&#8377;<?php echo number_format($TotDiscount,2);?></th>
               <th>&#8377;<?php echo number_format($TotAmt,2);?></th>
               <th></th>
           </tr>
        </tbody>
    </table>
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


<script type="text/javascript">
function showdate(val){
    if(val == 'Custom'){
        $('.customfmdt').show();
        $('.customtodt').show();
    }
    else{
        $('.customfmdt').hide();
        $('.customtodt').hide();
        $('#FromDate').val('');
        $('ToDate').val('');
    }
}
    function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'SMS Sent...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function sendSms(id){
         var action = "sendSms";
    $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
  data: {action:action,id:id},
  success: function(data){
         success_toast();
      }
});
    }
function printReceipt(invdata){
    console.log(invdata);
   // alert(invdata);
     Android.printReceipt(''+invdata+'');
}

function goToOffline(){
      Android.goToOfflinePage();
   }
 function approve(status,id){
     //alert(status);alert(id);
     window.location.href="view-invoices.php?status="+status+"&id="+id+"&action=changestatus";
 }
	$(document).ready(function() {
    $('#example').DataTable({
        order: [[1, 'desc']],
      "scrollX": true
    });
});
</script>
</body>
</html>
