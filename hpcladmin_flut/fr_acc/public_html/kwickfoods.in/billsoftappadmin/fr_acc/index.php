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
    <title>Login</title>
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
                            <!--<div class="btn-section clearfix">
                                <a href="index.php" class="link-btn active btn-1 active-bg default-bg">OTP Login</a>
                                <a href="#" class="link-btn btn-2">PIN Login</a>
                            </div>-->
                            <div class="logo">
                                <a href="index.php">
                                    <img src="logo.jpg" alt="logo">
                                </a>
                            </div>
                            <h1>Welcome!</h1>
                            <div class="typing">
                                <h3>Sign Into Your Account</h3>
                            </div>
                            <div class="clearfix"></div>
                            <form id="validation-form" method="post">
                                <div class="form-group">
                                    <label for="first_field" class="form-label">Mobile Number</label>
                                    <input name="Username" type="number" class="form-control" id="Username" placeholder="Mobile Number" aria-label="Email Address" required>
                                </div>
                                <!--<div class="form-group">
                                    <label for="first_field" class="form-label">Printer Type</label>
                                    <select class="form-control" name="PrintType" id="PrintType">
                                        <option value="0" >Wired Printer</option>
                                        <option value="1" selected>Bluetooth Printer</option> 
                                    </select>
                                </div>-->
                                <div class="form-group clearfix">
                                    <label for="second_field" class="form-label">Password</label>
                                    <input name="Password" type="password" class="form-control" autocomplete="off" id="second_field" placeholder="Password" aria-label="Password" required>
                                </div> 
                                <!-- <div class="checkbox form-group clearfix">
                                    <div class="form-check float-start mb-0">
                                        <input class="form-check-input" type="checkbox" id="rememberme">
                                        <label class="form-check-label" for="rememberme">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="forgot-password-7.html" class="float-end forgot-password">Forgot your password?</a>
                                </div> -->
                                <input type="hidden" name="action" class="form-control" value="Login">
                                <div class="form-group clearfix">
                                    <button type="submit" id="submit" class="btn btn-primary btn-lg btn-theme">Login</button>
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

	 $(document).ready(function(){
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
                  res = JSON.parse(data);
                  Status = res.Status;
                  Roll = res.Roll;
                  var Username = res.Username;
                    var uid = res.uid;
                     if(Status == 1){
                     	 //success_toast();
                     	// alert('OTP Sent On Your Mobile No...!');
                     setTimeout(function(){  
                    // Android.loginUser(Username,uid);
                    //      Android.startDriverTracking(Username,uid);
                 window.location.href = 'dashboard.php'; 
                 
                    }, 1000);
                     
                     }
                     else{
                         alert('Invalid Phone No');
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