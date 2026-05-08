<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";
$url = "home.php";
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Maha Buddy</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <?php include 'inc-admin-css.php';?>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="analytics">
    <!-- screen loader -->
   

    <!-- menu main -->
    <?php include_once 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       <?php include 'top_header.php';?>

     

        <!-- page content start -->

        <div class="main-container" style="padding-top: 80px;">
            <div class="container">

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
     <div class="form-row">
<div class="form-group col-lg-8">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="" required="">
<div class="clearfix"></div>
</div>
 
<div class="form-group col-lg-4">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $FranchiseId = $_SESSION['FranchiseId'];
        $q = "select * from tbl_cust_category WHERE Status='1' AND CreatedBy='$FranchiseId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

</div>



 <div class="form-row">

<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" required  oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>
<input type="hidden" id="CgstAmt" name="CgstAmt">
<input type="hidden" id="SgstAmt" name="SgstAmt">
<input type="hidden" id="IgstAmt" name="IgstAmt">
<div class="form-group col-lg-2">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

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
<label class="form-label">Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)" required>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Barcode No<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="BarcodeNo" value="" required="">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1">Publish</option>
<option value="0">Not Publish</option>
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

</div>




<div class="form-row">





<div class="form-group col-md-12">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
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

            </div>
        </div>
    </main>


   <?php include_once 'footer.php';?>


    <!-- color settings style switcher -->
     <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>





    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- chart js-->
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/chartjs/utils.js"></script>
    <script src="vendor/chartjs/chart-js-data.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
    <?php include 'inc-admin-js.php';?>

    <script type="text/javascript">
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
  var MinPrice = Number(prodprice) + Number(GstAmt);
  $('#MinPrice').val(parseFloat(MinPrice).toFixed(2));
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
  url: "ajax_files/ajax_customer_products.php",
   data:{action:action2,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
      var action3 = 'getAttributes';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_customer_products.php",
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
