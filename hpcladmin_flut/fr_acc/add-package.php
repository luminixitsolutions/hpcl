<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Masters";
$Page="Packages";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Package</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
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
$sql7 = "SELECT * FROM tbl_packages WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();

if($_GET['action'] == 'delete'){
  $id = $_GET['id'];
  $uid = $_GET['uid'];
  $sql = "DELETE FROM tbl_package_title WHERE id='$id'";
  $conn->query($sql);
  echo "<script>alert('record deleted!');window.location.href='add-package.php?id=$uid';</script>";
}

if(isset($_POST['submit'])){
    $Name = addslashes(trim($_POST["Name"]));
$Status = $_POST["Status"];
$Amount = $_POST["Amount"];
$Duration = $_POST["Duration"];
$Period = $_POST["Period"];
$Discount = $_POST['Discount'];
$Points = $_POST['Points'];
$CreatedDate = date('Y-m-d H:i:s');

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
  $Photo = $_POST['OldPhoto'];
}
$number = count($_POST["Title"]);
$number2 = count($_POST["CatId"]);
if($_GET['id'] == ''){
$qx = "INSERT INTO tbl_packages SET Name = '$Name',Amount='$Amount',Duration='$Duration',Period = '$Period',Detail1='$Detail1',
Title1='$Title1',Detail2='$Detail2',Title2='$Title2',Detail3='$Detail3',Title3='$Title3',Detail4='$Detail4',
Title4='$Title4',Detail5='$Detail5',Title5='$Title5',Status='$Status',Photo='$Photo',CreatedDate='$CreatedDate',
CreatedBy='$user_id',Title6='$Title6',Title7='$Title7',Title8='$Title8',Title9='$Title9',Title10='$Title10',Points='$Points',Discount='$Discount'";
	$conn->query($qx);
	$PostId = mysqli_insert_id($conn);
	 if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Title"][$i] != ''))  
                     {
                        $Title = addslashes($_POST['Title'][$i]);
                        $sql = "INSERT INTO tbl_package_title SET PostId='$PostId',Title='$Title'";
                        $conn->query($sql);

                     }
                }
             }

              if($number2 > 0)  
            {  
                for($i=0; $i<$number2; $i++)  
                {  
                     if(trim($_POST["CatId"][$i] != ''))  
                     {
                        $CatId = addslashes($_POST['CatId'][$i]);
                        $Percent = addslashes($_POST['Percent'][$i]);
                        $sql = "INSERT INTO tbl_subscription_per SET PostId='$PostId',CatId='$CatId',Percent='$Percent'";
                        $conn->query($sql);

                     }
                }
             }
}
else{
     $query2 = "UPDATE tbl_packages SET Name = '$Name',Amount='$Amount',Duration='$Duration',Period = '$Period',Detail1='$Detail1',
   Title1='$Title1',Detail2='$Detail2',Title2='$Title2',Detail3='$Detail3',Title3='$Title3',Detail4='$Detail4',Title4='$Title4',
   Detail5='$Detail5',Title5='$Title5',Status='$Status',Photo='$Photo',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',
   Title6='$Title6',Title7='$Title7',Title8='$Title8',Title9='$Title9',Title10='$Title10',Points='$Points',Discount='$Discount'
   WHERE id = '$id'";
 	$conn->query($query2);
 	$PostId = $id;
 	$sql3 = "DELETE FROM tbl_package_title WHERE PostId='$PostId'";
$conn->query($sql3);
 	 if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Title"][$i] != ''))  
                     {
                        $Title = addslashes($_POST['Title'][$i]);
                        $sql = "INSERT INTO tbl_package_title SET PostId='$PostId',Title='$Title'";
                        $conn->query($sql);

                     }
                }
             }

$sql3 = "DELETE FROM tbl_subscription_per WHERE PostId='$PostId'";
$conn->query($sql3);

