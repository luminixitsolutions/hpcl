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
                
                <!-- header ends -->
            </div>
            <div class="col-11 col-sm-11 col-md-6 col-lg-5 col-xl-3 mx-auto align-self-center py-4" align="center">
                
                 <img src="logoo.jpg" alt="" style="width: 150px;"/>
                 
                <h1 class="mb-4"><span class="text-secondary fw-light">Sign in to</span><br/>your account</h1>
<form>
                <div class="form-group form-floating mb-3 is-valid">
                    <input type="number" name="Username" id="Username" class="form-control" placeholder="Mobile No">
                    <label class="form-control-label" for="email">Mobile No</label>
                </div>
<div class="alert alert-danger" role="alert" id="danger_message" style="display: none;"></div>
<div class="alert alert-success" role="alert" id="success_message" style="display: none;"></div>
              
            </div>
            <input type="hidden" name="PageValue" id="PageValue" placeholder="" class="form-control" value="<?php echo $_GET['value'];?>">     
            <div class="col-11 col-sm-11 mt-auto mx-auto py-4">
                <div class="row ">
                    <div class="col-12 d-grid">
                        <button type="button" class="btn btn-default btn-lg shadow-sm" id="login">GET OTP</button>
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

         $(document).ready(function(){
          $(document).on("click", "#login", function(event){
                 var action = "Login";
                var Username = $('#Username').val();
                 var Password = $('#Password').val();
                 var pageid = $('#pageid').val();
                 if(Username.trim() == ''){
                    $('#danger_message').css('display','block').html("Please Enter Mobile No");
                    $('#Username').focus();
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                }
               
                else{
                     $.ajax({  
                url :"ajax_files/ajax_customers.php",  
                method:"POST",  
                data:{action:action,Username:Username},
                 beforeSend:function(){
     $('#login').attr('disabled','disabled');
     $('#login').text('Please Wait...');
    },
                success:function(data){ 
                    //alert(data);exit();
                   // console.log(data);exit();
                     var res = JSON.parse(data);
                    var Status = res.status;
                    var roll = res.roll;
                    var Username = res.Username;
                    var prime = res.prime
                    var uid = res.uid;
                     if(Status == 0){
                         $('#danger_message').css('display','block').html("Invalid Login Details");
                      setTimeout(function(){  
                        $('#danger_message').fadeOut("Slow"); 
                    }, 2000); 
                     }
                     else{
                       
                           
                        $('#success_message').css('display','block').html("OTP Sent On Your Mobile No...!");
                      setTimeout(function(){  
                        $('#success_message').fadeOut("Slow");
                       
                    
                        window.location.href="login-otp-verify.php?phone="+Username;
                      
                    
                    }, 2000); 
                     }
                    $('#login').attr('disabled',false);
                    $('#login').text('Login');
                }
                });
                }

              }); 
         }); 
          </script>
</body>

</html>