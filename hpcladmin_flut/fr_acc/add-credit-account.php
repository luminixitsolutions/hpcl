<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "View-Credit-Account";
$Page = "View-Credit-Account";
$sessionid = session_id();
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - Product</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
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
    <style>
        .autocomplete-list2 {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
            margin-top: 35px;
        }

        .autocomplete-list {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
        }

        .autocomplete-item {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-item:hover,
        .autocomplete-item.active {
            background-color: #98e6ed;
        }
    </style>
    <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php';
            include_once 'sidebar.php'; ?>


            <div class="layout-container">


                 <?php 
$id = $_GET['id'];
$recid = $_GET['recid'];
$sql7 = "SELECT * FROM tbl_cust_general_ledger WHERE id='$id'";
$row7 = getRecord($sql7);
if($_GET['id'] == ''){
    $PayDate = date('Y-m-d');
}
else{
    $PayDate = $row7['PaymentDate'];
    $CustId = $row7["CustId"];
    $CustName = $row7['AccountName'];
    $CellNo = $row7['CellNo'];
    $Address = $row7['Address'];
    $RecId = $row7["RecId"];
}
?>

                <?php
                if (isset($_POST['submit'])) {
                    $FrId = addslashes(trim($_POST["FrId"]));
    $CustId = addslashes(trim($_POST["CustId"]));
    $CustName = addslashes(trim($_POST["CustName"]));
    $CustPhone = addslashes(trim($_POST['CustPhone']));
    $Address = addslashes(trim($_POST['Address']));
    $InvNo = addslashes(trim($_POST['InvNo']));
    $InvId = addslashes(trim($_POST['InvId']));
    $Amount = addslashes(trim($_POST['Amount']));
    $Interest = addslashes(trim($_POST['Interest']));
    $TotAmount = addslashes(trim($_POST['TotAmount']));
    $PayDate = addslashes(trim($_POST['PayDate']));
    $PayType = addslashes(trim($_POST['PayType']));
    $Narration = addslashes(trim($_POST['Narration']));
    $MonthPeriod = addslashes(trim($_POST['MonthPeriod']));
    $Month = date('m', strtotime($PayDate));
    $ChequeNo = addslashes(trim($_POST['ChequeNo']));
    $ChqDate = addslashes(trim($_POST['ChqDate']));
    $BankName = addslashes(trim($_POST['BankName']));
    $UpiNo = addslashes(trim($_POST['UpiNo']));
    $CreatedDate = date('Y-m-d');
    
    if($id==''){
        
        $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_cust_general_ledger WHERE Type='PR'";
        $row2 = getRecord($sql2);
        if($row2['maxid'] == ''){
            $SrNo = 1;
            $Code = "PR".$SrNo;
        }
        else{
            $SrNo = $row2['maxid']+1;
            $Code = "PR".$SrNo;
        }
        
         $sql = "INSERT INTO tbl_cust_general_ledger SET FrId='$FrId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName'
    ,Amount='$Amount',PaymentDate='$PayDate',PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',
    Narration='$Narration',Type='PR',CrDr='cr',CreatedBy='$user_id',CreatedDate='$CreatedDate',CustPhone='$CustPhone'";
    $conn->query($sql);
    
         echo "<script>alert('Record Saved Successfully!');window.location.href='view-manage-credit-accounts.php';</script>";
        }
        else{
        $sql4 = "UPDATE tbl_cust_general_ledger SET FrId='$FrId',UserId='$CustId',AccountName='$CustName',Amount='$Amount',PaymentDate='$PayDate',PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',
        Narration='$Narration',Type='PR',CrDr='cr',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',CustPhone='$CustPhone' WHERE id='$id'";
        $conn->query($sql4);
        echo "<script>alert('Record Updated Successfully!');window.location.href='view-manage-credit-accounts.php';</script>";
        }

                    //header('Location:courses.php'); 

                }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Pay Credit Amount</h4>


                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <div class="row">
                                    <div class="col-lg-7">
                                        <form id="validation-form" method="post" enctype="multipart/form-data">
                                            <div class="form-row">

                                                <input type="hidden" id="FrId" name="FrId" value="<?php echo $BillSoftFrId; ?>">
                                                <div class="form-group col-md-12">
                                                    <label class="form-label">Seach Customer <span class="text-danger">*</span></label>
                                                    <input type="text" name="SearchCust" id="SearchCust" class="form-control" placeholder="search phone no, Name..." value="" autocomplete="off" >
                                                    <div id="autocomplete-list" class="autocomplete-list" style="display: none; position: absolute;"></div>
                                                    <div class="clearfix"></div>

                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Customer ID <span class="text-danger">*</span></label>
                                                    <input type="text" name="CustId" id="CustId" class="form-control" placeholder="" value="<?php echo $row7['UserId'];?>" autocomplete="off" required readonly>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Contact No <span class="text-danger">*</span></label>
                                                    <input type="text" name="CustPhone" id="CustPhone" class="form-control" placeholder="" value="<?php echo $row7['CustPhone'];?>" autocomplete="off" required>
                                                    <div class="clearfix"></div>

                                                </div>



                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="CustName" id="CustName" class="form-control" placeholder="" value="<?php echo $row7['AccountName'];?>" autocomplete="off" required>
                                                    <div class="clearfix"></div>
                                                </div>


                                                <?php if ($_GET['id'] == '') { ?>
                                                    <div class="form-group col-md-4">
                                                        <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                                        <input type="text" name="TotalInvAmt" id="TotalInvAmt" class="form-control"
                                                            placeholder="" value=""
                                                            autocomplete="off" readonly>
                                                        <div class="clearfix"></div>
                                                    </div>


                                                    <div class="form-group col-md-4">
                                                        <label class="form-label">Total Paid Amount <span class="text-danger">*</span></label>
                                                        <input type="text" name="PaidAmount" id="PaidAmount" class="form-control"
                                                            placeholder="" value=""
                                                            autocomplete="off" readonly>
                                                        <div class="clearfix"></div>
                                                    </div>


                                                    <div class="form-group col-md-4">
                                                        <label class="form-label">Balance Amount <span class="text-danger">*</span></label>
                                                        <input type="text" name="BalanceAmt" id="BalanceAmt" class="form-control"
                                                            placeholder="" value=""
                                                            autocomplete="off" readonly>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                                    <input type="text" name="Amount" id="Amount" class="form-control"
                                                        placeholder="" value="<?php echo $row7["Amount"]; ?>"
                                                        autocomplete="off" required oninput="getTotal(document.getElementById('Amount').value,document.getElementById('Interest').value,document.getElementById('MonthPeriod').value)">
                                                    <div class="clearfix"></div>
                                                </div>



                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                                    <input type="date" name="PayDate" id="PayDate" class="form-control"
                                                        placeholder="" value="<?php echo $PayDate; ?>"
                                                        autocomplete="off" required onchange="getCollectionsMonth(document.getElementById('RecId').value)">
                                                    <div class="clearfix"></div>
                                                </div>


                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
                                                        <option selected="" disabled="" value="">Select Payment Type</option>
                                                        <option <?php if ($row7['PayMode'] == 'Cash') { ?> selected <?php } ?> value="Cash">Cash</option>
                                                        <option <?php if ($row7['PayMode'] == 'Cheque') { ?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
                                                        <option <?php if ($row7['PayMode'] == 'UPI') { ?> selected <?php } ?> value="UPI">UPI</option>
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
                                                <div class="form-group col-md-12">
                                                    <label class="form-label">Narration</label>
                                                    <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
                                                    <div class="clearfix"></div>
                                                </div>


                                            </div>

                                            <button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Submit</button>
                                            <span id="error_msg" style="color:red;"></span>

                                        </form>
                                    </div>
                                    <div class="col-lg-4" id="custresult">


                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
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

                    <?php include_once 'footer.php'; ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>
    <script>
        function getPayType(val) {
            if (val == 'Cheque') {
                $('.chequeoption').show();
                $('.upioption').hide();
            } else if (val == 'UPI') {
                $('.chequeoption').hide();
                $('.upioption').show();
            } else {
                $('.chequeoption').hide();
                $('.upioption').hide();
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
                    url: "ajax_files/ajax_customer_account.php",
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
                url: "ajax_files/ajax_customer_account.php",
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
                url: "ajax_files/ajax_customer_account.php",
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
                url: "ajax_files/ajax_customer_account.php",
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