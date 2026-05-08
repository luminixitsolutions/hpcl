<?php 
session_start();
include_once '../config.php';
//include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Selling-Products";
$Page = "Products";

$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cust_products_2025 WHERE id='$id'";
$row7 = getRecord($sql7);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Customer Product | <?php echo $Proj_Title; ?></title>

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
  border-radius: 16px;
  box-shadow: 0 3px 12px rgba(90, 60, 200, 0.08);
  padding: 10px 10px;
}
.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #5b3cc4;
  text-align: center;
  margin-bottom: 25px;
}
.form-label {
  font-weight: 600;
  font-size: 14px;
  color: #4a3aa0;
}
.form-control, .form-select {
  border: 1px solid #d8d1ff;
  border-radius: 10px;
  font-size: 14px;
  padding: 8px 10px;
  box-shadow: none;
  transition: all 0.2s ease-in-out;
}
.form-control:focus, .form-select:focus {
  border-color: #7a5fff;
  box-shadow: 0 0 0 0.2rem rgba(122, 95, 255, 0.15);
}
.btn-primary {
  background-color: #6a4fe0 !important;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 600;
  padding: 10px 20px;
}
.btn-primary:hover {
  background-color: #5a41c6 !important;
}
.btn-secondary {
  background-color: #b2bec3;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 600;
  padding: 10px 20px;
}
.section-title {
  background-color: #f5f2ff;
  border-left: 5px solid #6a4fe0;
  padding: 8px 12px;
  margin-bottom: 15px;
  font-weight: 600;
  color: #4a3aa0;
  border-radius: 6px;
}
@media (max-width: 768px) {
  .form-label { font-size: 13px; }
  .form-control, .form-select { font-size: 13px; }
  .btn-primary, .btn-secondary { width: 100%; margin-bottom: 8px; }
}
</style>
</head>

<body>

<div class="container-fluid">
  <div class="card">
   <!-- <div class="page-title"><i class="bi bi-box-seam"></i> Add / Edit Customer Product</div>-->

    <form action="../ajax_files/ajax_customer_products.php" method="POST" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" name="action" value="Add">
      <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999); ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"> 
       <input type="hidden" name="userid" id="userid" value="<?php echo $_GET['user_id']; ?>"> 

      <!-- Basic Info -->
      <div class="section-title"><i class="bi bi-info-circle"></i> Basic Information</div>
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label">Product Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="ProductName" value="<?php echo $row7["ProductName"]; ?>" required>
        </div>

        <!--<div class="col-md-3">
          <label class="form-label">Brand / Vendor <span class="text-danger">*</span></label>
          <select class="form-select" id="BrandId" name="BrandId" required>
            <option value="">Select Brand</option>
            <?php 
              $q = "select * from tbl_users WHERE Status='1' AND Roll=3";
              $r = $conn->query($q);
              while($rw = $r->fetch_assoc()) {
                $selected = ($row7['BrandId'] == $rw['id']) ? 'selected' : '';
                echo "<option $selected value='{$rw['id']}'>{$rw['Fname']}</option>";
              }
            ?>
          </select>
        </div>-->

        <div class="col-md-3">
          <label class="form-label">Category <span class="text-danger">*</span></label>
          <select class="form-select" id="CatId" name="CatId" required>
            <option value="">Select Category</option>
            <?php 
              $q = "select * from tbl_cust_category_2025 WHERE Status='1' AND ProdType=0";
              $r = $conn->query($q);
              while($rw = $r->fetch_assoc()) {
                $selected = ($row7['CatId'] == $rw['id']) ? 'selected' : '';
                echo "<option $selected value='{$rw['id']}'>{$rw['Name']}</option>";
              }
            ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Sub Category</label>
          <select class="form-select" id="SubCatId" name="SubCatId">
            <option value="">Select Sub Category</option>
            <?php 
              $q = "select * from tbl_cust_sub_category_2025 WHERE Status='1' AND CatId='".$row7['CatId']."' AND ProdType=0";
              $r = $conn->query($q);
              while($rw = $r->fetch_assoc()) {
                $selected = ($row7['SubCatId'] == $rw['id']) ? 'selected' : '';
                echo "<option $selected value='{$rw['id']}'>{$rw['Name']}</option>";
              }
            ?>
          </select>
        </div>
      </div>

      <!-- Pricing Section -->
      <div class="section-title mt-4"><i class="bi bi-currency-rupee"></i> Pricing Details</div>
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">Purchase Price (₹)</label>
          <input type="text" id="PurchasePrice" name="PurchasePrice" class="form-control" value="<?php echo $row7["PurchasePrice"]; ?>" required onkeypress="return isNumberKey(event)">
        </div>

        <div class="col-md-2">
          <label class="form-label">MRP (₹)</label>
          <input type="text" id="SubTotal" name="SubTotal" class="form-control" value="<?php echo $row7["SubTotal"]; ?>" required oninput="getProdPrice()">
        </div>

        <div class="col-md-2">
          <label class="form-label">Discount %</label>
          <input type="text" id="DiscPer" name="DiscPer" class="form-control" value="<?php echo $row7["DiscPer"]; ?>" oninput="getProdPrice()">
        </div>

        <div class="col-md-2">
          <label class="form-label">Final Price (₹)</label>
          <input type="text" id="MinPrice" name="MinPrice" class="form-control" value="<?php echo $row7["MinPrice"]; ?>" readonly>
        </div>
        
      <div class="form-group col-lg-1">
  <label class="form-label">CGST%<span class="text-danger">*</span></label>
  <div class="input-group">
    <input type="text" id="CgstPer" name="CgstPer" class="form-control"
      value="<?php echo $row7['CgstPer'] ?? '2.5'; ?>" required
      onKeyPress="return isNumberKey(event)" oninput="getProdPrice()" />
  </div>
