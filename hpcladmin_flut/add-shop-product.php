<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="E-Commerce";
$Page = "Add-Product";
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
<h4 class="font-weight-bold py-3 mb-0">Add product</h4>


<form action="ajax_files/ajax_shop_products.php" method="POST" enctype="multipart/form-data" autocomplete="off">
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
<div class="form-group">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="" required="">
<div class="clearfix"></div>
</div>
  <div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from category WHERE Status='1' AND Roll!=2";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        if($rw['Roll'] == 1){
            $Roll = "Franchise";  
          }
          else if($rw['Roll'] == 2){
            $Roll = "Employee";  
          }
          else if($rw['Roll'] == 4){
            $Roll = "Customer";  
          }
          else{
              $Roll = "All";
          }
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Sub Category</label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" value="0">Select Sub Category</option>
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
<input type="text" id="PurchaseProdPrice" name="PurchaseProdPrice" class="form-control" value="" required="">
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Sell Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<div class="clearfix"></div>
</div>
</div>



<div class="form-group col-lg-1">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<!-- <div class="input-group-prepend">
<div class="input-group-text">%</div>
</div> -->
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<!-- <div class="input-group-prepend">
<div class="input-group-text">%</div>
</div> -->
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<!-- <div class="input-group-prepend">
<div class="input-group-text">%</div>
</div> -->
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

<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>






<!-- <div class="form-group col-lg-4">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice" name="OfferPrice" class="form-control" value="" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-4">
<label class="form-label">Offer Percentage<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer" name="OfferPer" class="form-control" value="" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div> -->

</div>

<br>

<!--<div class="form-row">
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Featured" value="1" class="custom-control-input">
<span class="custom-control-label">Featured</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="FreeShipping" value="1" class="custom-control-input">
<span class="custom-control-label">Free Shipping</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Bestseller" value="1" class="custom-control-input">
<span class="custom-control-label">Bestseller</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Trending" value="1" class="custom-control-input">
<span class="custom-control-label">Show In Home Page</span>
</label>
</div>


</div>-->



 



<div class="form-row">
<div class="form-group col-lg-4">
<label class="form-label">Product For<span class="text-danger">*</span></label>
<select class="form-control" name="ProdFor" required="">
<option value="1">Franchise</option>
<!--<option value="4">Customer</option>
<option value="2">Employee</option>-->
<!--<option value="3">Both</option>-->
</select>
</div>
<div class="form-group col-lg-4">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="Stock" required="">
<option value="1">Instock</option>
<option value="0">Out of stock</option>
</select>
</div>
<div class="form-group col-lg-4">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1">Publish</option>
<option value="0">Not Publish</option>
</select>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Product Details <span class="text-danger">*</span></label>
<textarea class="form-control" rows="10" name="Details" autocomplete="off" required="required" id="editor1"></textarea>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;" required="">
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Product Image (Multiple) <span class="text-danger">(File size must be less than 2 MB)</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo2" name="Files[]" style="opacity: 1;" multiple="">
<span class="custom-file-label"></span>
</label>
</div>

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
CKEDITOR.replace( 'editor1' );
    function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

/*function getProdPrice(minprice,cgstper,sgstper,igstper){
  var CgstAmt2 = Number(minprice) / (1+ Number(cgstper) / 100);
  var CgstAmt = Number(minprice) - Number(CgstAmt2);
  var SgstAmt2 = Number(minprice) / (1+ Number(sgstper) / 100);
  var SgstAmt = Number(minprice) - Number(SgstAmt2);
  var IgstAmt2 = Number(minprice) / (1+ Number(igstper) / 100);
  var IgstAmt = Number(minprice) - Number(IgstAmt2);
  var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
  $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
  var ProdPrice = Number(minprice) - Number(GstAmt);
  $('#ProdPrice').val(parseFloat(ProdPrice).toFixed(2));
}*/

function getProdPrice(prodprice,cgstper,sgstper,igstper){
  var CgstAmt = Number(prodprice) * (Number(cgstper) / 100);
  var SgstAmt = Number(prodprice) * (Number(sgstper) / 100);
  var IgstAmt = Number(prodprice) * (Number(igstper) / 100);
  var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
  $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
  var MinPrice = Number(prodprice) - Number(GstAmt);
  $('#ProdPrice').val(parseFloat(MinPrice).toFixed(2));
}

function getAttrValue(id,val){
 var action = "getAttrValue";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AttrValue'+id).html(data);
    }
    });
}

function getBrands(id){
  var action = "getBrands";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:id},
    success:function(data)
    {
      $('#BrandId').html(data);
    }
    });
}
function calculate(){
   var MinPrice = $('#MinPrice').val();
    var MaxPrice = $('#MaxPrice').val();
    var OfferPrice = Number(MaxPrice) - Number(MinPrice);
    $('#OfferPrice').val(parseFloat(OfferPrice).toFixed(2));
     var perc="";
            if(isNaN(MinPrice) || isNaN(MaxPrice)){
                perc=" ";
               }else{
               perc = Math.trunc(((MaxPrice-MinPrice)/MaxPrice * 100));
               }
            
            $('#OfferPer').val(perc);
}
   $(document).ready(function() {

 $(document).on("input", "#MinPrice", function(event){
   calculate();

  });

 $(document).on("input", "#MaxPrice", function(event){
   calculate();

  });

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

        var action2 = 'view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action2,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
      var action3 = 'getAttributes';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action3,id:val},  
  success: function(data){
      $('#attributes').html(data);
      
  }
  });

    //getBrands(val);

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
