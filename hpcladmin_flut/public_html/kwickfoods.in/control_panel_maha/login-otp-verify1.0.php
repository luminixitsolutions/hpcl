<?php 
session_start();
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Google Tag Manager -->
    <!-- End Google Tag Manager -->
    <title><?php echo $Proj_Title; ?> - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="loginassets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="loginassets/fonts/font-awesome/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="loginassets/fonts/flaticon/font/flaticon.css">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="loginassets/img/favicon.ico" type="image/x-icon" >

    <!-- Google fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800%7CPoppins:400,500,700,800,900%7CRoboto:100,300,400,400i,500,700">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="loginassets/css/style.css">
    <link rel="stylesheet" type="text/css" id="style_sheet" href="loginassets/css/skins/default.css">

</head>
<body id="top">
<!-- Google Tag Manager (noscript) -->
<!-- End Google Tag Manager (noscript) -->
<div class="page_loader"></div>

<!-- Login 43 start -->
<div class="login-43">
    <div class="container">
        <div class="row login-box">
            <div class="col-lg-5 col-md-12 bg-img none-992">
                <div class="info">
                    <div class="logo clearfix">
                        <a href="#">
                           
                        </a>
                    </div>
                    <!--<div class="btn-section clearfix">
                        <a href="login-43.html" class="link-btn active btn-1 default-bg">Login</a>
                        <a href="register-43.html" class="link-btn btn-1">Register</a>
                    </div>
                    <div class="social-list">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-google"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                    </div>-->
                </div>
            </div>
            <div class="col-lg-7 col-md-12 bg-color-8 align-self-center">
                <div class="form-section">
                    
                    <h3>OTP Verification</h3>
                    <div class="login-inner-form">
                        <form id="validation-form" method="post">
                            <div class="form-group form-box">
                                <input type="number" name="YourOtp" id="YourOtp" value="" class="form-control" required="">
                                <i class="flaticon-mail-2"></i>
                                <a  href="javascript:void(0)" id="resendotp" onclick="resendotp()" style="float:right;"> Resend OTP</a>
                                <a class="forgot" id="timer" style="float: right !important;font-size: 15px;"></a>
                            </div>

                            <!--<div class="form-group form-box">
                                <input type="password" name="Password" class="form-control" autocomplete="off" placeholder="Password" aria-label="Password">
                                <i class="flaticon-password"></i>
                            </div>-->
                            <!--<div class="checkbox form-group form-box">
                                <div class="form-check checkbox-theme">
                                    <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <a href="forgot-password-43.html">Forgot Password</a>
                            </div>-->
                            <input type="hidden" name="action" class="form-control" value="OtpVerify">
<input class="form-control" type="hidden" autocomplete="off" name="GetOtp" placeholder="Phone No" id="GetOtp" value="<?php echo $_SESSION['otp'];?>">
<input class="form-control" type="hidden" autocomplete="off" name="Phone" placeholder="Phone No" id="Phone" value="<?php echo $_REQUEST['phone'];?>">
                            <div class="form-group mb-0">
                                <button type="submit" id="submit" class="btn-md btn-theme w-100">OTP VERIFY</button>
                            </div>
                            
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login 43 end -->

<!-- External JS libraries -->
<script src="loginassets/js/jquery.min.js"></script>
<script src="loginassets/js/popper.min.js"></script>
<script src="loginassets/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript">

function resendotp(){
    var Phone = $('#Phone').val();
    var action = "resendotp";
    $.ajax({
    url:"ajax_files/ajax_login.php",
    method:"POST",
    data:{action:action,Phone:Phone},
    success:function(data)
    { 
        $('#GetOtp').val(data);
      $('#resendotp').hide();
      $('#timer').show();
      timer(60);

    }
    });
  }
  let timerOn = true;
function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;
  
  m = m < 10 ? '0' + m : m;
  s = s < 10 ? '0' + s : s;
  document.getElementById('timer').innerHTML = m + ':' + s;
  remaining -= 1;
  
  if(remaining >= 0 && timerOn) {
    setTimeout(function() {
        timer(remaining);
    }, 1000);
    return;
  }

  if(!timerOn) {
    // Do validate stuff here
    return;
  }
  
  // Do timeout stuff here
  //alert('Timeout for otp');
  $('#resendotp').show();
  $('#resendotp').text('Resend OTP');
      $('#timer').hide();

}

	 $(document).ready(function(){
	     $('#resendotp').hide();
      $('#timer').show();
      timer(60);

	 		$('#validation-form').on('submit', function(e){
	 		e.preventDefault();    
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
                     	 //success_toast();
                     	// alert('OTP Verified!');
                     setTimeout(function(){  
                     Android.loginUser(Username,uid);
                          Android.startDriverTracking(Username,uid);
                window.location.href = 'orders.php'; 
                 
                    }, 1000);
                     
                     }
                     else{
                         alert('Invalid OTP');
                    //error_toast();
                  
                     }
                      $('#submit').attr('disabled',false);
     				$('#submit').text('Sign In');
                }  
           })  



    
  });

	   });
</script>
</body>
</html>