<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Customer-Products-2025';
$Page = 'Add-Customer-Products-2025';
$row7 = ['MinQty' => '0', 'ProdType2' => '1', 'Transfer' => '1'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Add Selling Product</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>
<div class="layout-container">
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add Selling Product</h4>

<form action="ajax_files/ajax_customer_products.php" method="POST" enctype="multipart/form-data" autocomplete="off">
<div class="mb-3">
<div class="card animated fadeIn">
<div class="card-body">
<input type="hidden" name="action" value="Add2025">
<input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000, 99999); ?>">

<div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Product Name <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" required>
</div>
<div class="form-group col-lg-3">
<label class="form-label">Category <span class="text-danger">*</span></label>
<select class="form-control" id="CatId" name="CatId" required>
<option selected disabled value="">Select Category</option>
<?php
$q = "SELECT * FROM tbl_cust_category_2025 WHERE Status='1' AND ProdType='0' ORDER BY Name";
$r = $conn->query($q);
while ($rw = $r->fetch_assoc()) {
    echo '<option value="' . (int)$rw['id'] . '">' . htmlspecialchars($rw['Name']) . '</option>';
}
?>
</select>
</div>
<div class="form-group col-lg-3">
<label class="form-label">Sub Category</label>
<select class="form-control" id="SubCatId" name="SubCatId">
<option selected disabled value="">Select Sub Category</option>
</select>
</div>
</div>

<div class="form-row">
<div class="form-group col-lg-2">
<label class="form-label">Purchase Price <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">&#8377;</div></div>
<input type="text" id="PurchasePrice" name="PurchasePrice" class="form-control" required onkeypress="return isNumberKey(event)">
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Total Price <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">&#8377;</div></div>
<input type="text" id="SubTotal" name="SubTotal" class="form-control" required onkeypress="return isNumberKey(event)" oninput="syncSellPrice(); getProdPrice(this.value, document.getElementById('CgstPer').value, document.getElementById('SgstPer').value, document.getElementById('IgstPer').value);">
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Discount %</label>
<input type="text" id="DiscPer" name="DiscPer" class="form-control" value="0" onkeypress="return isNumberKey(event)" oninput="syncSellPrice()">
</div>
<div class="form-group col-lg-2">
<label class="form-label">Discount Amt</label>
<input type="text" id="Discount" name="Discount" class="form-control" value="0" onkeypress="return isNumberKey(event)" oninput="syncSellPrice()">
</div>
<div class="form-group col-lg-2">
<label class="form-label">Sell Price <span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">&#8377;</div></div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" required readonly>
</div>
</div>
<div class="form-group col-lg-1">
<label class="form-label">CGST%</label>
<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="0" onkeypress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('SubTotal').value, this.value, document.getElementById('SgstPer').value, document.getElementById('IgstPer').value)">
</div>
<input type="hidden" id="CgstAmt" name="CgstAmt">
<input type="hidden" id="SgstAmt" name="SgstAmt">
<input type="hidden" id="IgstAmt" name="IgstAmt">
<div class="form-group col-lg-1">
<label class="form-label">SGST%</label>
<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="0" onkeypress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('SubTotal').value, document.getElementById('CgstPer').value, this.value, document.getElementById('IgstPer').value)">
</div>
<div class="form-group col-lg-2">
<label class="form-label">IGST%</label>
<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="0" onkeypress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('SubTotal').value, document.getElementById('CgstPer').value, document.getElementById('SgstPer').value, this.value)">
</div>
<div class="form-group col-lg-2">
<label class="form-label">Total GST</label>
<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">&#8377;</div></div>
<input type="text" id="GstAmt" name="GstAmt" class="form-control" readonly>
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST</label>
<div class="input-group">
<div class="input-group-prepend"><div class="input-group-text">&#8377;</div></div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" readonly>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Barcode No</label>
<input type="text" class="form-control" name="BarcodeNo">
</div>
<div class="form-group col-lg-2">
<label class="form-label">Min Stock Qty <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="MinQty" value="0" required>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Status <span class="text-danger">*</span></label>
<select class="form-control" name="Status" required>
<option value="1">Publish</option>
<option value="0">Not Publish</option>
</select>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Product Type <span class="text-danger">*</span></label>
<select class="form-control" name="ProdType2" required>
<option value="1">MRP Product</option>
<option value="2">Making Product</option>
</select>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Transfer Product <span class="text-danger">*</span></label>
<select class="form-control" name="Transfer" required>
<option value="1">Yes</option>
<option value="0">No</option>
</select>
</div>
<div class="form-group col-lg-1">
<label class="form-label">Sr No <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="SrNo" value="0" required>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Opening Stock Qty</label>
<input type="number" class="form-control" name="StockQty" value="0" min="0">
</div>
<div class="form-group col-md-5">
<label class="form-label">Product Image</label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-2">
<button type="submit" class="btn btn-primary">Save</button>
<a href="view-customer-products-2025.php" class="btn btn-secondary">Cancel</a>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once 'footer_script.php'; ?>
<script>
function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode !== 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}
function syncSellPrice() {
    var total = Number(document.getElementById('SubTotal').value || 0);
    var discPer = Number(document.getElementById('DiscPer').value || 0);
    var discAmt = Number(document.getElementById('Discount').value || 0);
    if (discPer > 0) discAmt = total * discPer / 100;
    else document.getElementById('Discount').value = discAmt.toFixed(2);
    var sell = total - discAmt;
    if (sell < 0) sell = 0;
    document.getElementById('MinPrice').value = sell.toFixed(2);
}
function getProdPrice(prodprice, cgstper, sgstper, igstper) {
    var CgstAmt = Number(prodprice) * (Number(cgstper) / 100);
    var SgstAmt = Number(prodprice) * (Number(sgstper) / 100);
    var IgstAmt = Number(prodprice) * (Number(igstper) / 100);
    $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
    $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
    $('#IgstAmt').val(parseFloat(IgstAmt).toFixed(2));
    var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
    $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
    $('#ProdPrice').val(parseFloat(Number(prodprice) - GstAmt).toFixed(2));
}
$(document).ready(function() {
    $(document).on('change', '#CatId', function() {
        $.ajax({
            url: 'ajax_files/ajax_dropdown.php',
            method: 'POST',
            data: { action: 'getSubCat2025', id: this.value },
            success: function(data) { $('#SubCatId').html(data); }
        });
    });
});
</script>
</body>
</html>