</div>

<div class="form-group col-lg-1">
  <label class="form-label">SGST%<span class="text-danger">*</span></label>
  <div class="input-group">
    <input type="text" id="SgstPer" name="SgstPer" class="form-control"
      value="<?php echo $row7['SgstPer'] ?? '2.5'; ?>" required
      onKeyPress="return isNumberKey(event)" oninput="getProdPrice()" />
  </div>
</div>

<div class="form-group col-lg-1">
  <label class="form-label">IGST%<span class="text-danger">*</span></label>
  <div class="input-group">
    <input type="text" id="IgstPer" name="IgstPer" class="form-control"
      value="<?php echo $row7['IgstPer'] ?? '0'; ?>" required
      onKeyPress="return isNumberKey(event)" oninput="getProdPrice()" />
  </div>
</div>

<div class="form-group col-lg-1">
  <label class="form-label">Total GST%<span class="text-danger">*</span></label>
  <div class="input-group">
    <input type="text" id="GstPer" name="GstPer" class="form-control"
      value="<?php echo $row7['GstPer'] ?? '5.0'; ?>" readonly required />
  </div>
</div>



<!-- Hidden calculated amounts -->
<input type="hidden" id="CgstAmt" name="CgstAmt" value="<?php echo $row7['CgstAmt']; ?>">
<input type="hidden" id="SgstAmt" name="SgstAmt" value="<?php echo $row7['SgstAmt']; ?>">
<input type="hidden" id="IgstAmt" name="IgstAmt" value="<?php echo $row7['IgstAmt']; ?>">


        <div class="col-md-2">
          <label class="form-label">GST Amount (₹)</label>
          <input type="text" id="GstAmt" name="GstAmt" class="form-control" value="<?php echo $row7["GstAmt"]; ?>" readonly>
        </div>

        <div class="col-md-2">
          <label class="form-label">Price Without GST (₹)</label>
          <input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="<?php echo $row7["ProdPrice"]; ?>" readonly>
        </div>
      </div>

      <!-- Other Settings -->
      <div class="section-title mt-4"><i class="bi bi-gear"></i> Additional Settings</div>
      <div class="row g-3">
        <!--<div class="col-md-2">
          <label class="form-label">Unit</label>
          <select class="form-select" id="Unit" name="Unit">
            <option value="">Select</option>
            <?php
              $sql4 = "SELECT Name AS Unit,id FROM tbl_units_2025";
              $row4 = getList($sql4);
              foreach ($row4 as $result) {
                $selected = ($row7['Unit'] == $result['Unit']) ? 'selected' : '';
                echo "<option $selected value='{$result['Unit']}'>{$result['Unit']}</option>";
              }
            ?>
          </select>
        </div>-->

        <div class="col-md-2">
          <label class="form-label">Barcode No</label>
          <input type="text" class="form-control" name="BarcodeNo" value="<?php echo $row7["BarcodeNo"]; ?>">
        </div>

        <div class="col-md-2">
          <label class="form-label">Min Stock Qty</label>
          <input type="text" class="form-control" name="MinQty" value="<?php echo $row7["MinQty"]; ?>">
        </div>
            
            <?php if($_GET['id']==''){?>
         <div class="col-md-2">
          <label class="form-label">Stock Qty</label>
          <input type="text" class="form-control" name="StockQty" value="<?php echo $row7["StockQty"]; ?>">
        </div>
        <?php } ?>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          <select class="form-select" name="Status">
            <option value="1" <?php if($row7["Status"]=='1') echo 'selected'; ?>>Publish</option>
            <option value="0" <?php if($row7["Status"]=='0') echo 'selected'; ?>>Not Publish</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Product Type</label>
          <select class="form-select" name="ProdType2">
            <option value="1" <?php if($row7["ProdType2"]=='1') echo 'selected'; ?>>MRP Product</option>
          </select>
        </div>

       <!-- <div class="col-md-2">
          <label class="form-label">Transfer Product</label>
          <select class="form-select" name="Transfer">
            <option value="1" <?php if($row7["Transfer"]=='1') echo 'selected'; ?>>Yes</option>
            <option value="0" <?php if($row7["Transfer"]=='0') echo 'selected'; ?>>No</option>
          </select>
        </div>-->
      </div>

      <!-- Buttons -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-save"></i> Save Product</button>
       <!-- <a href="view-customer-products.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>-->
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
}

