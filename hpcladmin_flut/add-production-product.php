<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Production";
$Page = "Add-Production-Products";

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
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_production_products WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
if($_GET['id'] == ''){
    $SgstPer = 2.5;
    $CgstPer = 2.5;
    $IgstPer = 0;
}
else{
    $SgstPer = $row7['SgstPer'];
    $CgstPer = $row7['CgstPer'];
    $IgstPer = $row7['IgstPer'];
}
?>

<?php 
  if(isset($_POST['submit'])){
    $BrandId = addslashes(trim($_POST["BrandId"]));
     $CatId = addslashes(trim($_POST["CatId"]));
    $ProductName = addslashes(trim($_POST["ProductName"]));
$Status = $_POST["Status"];
$Duration = $_POST['Duration'];
$Period = $_POST['Period'];
$Details = addslashes(trim($_POST["Details"]));
$ShortDetails = addslashes(trim($_POST['ShortDetails']));
$CourseType = addslashes(trim($_POST["CourseType"]));
$CourseCode = addslashes(trim($_POST["CourseCode"]));
$Lectures = addslashes(trim($_POST["Lectures"]));
$Language = addslashes(trim($_POST["Language"]));
$ModelNo = addslashes(trim($_POST["ModelNo"]));
$ProductCode = addslashes(trim($_POST["ProductCode"]));
$Unit = addslashes(trim($_POST["Unit"]));
$Roll = addslashes(trim($_POST["Roll"]));
$Qty = addslashes(trim($_POST["Qty"]));
$MakingQty = addslashes(trim($_POST["MakingQty"]));
$CustProdId = addslashes(trim($_POST["CustProdId"]));
$ProdType = addslashes(trim($_POST["ProdType"]));

$ProdPrice = $_POST['ProdPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $Price = addslashes(trim($_POST['Price']));
    $CgstAmt = addslashes(trim($_POST['CgstAmt']));
    $SgstAmt = addslashes(trim($_POST['SgstAmt']));
    $IgstAmt = addslashes(trim($_POST['IgstAmt']));
    
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
  $Photo = $_POST['OldPhoto'];
}

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
  $Photo2 = $_POST['OldPhoto2'];
}

function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;
$Code = RandomStringGenerator($n);

if($_GET['id']==''){
   
    $qx="INSERT INTO tbl_production_products SET Qty='$Qty',ProductName = '$ProductName',Status='$Status',CreatedDate='$CreatedDate',CreatedBy='$user_id',Unit='$Unit',ProdType='$ProdType',Price = '$Price',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ProdPrice='$ProdPrice'";
  $conn->query($qx);
 
      
  echo "<script>alert('Product Added Successfully!');window.location.href='view-production-products.php';</script>";
}
else{
 
   
    $query2 = "UPDATE tbl_production_products SET Qty='$Qty',ProductName = '$ProductName',Status='$Status',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',Unit='$Unit',ProdType='$ProdType',Price = '$Price',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',CgstAmt='$CgstAmt',SgstAmt='$SgstAmt',IgstAmt='$IgstAmt',ProdPrice='$ProdPrice' WHERE id = '$id'";
  $conn->query($query2);
  
  
      
  echo "<script>alert('Product Updated Successfully!');window.location.href='view-production-products.php';</script>";

}
    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Production Product</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

 
 <div class="form-group col-lg-8">
<label class="form-label"> Product Name <span class="text-danger">*</span></label>
<input type="text" name="ProductName" class="form-control" id="ProductName" placeholder="" value="<?php echo $row7["ProductName"]; ?>" required>
<div class="clearfix"></div>
</div>

<!--<div class="form-group col-lg-2">
<label class="form-label">HSN Code </label>
<input type="text" name="ModelNo" class="form-control" id="ModelNo" placeholder="" value="<?php echo $row7["ModelNo"]; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Product Code</label>
<input type="text" name="ProductCode" class="form-control" id="ProductCode" placeholder="" value="<?php echo $row7["ProductCode"]; ?>" >
<div class="clearfix"></div>
</div>-->
 
 <!--




-->


          <!-- <div class="form-group col-lg-3">
