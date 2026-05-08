<?php session_start(); include_once '../config.php'; 
include_once 'auth.php'; 
$user_id = $_REQUEST['user_id']; 
$BillSoftFrId = $_REQUEST['user_id']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Customer Credit Account</title>

<!-- BOOTSTRAP + JQUERY -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- ====================== MODERN PURPLE UI (MATCHING ATTACHED IMAGE) ====================== -->
<style>

/* Background */
body {
    background: #f4f2ff;
    font-family: "Inter", sans-serif;
}

/* Main Content Wrapper */
.page-wrapper {
    padding: 10px;
}

/* Card (Same Style as Attached Image) */
.soft-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 10px;
    box-shadow: 0px 4px 18px rgba(112, 76, 255, 0.12);
}

/* Section Header (Same Violet Bar With Icon) */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #4b2cc4;
    background: #f3efff;
    padding: 12px 18px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}

.section-title i {
    font-size: 20px;
    color: #7042ff;
}

/* DataTable Header */
table.dataTable thead th {
    background: #7042ff !important;
    color: white !important;
    font-size: 14px;
    padding: 12px;
}

/* Row Style */
table tbody tr td {
    font-size: 14px;
    padding: 10px;
    color: #3d3d5c;
}

/* Customer Header Row */
.customer-header {
    background: #ebe6ff !important;
    font-weight: 700;
    color: #4b2cc4;
    font-size: 15px;
}

/* Total Row */
.total-row {
    background: #ecebff;
    font-weight: 700;
    color: #5138d4;
}

/* Balance Row */
.balance-row {
    background: #dff5ff;
    font-weight: 700;
    color: #026c7c;
}

/* Invoice Link */
.invoice-link {
    color: #7042ff;
    font-weight: 600;
}

.invoice-link:hover {
    text-decoration: underline;
}

/* DataTable Buttons */
button.dt-button {
    background: #7042ff !important;
    color: white !important;
    border-radius: 10px !important;
    border: none !important;
    padding: 8px 18px !important;
}

/* Modal */
.modal-content {
    border-radius: 14px;
}

.modal-header {
    background: #7042ff;
    color: white;
    border-radius: 14px 14px 0 0;
}

#invoiceItems tr td {
    font-size: 14px;
    padding: 10px;
}

#invoiceItems th {
    background: #ecebff;
    font-weight: 700;
}

</style>
</head>
<body>

<div class="page-wrapper">

    <!-- Section Title -->
    <!--<div class="section-title">
        <i class="fa-solid fa-wallet"></i>  
        Customer Credit Ledger
    </div>-->

    <!-- Main Table Card -->
    <div class="soft-card table-responsive">
        <table id="example" class="table table-bordered">
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
                $sql2 .= " AND PaymentDate >= '$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $sql2 .= " AND PaymentDate <= '$ToDate'";
            }
            $sql2 .= " ORDER BY PaymentDate";
            $rx = $conn->query($sql2);

            $TotCreditAmt = 0;
            $TotDebitAmt = 0;
            ?>
            
            <!-- ⭐ CUSTOMER HEADER ROW -->
            <tr class="customer-header">
                <td><?php echo $i;?></td>
                <td colspan="7" style="font-weight:bold;">
                    <?php echo $row['AccountName'] . " (" . $row['CustPhone'] . ")"; ?>
                </td>
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
                        <a class="invoice-link" href="javascript:void(0)" onclick="loadInvoiceDetails('<?php echo $nx['UniqInvId']; ?>')">
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

            <!-- ⭐ TOTAL ROW -->
            <tr class="total-row">
                <td><?php echo $i; ?></td>
                <td colspan="4"></td>
                <td style="text-align:right;">Total</td>
                <td><?php echo number_format($TotCreditAmt, 2); ?></td>
                <td><?php echo number_format($TotDebitAmt, 2); ?></td>
                <td></td>
            </tr>

            <!-- ⭐ BALANCE ROW -->
            <tr class="balance-row">
                <td><?php echo $i; ?></td>
                <td colspan="4"></td>
                <td style="text-align:right;">Balance</td>
                <td colspan="2"><?php echo number_format($TotDebitAmt - $TotCreditAmt, 2); ?></td>
                <td></td>
            </tr>

            <?php 
            $i++;
        }
        ?>
    </tbody>

        </table>
    </div>

</div>

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Invoice Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div id="invoiceLoader" class="text-center" style="display:none;">
            <div class="spinner-border text-primary"></div>
            <p>Loading...</p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="invoiceItems"></tbody>
        </table>

      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        "pageLength": 100,
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: ['excelHtml5']
    });
});

function loadInvoiceDetails(invid) {
    $('#invoiceItems').html("");
    $('#invoiceLoader').show();
    $('#invoiceModal').modal('show');

    $.ajax({
        url: "../ajax_files/ajax_customer_account.php",
        type: 'POST',
        data: { action: "loadInvoiceDetails", invid: invid },
        success: function(response) {
            $('#invoiceItems').html(response);
            $('#invoiceLoader').hide();
        },
        error: function() {
            $('#invoiceItems').html('<tr><td colspan="4" class="text-danger">Failed to load data</td></tr>');
            $('#invoiceLoader').hide();
        }
    });
}
</script>

</body>
</html>
