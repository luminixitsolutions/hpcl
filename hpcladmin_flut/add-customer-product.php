<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Selling-Products";
$Page = "Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Add Products</title>
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

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add Customer product</h4>

<?php 
$id = $_GET['id'] ?? '';
$sql7 = "SELECT * FROM tbl_cust_products2 WHERE id='$id'";
$row7 = getRecord($sql7);
if (!$row7) {
    $row7 = [];
}
$row7 = array_merge([
    'ProductName' => '', 'BrandId' => '', 'CatId' => '', 'SubCatId' => '',
    'PurchasePrice' => '', 'SubTotal' => '', 'DiscPer' => '', 'Discount' => '',
    'MinPrice' => '', 'CgstPer' => '', 'SgstPer' => '', 'IgstPer' => '',
    'GstAmt' => '', 'ProdPrice' => '', 'CgstAmt' => '', 'SgstAmt' => '', 'IgstAmt' => '',
    'Unit' => '', 'BarcodeNo' => '', 'MinQty' => '', 'Status' => '1',
    'ProdType2' => '1', 'Transfer' => '1', 'SrNo' => '', 'Photo' => '',
], $row7);
?>
<form action="ajax_files/ajax_customer_products" method="POST" autocomplete="off">
    
<!--<ul class="card px-4 pt-3 mb-3">
<li>
<a href="#smartwizard-6-step-1" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">1</span>
<div class="text-muted small">FIRST STEP</div>
Basic Info
</a>
</li>
<li>
<a href="#smartwizard-6-step-2" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">2</span>
<div class="text-muted small">SECOND STEP</div>
Description
</a>
</li>
<li>
<a href="#smartwizard-6-step-3" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">3</span>
<div class="text-muted small">THIRD STEP</div>
Product Images
</a>
</li>
 <li>
<a href="#smartwizard-6-step-4" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">4</span>
<div class="text-muted small">FOURTH STEP</div>
Other Products
</a>
</li>

</ul>-->
<div class="mb-3">
<div id="" class="card animated fadeIn">
<div class="card-body">
    <input type="hidden" name="form_action" value="Add">
    <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999);?>">
    <input type="hidden" name="id" id="id" value="<?php echo htmlspecialchars($id); ?>"/>
     <div class="form-row">
<div class="form-group col-lg-5">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="<?php echo $row7["ProductName"]; ?>" required="">
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-3">
<label class="form-label">Brand/Vendor <span class="text-danger">*</span></label>
  <select class="select2-demo form-control" id="BrandId" name="BrandId" required>
<option selected="" disabled="" value="">Select Brand</option>
<?php 
        $q = "select * from tbl_users WHERE Status='1' AND Roll=3";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['BrandId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

 
<div class="form-group col-lg-2">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from tbl_cust_category_2025 WHERE Status='1' AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['CatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Sub Category </label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" disabled="" value="">Select Sub Category</option>
<?php 
        $q = "select * from tbl_cust_sub_category_2025 WHERE Status='1' AND CatId='".$row7['CatId']."' AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['SubCatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
<div class="clearfix"></div>
</div>


<!--<div class="form-group col-lg-2">
<label class="form-label">Division </label>
  <select class="form-control" id="Division" name="Division" >
<option selected="" disabled="" value="">Select Division</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['Division']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Segment </label>
  <select class="form-control" id="Segment" name="Segment" >
<option selected="" disabled="" value="">Select Segment</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['Segment']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Family </label>
  <select class="form-control" id="Family" name="Family" >
<option selected="" disabled="" value="">Select Family</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=5";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['Family']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Class </label>
  <select class="form-control" id="ClassId" name="ClassId" >
<option selected="" disabled="" value="">Select Class</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=6";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['ClassId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Mc. Desc </label>
  <select class="form-control" id="McDesc" name="McDesc" >
<option selected="" disabled="" value="">Select Mc. Desc</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['McDesc']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Brand Desc</label>
  <select class="form-control" id="BrandDesc" name="BrandDesc" >
<option selected="" disabled="" value="">Select Brand Desc</option>
<?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=8";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['BrandDesc']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>-->

</div>



 <div class="form-row">
<div class="form-group col-lg-2">
<label class="form-label">Purchase Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="PurchasePrice" name="PurchasePrice" class="form-control" value="<?php echo $row7["PurchasePrice"]; ?>" required="" onKeyPress="return isNumberKey(event)" required>
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-1">
<label class="form-label">MRP Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="SubTotal" name="SubTotal" class="form-control" value="<?php echo $row7["SubTotal"]; ?>" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)" required>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Discount %<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="DiscPer" name="DiscPer" class="form-control" value="<?php echo $row7["DiscPer"]; ?>" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)" required>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
<div class="clearfix"></div>
</div>
</div>

<input type="hidden" id="Discount" name="Discount" value="<?php echo $row7["Discount"];?>">

<div class="form-group col-lg-1">
<label class="form-label">Final Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="<?php echo $row7["MinPrice"]; ?>" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)" readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="<?php echo (empty($id) ? '2.5' : $row7['CgstPer']); ?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="<?php echo (empty($id) ? '2.5' : $row7['SgstPer']); ?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="<?php echo (empty($id) ? '2.5' : $row7['IgstPer']); ?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('DiscPer').value,document.getElementById('SubTotal').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Total GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="GstAmt" name="GstAmt" class="form-control" value="<?php echo $row7["GstAmt"]; ?>" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>

