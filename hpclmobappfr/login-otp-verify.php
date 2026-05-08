<?php session_start();
require_once 'config.php';
$PageName = "OTP Verify";
$UserId = $_SESSION['User']['id'];
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
    <link rel="manifest" href="manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="login_css/assets/img/favicon180.png" sizes="180x180">
    <link rel="icon" href="login_css/assets/img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="login_css/assets/img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&amp;display=swap" rel="stylesheet">

    <!-- bootstrap icons -->
    <link rel="stylesheet" href="login_css/font/bootstrap-icons.css">

    <!-- swiper carousel css -->
    <link rel="stylesheet" href="login_css/assets/vendor/swiperjs-6.6.2/swiper-bundle.min.css">

    <!-- style css for this template -->
    <link href="login_css/assets/css/style.css" rel="stylesheet" id="style">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>

<body class="body-scroll d-flex flex-column h-100 " data-page="landing">


    <!-- Begin page content -->
    <main class="container-fluid h-100 ">
        <div class="row h-100">
            <div class="col-11 col-sm-11 mx-auto">
                <!-- header -->
                <div class="row">
                    <header class="header">
                        <div class="row">
                            <div class="col">
                                <div class="">
                                    <img src="logo.png" alt="" style="width: 100px;"/>
                                    
                                </div>
                            </div>
                            <div class="col-auto align-self-center">
                               <!-- <a href="signup.php">Sing up</a>-->
                            </div>
                        </div>
                    </header>
                </div>
                <!-- header ends -->
            </div>
            <div class="col-11 col-sm-11 col-md-6 col-lg-5 col-xl-3 mx-auto align-self-center py-4">
                <h1 class="mb-3"><span class="text-secondary fw-light">Verify your</span><br/>OTP</h1>
                <p class="text-secondary mb-4">We have send you an mobile with OTP with instruction.</p>
 <form id="validation-form" method="post">
                <div class="form-group form-floating mb-3 is-valid">
                    <input type="number" class="form-control" value="<?php echo $_SESSION['otp'];?>" name="YourOtp" id="YourOtp" placeholder="Enter OTP">
                    <label class="form-control-label" for="OTP">Enter OTP</label>
                </div> 
                     <div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
                           <div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>

 <input type="hidden" name="PageValue" id="PageValue" placeholder="" class="form-control" value="<?php echo $_GET['value'];?>">   
                   <input type="hidden" name="action" class="form-control" value="OtpVerify">
<input class="form-control" type="hidden" autocomplete="off" name="GetOtp" placeholder="Phone No" id="GetOtp" value="<?php echo $_SESSION['otp'];?>">
<input class="form-control" type="hidden" autocomplete="off" name="Phone" placeholder="Phone No" id="Phone" value="<?php echo $_REQUEST['phone'];?>">     
            </div>
            <div class="col-11 col-sm-11 mt-auto mx-auto py-4">               
                <div class="row ">
                    <div class="col-auto text-center mx-auto">
                        <span class="progressstimer" id="timer2">
                            <img src="login_css/assets/img/progress.png" alt="">
                            <span class="timer" id="timer">1:00</span>
                        </span>
                        <br />
                        <p class="mb-3"><span class="text-muted">Didn't received yet?</span> <a href="javascript:void(0)" onclick="resendotp()" id="resendotp">Resend OTP</a>
                        </p>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" id="submit" class="btn btn-default btn-lg shadow-sm" id="login">Verify OTP</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </main>


    <!-- Required jquery and libraries -->
    <script src="login_css/assets/js/jquery-3.3.1.min.js"></script>
    <script src="login_css/assets/js/popper.min.js"></script>
    <script src="login_css/assets/vendor/bootstrap-5/js/bootstrap.bundle.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="login_css/assets/js/main.js"></script>
    <script src="login_css/assets/js/color-scheme.js"></script>

    <!-- PWA app service registration and works -->
    <script src="login_css/assets/js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="login_css/assets/js/app.js"></script>

    <script type="text/javascript">

function resendotp(){
    var Phone = $('#Phone').val();
    var action = "resendotp";
    $.ajax({
    url:"ajax_files/ajax_customers.php",
    method:"POST",
    data:{action:action,Phone:Phone},
    success:function(data)
    { 
        //alert(data);
        $('#GetOtp').val(data);
      $('#resendotp').hide();
      $('#timer2').show();
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
      $('#timer2').hide();

}

     $(document).ready(function(){
        timer(60);
            $('#validation-form').on('submit', function(e){
            e.preventDefault();    
            var PageValue = $('#PageValue').val();
           $.ajax({  
                url :"ajax_files/ajax_customers.php",  
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
                  Status = res.status;
                  Roll = res.Roll;
                  var Username = res.Username;
                    var uid = res.uid;
                     if(Status == 1){
                         //success_toast();
                        // alert('OTP Verified!');
                         $('#success_message').css('display','block').html("OTP VERIFIED");
                      setTimeout(function(){  
                        $('#success_message').fadeOut("Slow"); 
                    }, 2000); 
                     setTimeout(function(){  
                     Android.loginUser(Username,uid);
                     Android.startDriverTracking(Username,uid);
                    
                    window.location.href = 'home.php?frid='+uid; 
                 
                    }, 1000);
                     
                     }
                     else{
                        $('#danger_message').css('display','block').html("Invalid OTP");
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                  
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('VERIFIED');
                }  
           })  



    
  });

       });
</script>
</body>

</html>