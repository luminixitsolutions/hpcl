<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="page-loader">
<div class="bg-primary"></div>
</div>


<div class="authentication-wrapper authentication-2 ui-bg-cover ui-bg-overlay-container px-4" style="background-image: url('assets/img/bg/21.jpg');">
<div class="ui-bg-overlay bg-dark opacity-25"></div>
<div class="authentication-inner py-5">
<div class="card">
<div class="p-4 p-sm-5">

<div class="d-flex justify-content-center align-items-center pb-2 mb-4">
<div class="">
<div class="position-relative">
<img src="logo5.png" alt="Brand Logo" class="img-fluid" style="width: 120px;">
<div class="clearfix"></div>
</div>
</div>
</div>

<h5 class="text-center text-muted font-weight-normal mb-4">Login into Admin Panel</h5>

<form id="validation-form" method="post">
<div class="form-group">
<label class="form-label">Username</label>
<input type="text" name="Username" class="form-control" required="">
<div class="clearfix"></div>
</div>
<div class="form-group">
<label class="form-label d-flex justify-content-between align-items-end">
<span>Password</span>
</label>
<input type="password" name="Password" class="form-control" required="">
<div class="clearfix"></div>
</div>
<div class="d-flex justify-content-between align-items-center m-0">
<button type="submit" id="submit" class="btn btn-primary">Sign In</button>
<!-- <a href="sign-up.php" class="btn btn-success">Sign Up</a> -->
</div>
</form>

</div>

</div>
</div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  'Invalid Username / Password',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  'Login Successfull! Please Wait...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
	 $(document).ready(function(){
	 		$('#validation-form').on('submit', function(e){
	 		e.preventDefault();    
    if ($('#validation-form').valid()){ 
    	   $.ajax({  
                url :"ajax_files/ajax_login.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  //alert(data);
                  res = JSON.parse(data);
                  Status = res.Status;
                  Roll = res.Roll;
                  var Username = res.Username;
                    var uid = res.uid;
                     if(Status == 1){
                     	 success_toast();
                     setTimeout(function(){  
                    Android.loginUser(Username,uid);
                         Android.startDriverTracking(Username,uid);
                 window.location.href = 'dashboard.php'; 
                 
                    }, 2000);
                     
                     }
                     else{
                    error_toast();
                  
                     }
                      $('#submit').attr('disabled',false);
     				$('#submit').text('Sign In');
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
