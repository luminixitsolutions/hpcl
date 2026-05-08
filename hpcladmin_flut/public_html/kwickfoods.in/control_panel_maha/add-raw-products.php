<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Raw-Products";
$Page = "Add-Raw-Products";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
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
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cust_products WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();

$sql33 = "SELECT * FROM tbl_fr_raw_stock WHERE ProdId='$id'";
$rncnt33 = getRow($sql33);

$query22 = "SELECT count(*) as totrec FROM tbl_raw_prod_make_qty WHERE RawProdId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;
?>

<?php 
  if(isset($_POST['submit'])){
    $SubCatId = addslashes(trim($_POST["SubCatId"]));
     $CatId = addslashes(trim($_POST["CatId"]));
    $ProductName = addslashes(trim($_POST["ProductName"]));
$Status = $_POST["Status"];
$Price = $_POST['Price'];
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

$CGST = addslashes(trim($_POST["CGST"]));
$SGST = addslashes(trim($_POST["SGST"]));
$IGST = addslashes(trim($_POST["IGST"]));
$Unit = addslashes(trim($_POST["Unit"]));
$Roll = addslashes(trim($_POST["Roll"]));
$Qty = addslashes(trim($_POST["Qty"]));
$MakingQty = addslashes(trim($_POST["MakingQty"]));
$CustProdId = addslashes(trim($_POST["CustProdId"]));
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
 
    $qx="INSERT INTO tbl_cust_products SET Qty='$Qty',ProductName = '$ProductName',Status='$Status',CreatedDate='$CreatedDate',CreatedBy='$BillSoftFrId',code='$Code',Unit='$Unit',ProdType='1',Transfer=1,SubCatId='$SubCatId',CatId='$CatId'";
  $conn->query($qx);
  $id = mysqli_insert_id($conn);
  
   $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new raw product added',invid='$id',createddate='$createddate',roll='raw-product'";
  $conn->query($sql);
  
   $PrdNo = $Code.$id;
   $sql = "UPDATE tbl_cust_products SET code='$PrdNo' WHERE id='$id'";
   $conn->query($sql);
  
    $number = count($_POST["CustProdId"]);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["MakingQty"][$i] != '' || $_POST["MakingQty"][$i] != 0))  
              {
                $CustProdId = $_POST['CustProdId'][$i];
                $MakingQty = $_POST['MakingQty'][$i];
                $sql22 = "INSERT INTO tbl_raw_prod_make_qty SET RawQty='$Qty',RawUnit='$Unit',RawProdId='$id',CustProdId='$CustProdId',MakingQty='$MakingQty'";
                $conn->query($sql22);
              }  

          }
      }
      
  echo "<script>alert('Raw Product Added Successfully!');window.location.href='view-raw-products.php';</script>";
}
else{
 

    $query2 = "UPDATE tbl_cust_products SET Qty='$Qty',ProductName = '$ProductName',Status='$Status',ModifiedDate='$CreatedDate',Unit='$Unit',ProdType='1',SubCatId='$SubCatId',CatId='$CatId' WHERE id = '$id'";
  $conn->query($query2);
  
  $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='raw product record updated',invid='$id',createddate='$createddate',roll='raw-product'";
  $conn->query($sql);
  
  $sql = "DELETE FROM tbl_raw_prod_make_qty WHERE RawProdId='$id'";
$conn->query($sql);
     $number = count($_POST["CustProdId"]);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["MakingQty"][$i] != '' || $_POST["MakingQty"][$i] != 0))  
              {
                $CustProdId = $_POST['CustProdId'][$i];
                $MakingQty = $_POST['MakingQty'][$i];
                $sql22 = "INSERT INTO tbl_raw_prod_make_qty SET RawQty='$Qty',RawUnit='$Unit',RawProdId='$id',CustProdId='$CustProdId',MakingQty='$MakingQty'";
                $conn->query($sql22);
              }  

          }
      }
      
  echo "<script>alert('Raw Product Updated Successfully!');window.location.href='view-raw-products.php';</script>";

}
    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Raw Product</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

 
 <div class="form-group col-lg-6">
<label class="form-label">Raw Product Name <span class="text-danger">*</span></label>
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
 
 <!--<div class="form-group col-lg-2">
