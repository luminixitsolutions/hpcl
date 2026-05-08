<?php 
session_start();
include_once '../config.php';
//include_once 'auth.php';
$user_id = $_REQUEST['user_id'];
$BillSoftFrId = $_REQUEST['user_id'];

$MainPage = "View-Credit-Account";
$Page = "View-Credit-Account";

$id = $_GET['id'] ?? '';
$recid = $_GET['recid'] ?? '';

$sql7 = "SELECT * FROM tbl_cust_general_ledger WHERE id='$id'";
$row7 = getRecord($sql7);

if ($id == '') {
    $PayDate = date('Y-m-d');
} else {
    $PayDate = $row7['PaymentDate'];
    $CustId = $row7["CustId"];
    $CustName = $row7['AccountName'];
    $CellNo = $row7['CellNo'];
    $Address = $row7['Address'];
    $RecId = $row7["RecId"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pay Credit Amount</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background-color: #f6f4fa;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  color: #2f2f2f;
  padding: 1px;
}

.card {
  background-color: #fff;
  border: none;
  border-radius: 18px;
  box-shadow: 0 3px 12px rgba(90, 60, 200, 0.08);
  padding: 10px 10px;
}

.section-title {
  background-color: #f5f2ff;
  border-left: 5px solid #6a4fe0;
  padding: 8px 12px;
  margin-bottom: 18px;
  font-weight: 600;
  color: #4a3aa0;
  border-radius: 6px;
  font-size: 15px;
}

.form-label {
  font-weight: 600;
  font-size: 14px;
  color: #4a3aa0;
}

.form-control, .form-select, textarea {
  border: 1px solid #d8d1ff;
  border-radius: 10px;
  font-size: 14px;
  padding: 10px 12px;
  box-shadow: none;
}

.form-control:focus, .form-select:focus, textarea:focus {
  border-color: #7a5fff !important;
  box-shadow: 0 0 0 0.2rem rgba(122, 95, 255, 0.15) !important;
}

.btn-primary {
  background-color: #6a4fe0 !important;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  padding: 10px 20px;
  font-weight: 600;
}
.btn-primary:hover {
  background-color: #5a41c6 !important;
}

.autocomplete-list {
  border: 1px solid #dcd4ff;
  background: #fff;
  border-radius: 10px;
  position: absolute;
  z-index: 9999;
  max-height: 250px;
  overflow-y: auto;
  width: 100%;
  box-shadow: 0px 3px 12px rgba(112, 76, 255, 0.15);
  display: none;
}

.autocomplete-item {
  padding: 10px;
  cursor: pointer;
  font-size: 14px;
}
.autocomplete-item:hover {
  background: #efe9ff;
  color: #4b2cc4;
}
</style>

</head>
<body>

<div class="container-fluid">
  <div class="card">

<form id="creditForm" method="POST">

<input type="hidden" id="FrId" name="FrId" value="<?php echo $BillSoftFrId; ?>">

<!-- ================== CUSTOMER INFORMATION ================== -->
<div class="section-title"><i class="bi bi-person"></i> Customer Information</div>

<div class="row g-3">

  <div class="col-md-12 position-relative">
    <label class="form-label">Search Customer *</label>
    <input type="text" name="SearchCust" id="SearchCust" class="form-control" placeholder="Search by name or phone..." autocomplete="off">
    <div id="autocomplete-list" class="autocomplete-list"></div>
  </div>

  <div class="col-md-4">
    <label class="form-label">Customer ID</label>
    <input type="text" name="CustId" id="CustId" class="form-control" readonly>
  </div>

  <div class="col-md-4">
    <label class="form-label">Contact No</label>
    <input type="text" name="CustPhone" id="CustPhone" class="form-control">
  </div>

  <div class="col-md-4">
    <label class="form-label">Customer Name</label>
    <input type="text" name="CustName" id="CustName" class="form-control">
  </div>

</div>


<!-- ================== PAYMENT SUMMARY ================== -->
<div class="section-title mt-4"><i class="bi bi-cash-stack"></i> Payment Summary</div>

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Total Amount</label>
    <input type="text" name="TotalInvAmt" id="TotalInvAmt" class="form-control" readonly>
  </div>

  <div class="col-md-4">
    <label class="form-label">Paid Amount</label>
    <input type="text" name="PaidAmount" id="PaidAmount" class="form-control" readonly>
  </div>

  <div class="col-md-4">
    <label class="form-label">Balance Amount</label>
    <input type="text" name="BalanceAmt" id="BalanceAmt" class="form-control" readonly>
  </div>
</div>


<!-- ================== PAYMENT DETAILS ================== -->
<div class="section-title mt-4"><i class="bi bi-wallet2"></i> Payment Details</div>

<div class="row g-3">

  <div class="col-md-4">
    <label class="form-label">Paid Amount *</label>
    <input type="number" name="Amount" id="Amount" class="form-control">
  </div>

  <div class="col-md-4">
    <label class="form-label">Payment Date</label>
    <input type="date" name="PayDate" id="PayDate" class="form-control" value="<?php echo $PayDate; ?>">
  </div>

  <div class="col-md-4">
    <label class="form-label">Payment Type</label>
    <select class="form-select" id="PayType" name="PayType" onchange="getPayType(this.value)">
      <option value="">Select</option>
      <option value="Cash">Cash</option>
      <option value="Cheque">Cheque/Bank Transfer</option>
      <option value="UPI">UPI</option>
    </select>
  </div>

  <!-- Cheque Fields -->
  <div class="col-md-4 chequeoption d-none">
    <label class="form-label">Cheque No</label>
    <input type="text" name="ChequeNo" id="ChequeNo" class="form-control">
  </div>

  <div class="col-md-4 chequeoption d-none">
    <label class="form-label">Cheque Date</label>
    <input type="date" name="ChqDate" id="ChqDate" class="form-control">
  </div>

  <div class="col-md-4 chequeoption d-none">
    <label class="form-label">Bank Name</label>
    <input type="text" name="BankName" id="BankName" class="form-control">
  </div>

  <!-- UPI -->
  <div class="col-md-12 upioption d-none">
    <label class="form-label">UPI / Transaction ID</label>
    <input type="text" name="UpiNo" id="UpiNo" class="form-control">
  </div>

</div>


<!-- ================== NOTES ================== -->
<div class="section-title mt-4"><i class="bi bi-journal-text"></i> Notes</div>

<textarea name="Narration" id="Narration" class="form-control" rows="2"></textarea>

<div class="text-center mt-4">
  <button type="submit" name="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Payment</button>
</div>

</form>

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ======================== YOUR ORIGINAL SCRIPT ======================= -->
<script>
        function getPayType(val) {
    if (val === 'Cheque') {
        $('.chequeoption').removeClass('d-none');
        $('.upioption').addClass('d-none');
    } else if (val === 'UPI') {
        $('.chequeoption').addClass('d-none');
        $('.upioption').removeClass('d-none');
    } else {
        $('.chequeoption, .upioption').addClass('d-none');
    }
}

        let currentFocus = -1;

        $(document).ready(function() {
            $("#SearchCust").on("input", function() {
                let SearchCust = $(this).val();

                if (SearchCust.length === 0) {
                    $("#autocomplete-list").hide();
                    return;
                }
                var action = "getCustList";
                var FrId = $('#FrId').val();
                $.ajax({
                    url: "../ajax_files/ajax_customer_account.php",
                    method: "POST",
                    data: {
                        action: action,
                        SearchCust: SearchCust,
                        FrId:FrId
                    },
                    success: function(data) {
                        console.log(data);
                        $("#autocomplete-list").empty().show();
                        currentFocus = -1;

                        if (data.length === 0) {
                            $("#autocomplete-list").hide();
                            return;
                        }

                        data.forEach(function(item) {
                            $("#autocomplete-list").append(`<div class="autocomplete-item" onclick="getCustDetails(${item.id})">${item.Fname} (${item.Phone})</div>`);
                        });

                        $(".autocomplete-item").on("click", function() {
                            $("#SearchCust").val($(this).text());
                            $("#autocomplete-list").hide();
                        });
                    }
                });
            });

            $("#SearchCust").on("keydown", function(e) {
                let items = $(".autocomplete-item");

                if (e.key === "ArrowDown") {
                    currentFocus++;
                    if (currentFocus >= items.length) currentFocus = 0;
                    setActive(items);
                    e.preventDefault();
                } else if (e.key === "ArrowUp") {
                    currentFocus--;
                    if (currentFocus < 0) currentFocus = items.length - 1;
                    setActive(items);
                    e.preventDefault();
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (currentFocus > -1 && items[currentFocus]) {
                        items.eq(currentFocus).click();
                    }
                }
            });

            function setActive(items) {
                items.removeClass("active");
                if (currentFocus >= 0 && currentFocus < items.length) {
                    items.eq(currentFocus).addClass("active");
                    items.eq(currentFocus)[0].scrollIntoView({
                        block: "nearest"
                    });
                }
            }

            $(document).click(function(e) {
                if (!$(e.target).closest("#SearchCust, #autocomplete-list").length) {
                    $("#autocomplete-list").hide();
                }
            });
        });

        function getCustDetails(id) {
            var action = "getCustDetails";
            $.ajax({
                url: "../ajax_files/ajax_customer_account.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                dataType: "json",
                success: function(data) {
                    $('#CustId').val(data.id);
                    $('#CustName').val(data.Fname);
                    $('#CustPhone').val(data.Phone);
                    getRecordDetails(data.id);
                }
            });
        }

        function getCollections(uid, FrId) {
            //var FrId = $('#FrId').val();
            $.ajax({
                url: "../ajax_files/ajax_customer_account.php",
                method: "POST",
                data: {
                    action: "getCollections",
                    uid: uid,
                    FrId: FrId
                },
                success: function(data) {
                    
                    $('#custresult').html(data);
                }
            });
        }

        function getRecordDetails(uid) {
            var FrId = $('#FrId').val();
            $.ajax({
                url: "../ajax_files/ajax_customer_account.php",
                method: "POST",
                data: {
                    action: "getCustCommissionDetails",
                    uid: uid,
                    FrId: FrId
                },
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#TotalInvAmt').val(res.TotAmt);
                    $('#PaidAmount').val(res.PaidAmt);
                    $('#BalanceAmt').val(res.BalAmt);
                    getCollections(uid, FrId);
                }
            });
        }
        
        function loadInvoiceDetails(invid) {
  $('#invoiceItems').html(''); // clear previous content
  $('#invoiceLoader').show();  // show loader
  $('#invoiceModal').modal('show'); // open modal immediately

  $.ajax({
    url: "../ajax_files/ajax_customer_account.php",
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