<label class="form-label">Product Price <span class="text-danger">*</span></label>
<input type="text" name="Price" class="form-control" id="Price" placeholder="" value="<?php echo $row7["Price"]; ?>" required>
<div class="clearfix"></div>
</div>-->

<input type="hidden" name="Qty" class="form-control" id="Qty" placeholder="" value="1">

           <!-- <div class="form-group col-lg-1">
<label class="form-label">Qty </label>
<input type="text" name="Qty" class="form-control" id="Qty" placeholder="" value="<?php echo $row7["Qty"]; ?>" required>
<div class="clearfix"></div>
</div>   -->

 <div class="form-group col-lg-4">
<label class="form-label">Unit <span class="text-danger">*</span></label>
<input type="text" name="Unit" list="browsers" class="form-control" id="Unit" placeholder="" value="<?php echo $row7["Unit"]; ?>" <?php if($rncnt33>0){?> readonly <?php } ?> required>
<datalist id="browsers">
    <?php
                $sql4 = "SELECT DISTINCT(Unit) AS Unit FROM tbl_production_products WHERE Unit!=''";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
  <option value="<?php echo $result['Unit'];?>">
      <?php } ?>
</datalist>

<div class="clearfix"></div>
</div>   


<div class="form-group col-lg-2">
<label class="form-label">Product Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="Price" class="form-control" value="<?php echo $row7['Price'];?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)" required>
<div class="clearfix"></div>
</div>
</div>


<div class="form-group col-lg-2">
<label class="form-label">CGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="CgstPer" name="CgstPer" class="form-control" value="<?php echo $CgstPer;?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>
<input type="hidden" id="CgstAmt" name="CgstAmt" value="<?php echo $row7['CgstAmt'];?>">
<input type="hidden" id="SgstAmt" name="SgstAmt" value="<?php echo $row7['SgstAmt'];?>">
<input type="hidden" id="IgstAmt" name="IgstAmt" value="<?php echo $row7['IgstAmt'];?>">
<div class="form-group col-lg-2">
<label class="form-label">SGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="SgstPer" name="SgstPer" class="form-control" value="<?php echo $SgstPer;?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">IGST%<span class="text-danger">*</span></label>
<div class="input-group">

<input type="text" id="IgstPer" name="IgstPer" class="form-control" value="<?php echo $IgstPer;?>" required="" onKeyPress="return isNumberKey(event)" oninput="getProdPrice(document.getElementById('MinPrice').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value)">

<div class="clearfix"></div>
</div>
</div> 

<div class="form-group col-lg-2">
<label class="form-label">Total GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="GstAmt" name="GstAmt" class="form-control" value="<?php echo $row7['GstAmt'];?>" required="" onKeyPress="return isNumberKey(event)"  readonly>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Price Wo GST<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="ProdPrice" name="ProdPrice" class="form-control" value="<?php echo $row7['ProdPrice'];?>" required="" readonly>
<div class="clearfix"></div>
</div>
</div>

</div> 



<div class="form-row">
<!-- <div class="form-group col-lg-12">
<label class="form-label">Details </label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>
-->


<!--<div class="form-group col-md-12">
  <label class="form-label">Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" value="<?php echo $row7['Photo'];?>" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>-->


 <div class="form-group col-md-6">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">

<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
</select>
<div class="clearfix"></div>
</div> 

 <div class="form-group col-md-6">
<label class="form-label">Product Type <span class="text-danger">*</span></label>
  <select class="form-control" id="ProdType" name="ProdType" required="">
<option value="0" <?php if($row7["ProdType"]=='0') {?> selected <?php } ?>>Billing/Customer Product</option>
<option value="1" <?php if($row7["ProdType"]=='1') {?> selected <?php } ?>>Raw Product</option>

</select>
<div class="clearfix"></div>
</div> 

</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
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
           $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });

        });


 var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "addMoreRaw";
    $.ajax({
    url:"ajax_files/ajax_raw_stock.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {
        console.log(data);
      $('#dynamic_field').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
          
           $('#row'+button_id+'').remove();  
           
      }); 
      
            
            });

        //CKEDITOR.replace( 'editor1');
</script>
</body>
</html>