if($number2 > 0)  
            {  
                for($i=0; $i<$number2; $i++)  
                {  
                     if(trim($_POST["CatId"][$i] != ''))  
                     {
                        $CatId = addslashes($_POST['CatId'][$i]);
                        $Percent = addslashes($_POST['Percent'][$i]);
                        $sql = "INSERT INTO tbl_subscription_per SET PostId='$PostId',CatId='$CatId',Percent='$Percent'";
                        $conn->query($sql);

                     }
                }
             }

}?>
<script>
alert('Record Saved Successfully!');
window.location.href='packages.php';
</script>
<?php 
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Package</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Package</li>
<li class="breadcrumb-item active"><?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Package</li>
</ol>
</div>
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
<div class="form-row">

<div class="form-group col-md-12">
<label class="form-label">Packages Name <span class="text-danger">*</span></label>
<input type="text" name="Name" value="<?php echo $row7['Name'];?>" class="form-control" id="Name" placeholder="Packages Name" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Amount<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="Amount" name="Amount" class="form-control" value="<?php echo $row7['Amount'];?>" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Discount %<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Discount" name="Discount" class="form-control" value="<?php echo $row7['Discount'];?>" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>

<!-- <div class="form-group col-md-4">
<label class="form-label">Points<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Points" name="Points" class="form-control" value="<?php echo $row7['Points'];?>" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div> -->

<div class="form-group col-md-4">
<label class="form-label">Duration<span class="text-danger">*</span></label>
<div class="input-group">
<select class="form-control" name="Duration" id="Duration" required>
<option selected="" value="" disabled>Select</option>
   <?php for($i=1;$i<=11;$i++) {?>
    <option <?php if($row7['Duration'] == $i){?> selected <?php } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
  <?php } ?>
</select>

<select class="form-control" name="Period" id="Period" required>
<option selected="" value="" disabled>Select</option>
 <option value="1" <?php if($row7['Period'] == 1){?> selected <?php } ?>>Month</option>
  <option value="2" <?php if($row7['Period'] == 2){?> selected <?php } ?>>Year</option>
</select>
<div class="clearfix"></div>
</div>
</div>

<div class="form-group col-md-12">
  <label class="form-label">Image </label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" value="<?php echo $row7['Photo'];?>" id="OldPhoto">

<span class="custom-file-label"></span>
</label><br>
 <?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>

</div>


<div class="form-group col-lg-12">
    <label class="form-label">Description </label>
</div>

<?php 
$sql_1 = "SELECT * FROM tbl_package_title WHERE PostId='$id'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<div class="form-row">  
<div class="form-group col-md-12">
<label class="form-label">Title </label>
<div class="input-group">
<input type="text" name="Title[]" class="form-control" placeholder="e.g. Membership valid for 3 Months" value="<?php echo $result['Title'];?>" autocomplete="off">
<span class="input-group-append">
    <a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['id']; ?>&action=delete&uid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
  </span>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>

<div id="dynamic_field">
<div class="form-row">  
<div class="form-group col-md-12">
<label class="form-label">Title </label>
<div class="input-group">
<input type="text" name="Title[]" class="form-control" placeholder="e.g. Membership valid for 3 Months" value="" autocomplete="off">
<span class="input-group-append">
    <button class="btn btn-secondary" type="button" id="add_more"><i class="fa fa-plus"></i></button>
  </span>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<div class="form-row">  
<div class="form-group col-md-12">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
</select>
<div class="clearfix"></div>
</div>


</div>
<!-- <button id="growl-default" class="btn btn-default">Default</button> -->
<button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
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
       $(document).ready(function(){
       var i=1;  
          $('#add_more').click(function(){
             
           i++;  
           var html = '';
           html+='<div class="form-row" id="row'+i+'">'; 
           html+='<div class="form-group col-md-12">';
html+='<label class="form-label">Title </label>'; 
html+='<div class="input-group">'; 
html+='<input type="text" name="Title[]" class="form-control" placeholder="e.g. Membership valid for 3 Months" value="" autocomplete="off">'; 
html+='<span class="input-group-append">'; 
    html+='<button class="btn btn-danger btn_remove" type="button" id="'+i+'"><i class="fa fa-times"></i></button>'; 
  html+='</span>'; 
html+='</div>'; 
html+='<div class="clearfix"></div>'; 
html+='</div>'; 
           $('#dynamic_field').append(html);
        });  

      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row'+button_id+'').remove();  
           }
      }); 
      
      $(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_packages.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
      category_lists();
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
