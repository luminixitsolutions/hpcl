<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Customer-Invoice-Report";
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



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Customer Invoice Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-4">
    <label class="form-label">Customer <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="CustId" id="CustId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=55";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["CustId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname'] . " - " . $result['Phone']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group col-md-2">
    <label class="form-label">Invoice No <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="InvNo" id="InvNo">
        <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_customer_invoice WHERE Status=1 AND CustId='".$_REQUEST["CustId"]."' AND Roll=2";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["InvNo"] == $result['InvoiceNo']) { ?> selected <?php } ?> value="<?php echo $result['InvoiceNo']; ?>"><?php echo $result['InvoiceNo']; ?></option>
            <?php } ?>
        </select>
    </div>

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
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
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
               
                <th>Phone No</th>
                <th>Sub Total</th>
               <!--  <th>Service Charge</th>
                <th>GST%</th>
                <th>GST Amount</th>
                <th>Total Amount</th> -->
                <th>Discount</th>
                <th>Net Payable</th>
                <!-- <th>Advance</th>
                <th>Balance Amount</th> -->
                <th>Payment Mode</th>
                
               
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_customer_invoice WHERE Status=1 AND Roll=2";
            if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND CustId='$CustId'";
                }
            }
            if($_POST['InvNo']){
                $InvNo = $_POST['InvNo'];
                if($InvNo == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND InvoiceNo='$InvNo'";
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
            $sql.=" ORDER BY InvoiceDate DESC";
           // echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $Tot_SubTotal+=$row['SubTotal'];
                // $Tot_ExtraAmount+=$row['ExtraAmount'];
                // $Tot_GstPer+=$row['SgstPer'];
                // $Tot_GstAmt+=$row['GstAmt'];
                 //$Tot_TotalAmount+=$row['TotalAmount'];
                $Tot_Discount+=$row['Discount'];
                $Tot_NetAmount+=$row['NetAmount'];
                
             ?>
            <tr>
               <td><?php echo $i;?></td>
             
              <td><?php echo $row['InvoiceNo']; ?></td>
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
           
                <td><?php echo $row['CustName']; ?></td>
                <td><?php echo $row['CellNo']; ?></td>
                <td>&#8377;<?php echo number_format($row['SubTotal'],2); ?></td>
                <!-- <td>&#8377;<?php echo number_format($row['ExtraAmount'],2); ?></td>
                <td><?php echo $row['SgstPer']; ?>%</td>
                <td>&#8377;<?php echo number_format($row['GstAmt'],2); ?></td>
                <td>&#8377;<?php echo number_format($row['TotalAmount'],2); ?></td> -->
                <td>&#8377;<?php echo number_format($row['Discount'],2); ?></td>
                <td>&#8377;<?php echo number_format($row['NetAmount'],2); ?></td>
                <!-- <td>&#8377; <?php echo number_format($row['Advance'],2); ?></td>
                <td>&#8377; <?php echo number_format($row['Balance'],2); ?></td> -->
                <td><?php echo $row['PayType']; ?></td>
                
           
         
              
            </tr>
           <?php $i++;} ?>
           <tr>
           <td><?php echo $i;?></td>
           <td></td>
           <td></td>
           <td></td>
           <th>Total</th>
           <th>&#8377;<?php echo number_format($Tot_SubTotal,2); ?></th>
          <!--  <th>&#8377;<?php echo number_format($Tot_ExtraAmount,2); ?></th>
           <th><?php echo $Tot_GstPer; ?>%</th>
           <th>&#8377;<?php echo number_format($Tot_GstAmt,2); ?></th> 
           <th>&#8377;<?php echo number_format($Tot_TotalAmount,2); ?></th>-->
           <th>&#8377;<?php echo number_format($Tot_Discount,2); ?></th>
           <th>&#8377;<?php echo number_format($Tot_NetAmount,2); ?></th>
           <td></td>
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
 
	$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

    $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                var action = "getInvoice";
                $.ajax({
                    url: "ajax_files/ajax_fr_general_ledger.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#InvNo').html(data);
                       
                    }
                });

            });
});
</script>
</body>
</html>