<input type="hidden" id="CgstAmt" name="CgstAmt" value="<?php echo $row7["CgstAmt"];?>">
<input type="hidden" id="SgstAmt" name="SgstAmt" value="<?php echo $row7["SgstAmt"];?>">
<input type="hidden" id="IgstAmt" name="IgstAmt" value="<?php echo $row7["IgstAmt"];?>">



<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="<?php echo $row7["ProdPrice"]; ?>" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">Unit</label>
 <select class="form-control" id="Unit" name="Unit" >
<option selected="" value="">...</option>
<?php
                $sql4 = "SELECT Name AS Unit,id FROM tbl_units_2025";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
<option <?php if($row7['Unit']==$result['Unit']){ ?> selected <?php } ?> value="<?php echo $result['Unit']; ?>"><?php echo $result['Unit']; ?></option>
              <?php } ?></select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Barcode No</label>
<input type="text" class="form-control" name="BarcodeNo" value="<?php echo $row7["BarcodeNo"]; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Min Stock Qty</label>
<input type="text" class="form-control" name="MinQty" value="<?php echo $row7["MinQty"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Publish</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Not Publish</option>
</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Product Type<span class="text-danger">*</span></label>
<select class="form-control" name="ProdType2" required="">
<option value="1" <?php if($row7["ProdType2"]=='1') {?> selected <?php } ?>>MRP Product</option>

</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Transfer Product<span class="text-danger">*</span></label>
<select class="form-control" name="Transfer" required="">
<option value="1" <?php if($row7["Transfer"]=='1') {?> selected <?php } ?>>Yes</option>
<option value="0" <?php if($row7["Transfer"]=='0') {?> selected <?php } ?>>No</option>
</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Sr No</label>
<input type="text" class="form-control" name="SrNo" value="<?php echo $row7["SrNo"]; ?>">
<div class="clearfix"></div>
</div>

<!--<div class="form-group col-lg-2">
<label class="form-label">Opening Stock Qty<span class="text-danger">*</span></label>
<input type="number" class="form-control" name="StockQty" value="<?php echo $row7["StockQty"]; ?>" required="" min="0">
<div class="clearfix"></div>
</div>-->

<!--<div class="form-group col-lg-12">
  <label class="form-label">Product Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto" value="<?php echo $row7["Photo"]; ?>">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>-->

</div>






 <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>

                
                                    </div>
</div>
</div>


</div>
</form>


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

    function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


function getProdPrice(CgstPer, SgstPer, IgstPer, DiscPer, SubTotal) {

    CgstPer   = Number(CgstPer);
    SgstPer   = Number(SgstPer);
    IgstPer   = Number(IgstPer);
    DiscPer   = Number(DiscPer);
    SubTotal  = Number(SubTotal);

    // --- Discount Amount ---
    var DiscAmt = SubTotal * (DiscPer / 100);
    $('#Discount').val(DiscAmt.toFixed(2));

    // --- Final Price after Discount ---
    var FinalPrice = SubTotal - DiscAmt;
    $('#MinPrice').val(FinalPrice.toFixed(2));

    // --- Total GST percentage ---
    var TotalGstPer = IgstPer > 0 ? IgstPer : (CgstPer + SgstPer);

    // --- Price before GST ---
    var MinPrice = FinalPrice / (1 + (TotalGstPer / 100));
    $('#ProdPrice').val(MinPrice.toFixed(2));

    // --- GST Amount ---
    var GstAmt = FinalPrice - MinPrice;
    $('#GstAmt').val(GstAmt.toFixed(2));

    // --- CGST / SGST Split ---
    if (IgstPer > 0) {
        $('#CgstAmt').val(0);
        $('#SgstAmt').val(0);
        $('#IgstAmt').val(GstAmt.toFixed(2));
    } else {
        var Half = GstAmt / 2;
        $('#CgstAmt').val(Half.toFixed(2));
        $('#SgstAmt').val(Half.toFixed(2));
        $('#IgstAmt').val(0);
    }
}



   $(document).ready(function() {


   $(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getSubCat";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#SubCatId').html(data);
    }
    });

    

 });

       
 
   });
</script>
</body>
</html>
