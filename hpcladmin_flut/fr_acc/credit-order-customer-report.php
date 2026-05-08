<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-2025";
$Page = "Sell-Product-Report-2025";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>Credit Order Customer Report </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Credit Order Outstanding Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

<div class="form-group col-md-4">
    <label class="form-label">Customers <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="UserId" id="UserId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_cust_general_ledger WHERE FrId='$BillSoftFrId' AND CrDr='dr' GROUP BY UserId";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["UserId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['AccountName']." (".$result['CustPhone'].")"; ?></option>
            <?php } ?>
        </select>
    </div>
    
<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>

<?php if(isset($_REQUEST['Search'])) {?>
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
            <th>Mode</th>
            <th>Cr</th>
            <th>Dr</th>
            <th>Narration</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $sql = "SELECT * FROM tbl_cust_general_ledger WHERE FrId='$BillSoftFrId'";
        if($_REQUEST['FromDate']){
            $FromDate = $_REQUEST['FromDate'];
            $sql .= " AND PaymentDate >= '$FromDate'";
        }
        if($_REQUEST['ToDate']){
            $ToDate = $_REQUEST['ToDate'];
            $sql .= " AND PaymentDate <= '$ToDate'";
        }
        $sql .= " GROUP BY UserId ORDER BY PaymentDate";
        $res = $conn->query($sql);

        while($row = $res->fetch_assoc()) {
            $id = $row['UserId'];

            // Fetch all records for this user
            $sql2 = "SELECT * FROM tbl_cust_general_ledger WHERE UserId='$id' AND FrId='$BillSoftFrId' ";
            if($_REQUEST['FromDate']){
            $FromDate = $_REQUEST['FromDate'];
            $sql2 .= " AND PaymentDate >= '$FromDate'";
        }
        if($_REQUEST['ToDate']){
            $ToDate = $_REQUEST['ToDate'];
            $sql2 .= " AND PaymentDate <= '$ToDate'";
        }
        $sql2 .= " ORDER BY PaymentDate";
            $rx = $conn->query($sql2);

            $TotCreditAmt = 0;
            $TotDebitAmt = 0;
            ?>
            <tr>
                <td><?php echo $i;?></td>
                <td style="font-weight:bold; background:#f0f0f0;">
                    <?php echo $row['AccountName'] . " (" . $row['CustPhone'] . ")"; ?>
                </td>
                <?php for($i2=1;$i2<=7;$i2++){?>
                <td></td>
                <?php } ?>
            </tr>
            <?php
            while ($nx = $rx->fetch_assoc()) {
                if ($nx['CrDr'] == 'cr') {
                    $TotCreditAmt += $nx['Amount'];
                } else {
                    $TotDebitAmt += $nx['Amount'];
                }
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <a href="javascript:void(0)" onclick="loadInvoiceDetails('<?php echo $nx['UniqInvId']; ?>')">
                            <?php echo $nx['InvoiceNo']; ?>
                        </a>
                    </td>
                    <td><?php echo date("d/m/Y", strtotime($nx['PaymentDate'])); ?></td>
                    <td><?php echo $row['AccountName']; ?></td>
                    <td><?php echo $row['CustPhone']; ?></td>
                    <td>Credit Order</td>
                    <td><?php echo ($nx['CrDr'] == 'cr') ? number_format($nx['Amount'], 2) : '0.00'; ?></td>
                    <td><?php echo ($nx['CrDr'] == 'dr') ? number_format($nx['Amount'], 2) : '0.00'; ?></td>
                    <td><?php echo $nx['Narration']; ?></td>
                </tr>
                <?php
            }
            ?>
            <tr style="font-weight: bold; background: #e9ecef;">
                 <td><?php echo $i; ?></td>
                <?php for($i3=1;$i3<=4;$i3++){?>
                <td></td>
                <?php } ?>
                <td style="text-align: right;">Total</td>
                <td><?php echo number_format($TotCreditAmt, 2); ?></td>
                <td><?php echo number_format($TotDebitAmt, 2); ?></td>
                <td></td>
            </tr>
            <tr style="font-weight: bold; background: #d1ecf1;">
                 <td><?php echo $i; ?></td>
                <?php for($i3=1;$i3<=4;$i3++){?>
                <td></td>
                <?php } ?>
                <td style="text-align: right;">Balance</td>
                <td ><?php echo number_format($TotDebitAmt - $TotCreditAmt, 2); ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
            $i++;
        }
        ?>
    </tbody>
</table>

<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invoice Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="invoiceDetails">
        <!-- Table will be injected here -->
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody id="invoiceItems">
                <div id="invoiceLoader" style="text-align: center; padding: 30px; display: none;">
  <div class="spinner-border text-primary" role="status">
    <span class="sr-only">Loading...</span>
  </div>
  <p>Loading invoice details...</p>
</div>
              <!-- Dynamic rows go here -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
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
   function printReport(invdata){
     console.log(invdata);
      Android.printReport(''+invdata+'');
 }
	$(document).ready(function() {
    $('#example').DataTable({
        "pageLength":100,
      "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

    $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                var action = "getInvoiceNos";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
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

function loadInvoiceDetails(invid) {
  $('#invoiceItems').html(''); // clear previous content
  $('#invoiceLoader').show();  // show loader
  $('#invoiceModal').modal('show'); // open modal immediately

  $.ajax({
    url: "ajax_files/ajax_customer_account.php",
    type: 'POST',
    data: { action: "loadInvoiceDetails", invid: invid },
    success: function(response) {
      $('#invoiceItems').html(response);     // inject table rows
      $('#invoiceLoader').hide();            // hide loader
    },
    error: function() {
      $('#invoiceItems').html('<tr><td colspan="5" class="text-danger text-center">Failed to load data</td></tr>');
      $('#invoiceLoader').hide();
    }
  });
}
</script>
</body>
</html>