function getProdPrice() {
  var SubTotal = parseFloat($('#SubTotal').val()) || 0;
  var DiscPer = parseFloat($('#DiscPer').val()) || 0;
  var CgstPer = parseFloat($('#CgstPer').val()) || 0;
  var SgstPer = parseFloat($('#SgstPer').val()) || 0;
  var IgstPer = parseFloat($('#IgstPer').val()) || 0;

  // 🔹 Calculate total GST%
  var GstPer = CgstPer + SgstPer + IgstPer;
  $('#GstPer').val(GstPer.toFixed(2));

  // 🔹 Calculate discount
  var DiscAmt = (SubTotal * DiscPer) / 100;
  var FinalPrice = SubTotal - DiscAmt;

  // 🔹 Calculate GST exclusive price
  var MinPrice = FinalPrice / (1 + GstPer / 100);
  var GstAmt = FinalPrice - MinPrice;

  // 🔹 Split GST amount equally into CGST/SGST if IGST = 0
  var CgstAmt = 0, SgstAmt = 0, IgstAmt = 0;
  if (IgstPer == 0) {
    CgstAmt = GstAmt / 2;
    SgstAmt = GstAmt / 2;
  } else {
    IgstAmt = GstAmt;
  }

  // 🔹 Set calculated values
  $('#Discount').val(DiscAmt.toFixed(2));
  $('#MinPrice').val(FinalPrice.toFixed(2));
  $('#ProdPrice').val(MinPrice.toFixed(2));
  $('#GstAmt').val(GstAmt.toFixed(2));
  $('#CgstAmt').val(CgstAmt.toFixed(2));
  $('#SgstAmt').val(SgstAmt.toFixed(2));
  $('#IgstAmt').val(IgstAmt.toFixed(2));
}



$('#CatId').on('change', function() {
  var val = this.value;
  $.ajax({
    url: "../ajax_files/ajax_dropdown.php",
    method: "POST",
    data: { action: "getSubCat", id: val },
    success: function(data) {
      $('#SubCatId').html(data);
    }
  });
});
</script>
</body>
</html>
