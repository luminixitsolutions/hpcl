<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="College-Information";
$Page = "Company";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - College Information</title>
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
$sql7 = "SELECT * FROM tbl_company_profile WHERE id='1'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Company Information</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Company Information</li>

</ol>
</div>
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" autocomplete="off">
  <input type="hidden" name="id" value="1" id="userid">
<div class="form-row">
 
<div class="form-group col-md-12">
<label class="form-label">College Name <span class="text-danger">*</span></label>
<input type="text" name="Sname" id="Sname" class="form-control" placeholder="Company Name" value="<?php echo $row7["Sname"]; ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-12">
<label class="form-label">Address <span class="text-danger">*</span></label>
<textarea name="Address" class="form-control" placeholder="Address" autocomplete="off" required><?php echo str_replace("<br />"," ",$row7["Address"]); ?></textarea>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
<label class="form-label">Google Map Code <span class="text-danger">*</span></label>
<textarea name="GoogleMap" class="form-control" placeholder="Google Map Code" autocomplete="off" rows="5"><?php echo $row7["GoogleMap"]; ?></textarea>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Email Id <span class="text-danger">*</span></label>
<input type="email" name="EmailId" id="EmailId" class="form-control" placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Another Email Id </label>
<input type="email" name="EmailId2" id="EmailId2" class="form-control" placeholder="Email Id" value="<?php echo $row7["EmailId2"]; ?>" autocomplete="off">
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">WhatsApp Mobile No <span class="text-danger">*</span></label>
<input type="text" name="Phone1" id="Phone1" class="form-control" placeholder="Mobile No" value="<?php echo $row7["Phone1"]; ?>" required>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Another Mobile No</label>
<input type="text" name="Phone2" class="form-control" placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-md-6">
<label class="form-label">Support Contact No <span class="text-danger">*</span></label>
<input type="text" name="SupportNo" id="SupportNo" class="form-control" placeholder="" value="<?php echo $row7["SupportNo"]; ?>" required>
<div class="clearfix"></div>
</div>
<div class="form-group col-md-6">
<label class="form-label">Support Email Id</label>
<input type="text" name="SupportEmailId" class="form-control" placeholder="" value="<?php echo $row7["SupportEmailId"]; ?>">
<div class="clearfix"></div>
</div> -->

<div class="form-group col-md-6">
<label class="form-label">Facebook Link <span class="text-danger">*</span></label>
<input type="text" name="Facebook" id="Facebook" class="form-control" placeholder="Facebook Link" value="<?php echo $row7["Facebook"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Twitter Link <span class="text-danger">*</span></label>
<input type="text" name="Twitter" id="Twitter" class="form-control" placeholder="Twitter Link" value="<?php echo $row7["Twitter"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Linkedin Link <span class="text-danger">*</span></label>
<input type="text" name="Linkedin" id="Linkedin" class="form-control" placeholder="Linkedin Link" value="<?php echo $row7["Linkedin"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Youtube Link <span class="text-danger">*</span></label>
<input type="text" name="Google" id="Google" class="form-control" placeholder="Youtube Link" value="<?php echo $row7["Google"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Instragram Link <span class="text-danger">*</span></label>
<input type="text" name="Instagram" id="Instagram" class="form-control" placeholder="Instagram Link" value="<?php echo $row7["Instagram"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Pinterest Link <span class="text-danger">*</span></label>
<input type="text" name="Pinterest" id="Pinterest" class="form-control" placeholder="Pinterest Link" value="<?php echo $row7["Pinterest"]; ?>">
<div class="clearfix"></div>
</div>


</div>
<!-- <button id="growl-default" class="btn btn-default">Default</button> -->
<button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
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

<script type="text/javascript">

  function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Email Id / Phone No Already Exists',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Saved Successfully...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
   $(document).ready(function(){
    //$(document).on("click", ".btn-finish", function(event){
      $('#validation-form').on('submit', function(e){
      e.preventDefault();    
    if ($('#validation-form').valid()){ 
      
         $.ajax({  
                url :"ajax_files/ajax_company.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                     if(data == 0){
                      error_toast();
                     
                     }
                     else{
                   success_toast();
                   /*setTimeout(function(){  
                   window.location.href = 'company-information.php';
                    }, 2000);*/
                     }
                      $('#submit').attr('disabled',false);
                     $('#submit').text('Save');
                }  
           })  



    }
else{
  //$('#Fname').focus();
    return false;
}
  });

});
</script>
</body>
</html>
