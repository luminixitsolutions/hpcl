<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="E-Commerce";
$Page = "Add-Product";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Edit Products</title>
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
<h4 class="font-weight-bold py-3 mb-0">Edit product</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Products</li>
<li class="breadcrumb-item active">Edit product</li>
</ol>
</div>
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM products WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$row7['Color'] = explode(",", $row7['Color']);
$row7['DdsOffers'] = explode(",",$row7['DdsOffers']);

if($_REQUEST["action"]=="color_photo_delete")
{
  $id = $_REQUEST["id"];
  $prdid = $_REQUEST["prdid"];
   $query2 = "SELECT Photo FROM temp_color WHERE id = '$id'";
    $row2 = getRecord($query2);
    $Photo2 = $row2['Photo'];
    $src2 = "../uploads/$Photo2";
        unlink($src2);
     $sql11 = "UPDATE temp_color SET Photo='' WHERE id = '$id'";
    $conn->query($sql11);?>
    <script type="text/javascript">
      alert("Photo Deleted Successfully!");
      window.location.href="edit-product.php?id=<?php echo $prdid;?>";
    </script>
<?php    

}  
?>
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
<div class="card animated fadeIn">
<div class="card-body">
     <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"/> 
    <input type="hidden" name="action" value="Edit">
<div class="form-group">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="<?php echo $row7["ProductName"]; ?>" required="">
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
                <option <?php if($row7['CatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']." (".$Roll.")"; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Sub Category</label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" value="0">Select Sub Category</option>
<?php 
    $CatId = $row7['CatId'];
        $q = "select * from sub_category WHERE CatId ='$CatId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['SubCatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } ?>
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
<input type="text" id="PurchaseProdPrice" name="PurchaseProdPrice" class="form-control" value="<?php echo $row7["PurchaseProdPrice"]; ?>" required="">
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="<?php echo $row7["ProdPrice"]; ?>" required="" onKeyPress="return isNumberKey(event)" required  oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<div class="clearfix"></div>
</div>
</div>



<div class="form-group col-lg-1">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="<?php echo $row7["CgstPer"]; ?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<!-- <div class="input-group-prepend">
<div class="input-group-text">%</div>
</div> -->
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="<?php echo $row7["SgstPer"]; ?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
<!-- <div class="input-group-prepend">
<div class="input-group-text">%</div>
</div> -->
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-1">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="<?php echo $row7["IgstPer"]; ?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('ProdPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">
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
<input type="text" id="GstAmt" name="GstAmt" class="form-control" value="<?php echo $row7["GstAmt"]; ?>" required="" onKeyPress="return isNumberKey(event)" readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="<?php echo $row7["MinPrice"]; ?>" required="" readonly>
<div class="clearfix"></div>
</div>
</div>

<!-- 
<div class="form-group col-lg-4">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice" name="OfferPrice" class="form-control" value="<?php echo $row7["OfferPrice"]; ?>" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-4">
<label class="form-label">Offer Percentage<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer" name="OfferPer" class="form-control" value="<?php echo $row7["OfferPer"]; ?>" readonly="">
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
<input type="checkbox" name="Featured" value="1" <?php if($row7["Featured"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Featured</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="FreeShipping" value="1" <?php if($row7["FreeShipping"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Free Shipping</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Bestseller" value="1" <?php if($row7["Bestseller"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Bestseller</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Trending" value="1" <?php if($row7["Trending"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Show In Home Page</span>
</label>
</div>

</div>-->





<div class="form-row">

<div class="form-group col-lg-4">
<label class="form-label">Product For<span class="text-danger">*</span></label>
<select class="form-control" name="ProdFor" required="">
<option value="1" <?php if($row7["ProdFor"]=='1') {?> selected <?php } ?>>Franchise</option>
<!--<option value="2" <?php if($row7["ProdFor"]=='2') {?> selected <?php } ?>>Employee</option>
<option value="4" <?php if($row7["ProdFor"]=='4') {?> selected <?php } ?>>Customer</option>
<option value="3" <?php if($row7["ProdFor"]=='3') {?> selected <?php } ?>>All</option>-->
</select>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="Stock" required="">
<option value="1" <?php if($row7["Stock"]=='1') {?> selected <?php } ?>>Instock</option>
<option value="0" <?php if($row7["Stock"]=='0') {?> selected <?php } ?>>Out of stock</option>
</select>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Publish</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Not Publish</option>
</select>
</div>
</div>

 <div class="form-row">
<div class="form-group col-md-12">
  <label class="form-label">Product Details <span class="text-danger">*</span></label>
<textarea class="form-control" rows="10" name="Details" autocomplete="off" required="required" id="editor1"><?php echo $row7["Details"]; ?></textarea>
</div>

<span class="text-danger">*</span> Mandatory
</div>

<div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
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
</div>
</div>

 <div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image (Multiple) <span class="text-danger">(File size must be less than 2 MB)</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo2" name="Files[]" style="opacity: 1;" multiple="">
<span class="custom-file-label"></span>
</label>
 <span id="show_photo2">
<?php 
  $id = $_GET['id'];
  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_GET["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} ?>
</span>
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

function getProdPrice(prodprice,cgstper,sgstper,igstper){
  var CgstAmt = Number(prodprice) * (Number(cgstper) / 100);
  var SgstAmt = Number(prodprice) * (Number(sgstper) / 100);
  var IgstAmt = Number(prodprice) * (Number(igstper) / 100);
  var GstAmt = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
  $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
  var MinPrice = Number(prodprice) + Number(GstAmt);
  $('#MinPrice').val(parseFloat(MinPrice).toFixed(2));
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

function showProdImages(pid){
  var action = "showProdImages";
    $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:pid},
    success:function(data)
    {
      $('#show_photo2').html(data);
    }
    });
}

function delete_photo2(id,pid){
  if(confirm("Are you sure you want to delete Product Photo?"))  
           {  
             var action = "deletePhoto2";
            
             var Photo = $('#OldMulImage'+id).val();
             $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:id,pid:pid,Photo:Photo},
    success:function(data)
    {
      
      $('#OldMulImage'+id).val('');
      showProdImages(pid);
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }
}

function deleteAttr(id,pid){
  if(confirm("Are you sure you want to delete?"))  
           {  
    var CatId = $('#CatId').val();
        var action = 'delete_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:id,pid:pid},  
  success: function(data){
      //$('#show_attr').html(data);
       showAttributes(pid,CatId);
      
  }
  });
    }
    else{}
}

function showAttributes(pid,CatId){
        var action = 'save_view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:pid,CatId:CatId},  
  success: function(data){
      $('#show_attr').html(data);
      
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

     var i=1;  
      $('#addMore').click(function(){  
        
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

    var pid = $('#ProdId').val();
    var val = $('#CatId').val();
    var rncnt = $('#rn_cnt').val();
    showAttributes(pid,val);
    if(rncnt > 0){
       var action = 'edit_view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val,rncnt:rncnt},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
    }
    else{
         var action = 'view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
    }
    
       
    

 $(document).on("input", "#MinPrice", function(event){
   calculate();

  });

 $(document).on("input", "#MaxPrice", function(event){
   calculate();

  });

/*$("#Photo2").on("change", function() {
    if ($("#Photo2")[0].files.length > 4) {
        alert("You can select only 5 images");
    } else {
        
    }
});*/
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

     var action = 'view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
       var action = 'getAttributes';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val},  
  success: function(data){
      $('#attributes').html(data);
      
  }
  });
    //getBrands(val);

 });

     /* $(document).on("change", "#SubCatId", function(event){

         var val = this.value;
          var CatId = $('#CatId').val();
       
     });
*/
      $(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Product Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });




  
   });
</script>
</body>
</html>
