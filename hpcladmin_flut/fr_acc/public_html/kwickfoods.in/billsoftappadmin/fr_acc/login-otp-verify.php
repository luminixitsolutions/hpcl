<?php 
session_start();
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '../../../../../../www.googletagmanager.com/gtm5445.html?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TAGCODE');</script>
    <!-- End Google Tag Manager -->
    <title>OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="logincss/assets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="logincss/assets/fonts/font-awesome/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="logincss/assets/fonts/flaticon/font/flaticon.css">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="logincss/assets/img/favicon.ico" type="image/x-icon" >

    <!-- Google fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800%7CPoppins:400,500,700,800,900%7CRoboto:100,300,400,400i,500,700">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="logincss/assets/css/style.css">
    <link rel="stylesheet" type="text/css" id="style_sheet" href="logincss/assets/css/skins/default.css">

</head>
<body id="top" class="login-7-bg">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TAGCODE"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="page_loader"></div>

<!-- Login 7 start -->
<div class="login-7">
    <div class="login-7-inner">
        <div id="particles-js"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-info">
                        <div class="form-section align-self-center">
                           <!-- <div class="btn-section clearfix">
                                <a href="login-7.html" class="link-btn active btn-1 active-bg default-bg">OTP Verification</a>
                               
                            </div>-->
                            <div class="logo">
                                <a href="index.php">
                                    <img src="logo.jpg" alt="logo">
                                </a>
                            </div>
                            <h1>Welcome!</h1>
                            <div class="typing">
                                <h3>OTP Verification</h3>
                            </div>
                            <div class="clearfix"></div>
                            <form id="validation-form" method="post">
                                <div class="form-group">
                                    <label for="first_field" class="form-label">OTP</label>
                                    <input name="YourOtp" id="YourOtp" type="number" class="form-control" placeholder="Enter Your OTP" aria-label="Email Address" value="<?php echo $_SESSION['otp'];?>">
                                     <a href="javascript:void(0)" id="resendotp" onclick="resendotp()" class="float-end forgot-password" >Resend OTP?</a>
                                      <a id="timer" class="float-end forgot-password"></a>
                                </div>
                                <!-- <div class="form-group clearfix">
                                    <label for="second_field" class="form-label">Password</label>
                                    <input name="password" type="password" class="form-control" autocomplete="off" id="second_field" placeholder="Password" aria-label="Password">
                                </div> -->
                                <!-- <div class="checkbox form-group clearfix">
                                    <div class="form-check float-start mb-0">
                                        <input class="form-check-input" type="checkbox" id="rememberme">
                                        <label class="form-check-label" for="rememberme">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="forgot-password-7.html" class="float-end forgot-password">Forgot your password?</a>
                                </div> -->
                                <input type="hidden" name="action" class="form-control" value="OtpVerify">
<input class="form-control" type="hidden" autocomplete="off" name="GetOtp" placeholder="Phone No" id="GetOtp" value="<?php echo $_SESSION['otp'];?>">
<input class="form-control" type="hidden" autocomplete="off" name="Phone" placeholder="Phone No" id="Phone" value="<?php echo $_REQUEST['phone'];?>">
<input type="hidden" id="PrintType" name="PrintType" value="<?php echo $_REQUEST['PrintType'];?>">
<input type="hidden" id="uid" name="uid" value="<?php echo $_REQUEST['uid'];?>">

                                <div class="form-group clearfix">
                                    <button type="submit" id="submit" class="btn btn-primary btn-lg btn-theme">OTP VERIFY</button>
                                </div>
                            </form>
                            <!-- <p>Help & Support</p>
                            <div class="social-list">
                                Call : 08149693719
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login 7 end -->

<!-- External JS libraries -->
<script src="logincss/assets/js/jquery.min.js"></script>
<script src="logincss/assets/js/popper.min.js"></script>
<script src="logincss/assets/js/bootstrap.bundle.min.js"></script>
<script src="logincss/assets/js/app.js"></script>
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
	 		var PrintType = $('#PrintType').val();
	 		
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
                  //console.log(data);exit();
                  res = JSON.parse(data);
                  Status = res.Status;
                  Roll = res.Roll;
                  var Username = res.Username;
                    var uid = res.uid;
                    
                    var title = res.title;
                    var address = res.address;
                    var mobile = res.mobile;
                    var gstin = res.gstin;
                    var receipt_title = res.receipt_title;
                    var terms_condition = res.terms_condition;
                    var bottom_title = res.bottom_title;
                     if(Status == 1){
                     	 //success_toast();
                     	// alert('OTP Verified!');
                     setTimeout(function(){  
                     Android.loginUser(Username,uid,PrintType,title,address,mobile,gstin,receipt_title,terms_condition,bottom_title);
                          Android.startDriverTracking(Username,uid);
                          window.location.href = 'file:///android_asset/offlineWeb/index.html';  
                          /*if(uid == 275){
                              window.location.href = 'view-customer-invoices2.php?userid='+uid;  
                          }
                          else{
                             window.location.href = 'orders.php?userid='+uid;   
                          }*/
               
                 
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