<label class="form-label">Product Price </label>
<input type="text" name="Price" class="form-control" id="Price" placeholder="" value="<?php echo $row7["Price"]; ?>">
<div class="clearfix"></div>
</div>




<div class="form-group col-lg-2">
<label class="form-label">CGST % </label>
<input type="text" name="CGST" class="form-control" id="CGST" placeholder="" value="<?php echo $row7["CGST"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">SGST % </label>
<input type="text" name="SGST" class="form-control" id="SGST" placeholder="" value="<?php echo $row7["SGST"]; ?>" >
<div class="clearfix"></div>
</div>
 
 <div class="form-group col-lg-2">
<label class="form-label">IGST % </label>
<input type="text" name="IGST" class="form-control" id="IGST" placeholder="" value="<?php echo $row7["IGST"]; ?>">
<div class="clearfix"></div>
</div>-->


           
            <div class="form-group col-lg-1">
<label class="form-label">Qty </label>
<input type="text" name="Qty" class="form-control" id="Qty" placeholder="" value="<?php echo $row7["Qty"]; ?>">
<div class="clearfix"></div>
</div>   

 <div class="form-group col-lg-1">
<label class="form-label">Unit </label>
<input type="text" name="Unit" list="browsers" class="form-control" id="Unit" placeholder="" value="<?php echo $row7["Unit"]; ?>" <?php if($rncnt33>0){?> readonly <?php } ?>>
<datalist id="browsers">
    <?php
                $sql4 = "SELECT DISTINCT(Unit) AS Unit FROM tbl_raw_products WHERE Unit!=''";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
  <option value="<?php echo $result['Unit'];?>">
      <?php } ?>
</datalist>

<div class="clearfix"></div>
</div>   


<div class="form-group col-lg-2">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from tbl_raw_prod_category WHERE Status='1' AND CreatedBy IN ('$BillSoftFrId')";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
        
?>
                <option <?php if($row7['CatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Sub Category <span class="text-danger">*</span></label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" disabled="" value="">Select Sub Category</option>
<?php 
        $q = "select * from tbl_cust_sub_category WHERE Status='1' AND CatId='".$row7['CatId']."'";
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

<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_raw_prod_make_qty WHERE RawProdId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
      <div class="form-row" id="row<?php echo $i;?>">
 <div class="form-group col-md-3">
    <label class="form-label">Customer Product </label>
        <select class="form-control" style="width: 100%" data-allow-clear="true" name="CustProdId[]" id="CustProdId<?php echo $i; ?>">
        <option selected value="">...</option>
            <?php
                $sql4 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy IN ($BillSoftFrId) AND ProdType=0 ORDER BY ProductName";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
        <option <?php if ($result12["CustProdId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (&#8377;".$result['MinPrice'].")"; ?></option>
       <?php } ?>
    </select>
</div>

 <div class="form-group col-lg-3">
<label class="form-label">Making Qty </label>
<input type="text" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $i; ?>" placeholder="" value="<?php echo $result12["MakingQty"]; ?>">
<div class="clearfix"></div>
</div> 
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">

<div class="form-group col-md-1" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="fa fa-times"></i></button>
</div>
</div>
<?php $i++;} ?>
 <div id="dynamic_field">
    <div class="form-row">
 <div class="form-group col-md-3">
    <label class="form-label">Customer Product </label>
        <select class="form-control" style="width: 100%" data-allow-clear="true" name="CustProdId[]" id="CustProdId<?php echo $row_cnt; ?>">
        <option selected value="">...</option>
            <?php
                $sql4 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy IN ($BillSoftFrId) AND ProdType=0 ORDER BY ProductName";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
        <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (&#8377;".$result['MinPrice'].")"; ?></option>
       <?php } ?>
    </select>
</div>

 <div class="form-group col-lg-3">
<label class="form-label">Making Qty </label>
<input type="text" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $row_cnt; ?>" placeholder="" value="">
<div class="clearfix"></div>
</div> 
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $row_cnt; ?>" value="<?php echo $row_cnt; ?>">

<div class="form-group col-md-1" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
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


 <div class="form-group col-md-12">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">

<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
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

  $(document).ready(function() {
          $(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getRawSubCat";
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