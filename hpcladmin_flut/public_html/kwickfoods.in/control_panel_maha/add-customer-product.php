<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Customer-Products";
$Page = "Add-Customer-Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
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

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add Customer product</h4>


<form action="ajax_files/ajax_customer_products.php" method="POST" enctype="multipart/form-data" autocomplete="off">
    
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
    <input type="hidden" name="action" value="Add">
    <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999);?>">
     <div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="" required="">
<div class="clearfix"></div>
</div>
 
<div class="form-group col-lg-3">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from tbl_cust_category WHERE Status='1' AND CreatedBy IN ('$BillSoftFrId')";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Sub Category <span class="text-danger">*</span></label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" disabled="" value="">Select Sub Category</option>
</select>
<div class="clearfix"></div>
</div>

</div>



 <div class="form-row">
<div class="form-group col-lg-2">
<label class="form-label">Purchase Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="PurchasePrice" name="PurchasePrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" required>
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Total Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)" required>
<div class="clearfix"></div>
</div>
</div>


<div class="form-group col-lg-1">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="0" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>
<input type="hidden" id="CgstAmt" name="CgstAmt">
<input type="hidden" id="SgstAmt" name="SgstAmt">
<input type="hidden" id="IgstAmt" name="IgstAmt">
<div class="form-group col-lg-1">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="0" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="0" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Total GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="GstAmt" name="GstAmt" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="" required="" readonly>
<div class="clearfix"></div>
</div>
</div>



<div class="form-group col-lg-3">
<label class="form-label">Barcode No</label>
<input type="text" class="form-control" name="BarcodeNo" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Min Stock Qty<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="MinQty" value="<?php echo $row7["MinQty"]; ?>" required="">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1">Publish</option>
<option value="0">Not Publish</option>
</select>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Product Type<span class="text-danger">*</span></label>
<select class="form-control" name="ProdType2" required="">
<option value="1" <?php if($row7["ProdType2"]=='1') {?> selected <?php } ?>>MRP Product</option>
<option value="2" <?php if($row7["ProdType2"]=='2') {?> selected <?php } ?>>Raw Product</option>
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
<label class="form-label">Sr No<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="SrNo" value="" required="">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Opening Stock Qty<span class="text-danger">*</span></label>
<input type="number" class="form-control" name="StockQty" value="1" required="" min="0">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;"  >
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
</div>

</div>




<div class="form-row">





<!--<div class="form-group col-md-12">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;" onclick="uploadImageSingle(document.getElementById('TempPrdId').value)" >
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
</div>-->

<!--<div class="form-group col-md-12">
  <label class="form-label">Product Image (Multiple) <span class="text-danger">(File size must be less than 2 MB)</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo2" name="Files[]" style="opacity: 1;" multiple="" onclick="uploadImageMultiple(document.getElementById('TempPrdId').value)">
<span class="custom-file-label"></span>
</label>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Pdf File <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" style="opacity: 1;" onclick="uploadFileSingle(document.getElementById('TempPrdId').value)" >
<span class="custom-file-label"></span>
</label>
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
function uploadImageSingle(prdid){
    //alert(prdid);
    var action = "save";
    var pageval = "product";
    Android.uploadImageSingle(''+prdid+'',''+action+'',''+pageval+'');
}

function uploadImageMultiple(prdid){
    //alert(prdid);
    var action = "save";
    var pageval = "product";
    Android.uploadImageMultiple(''+prdid+'',''+action+'',''+pageval+'');
}

function uploadFileSingle(prdid){
    //alert(prdid);
    var action = "save";
    var pageval = "product";
    Android.uploadFileSingle(''+prdid+'',''+action+'',''+pageval+'');
}

function saveProductClick(prdid){
    //alert(prdid);
    var action = "save";
    var pageval = "product";
    //Android.saveProductClick(''+prdid+'',''+action+'',''+pageval+'');
    Android.saveProductClick(); 
}
    function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


function getProdPrice(prodprice,cgstper,sgstper,igstper){
  var CgstAmt = Number(prodprice) * (Number(cgstper) / 100);
  var SgstAmt = Number(prodprice) * (Number(sgstper) / 100);
  var IgstAmt = Number(prodprice) * (Number(igstper) / 100);
  $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
  $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
  $('#IgstAmt').val(parseFloat(IgstAmt).toFixed(2));
  var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
  $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
  var MinPrice = Number(prodprice) - Number(GstAmt);
  $('#ProdPrice').val(parseFloat(MinPrice).toFixed(2));
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

       /* $(document).on("change", "#SubCatId", function(event){
         var val = this.value;
         var CatId = $('#CatId').val();
    
     });*/

   $('.bs-markdown').markdown({
    iconlibrary: 'fa',
    footer: '<div id="md-character-footer"></div><small id="md-character-counter" class="text-muted">350 character left</small>',

    onChange: function(e) {
      var contentLength = e.getContent().length;

      if (contentLength > 350) {
        $('#md-character-counter')
          .removeClass('text-muted')
          .addClass('text-danger')
          .html((contentLength - 350) + ' character surplus.');
      } else {
        $('#md-character-counter')
          .removeClass('text-danger')
          .addClass('text-muted')
          .html((350 - contentLength) + ' character left.');
      }
    },
  });



   var i=1;  
      $('#add').click(function(){  
        alert(data);
           i++;  
             var action = "getMoreAttributes";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {

      
       $('#dynamic_field').append(data);
    }
    });
           
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
          

      });  

       
   });
</script>
</body>
</html>
