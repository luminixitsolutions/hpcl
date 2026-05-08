<?php session_start();require_once 'config.php';require_once 'auth.php';$PageName = "Atootdor";$page="premium";$PageName="Refer & Earn";?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
       <?php include_once 'back-header.php'; ?>
    
    
     <div class="container  text-center text-white">
            <div class="row">
                <div class="col col-sm-8 col-md-6 col-lg-5 mx-auto">
                    <img src="img/refer.png" alt="" class="mw-100 ">
                    
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="container mb-4">
                <div class="card border-0 mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto pr-0">
                                <div class="avatar avatar-50 border-0 bg-danger-light rounded-circle text-danger">
                                    <i class="material-icons vm text-template">card_giftcard</i>
                                </div>
                            </div>
                            <div class="col-auto align-self-center">
                                <h6 class="mb-1">Refer and Earn Rewards</h6>
                                <p class="small text-secondary">Share your referal link and start earning</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mb-4">
                <div class="alert alert-success d-none" id="successmessage">Refferal link copied</div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="refferal Link" value="<?php echo $row110['CustomerId'];?>" id="referallink" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-default rounded" type="button" id="coplink" onclick="shareApplication('Hey There! Use Referral Code <?php echo $row110['CustomerId'];?> on your first purchase. Redeem Now! Download and Apply Now:');">Share</button>
                    </div>
                </div>
            </div>
       </div>
    <br><br><br><br>
    <!-- footer-->
     <?php include_once 'footer.php';?>


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- PWA app service registration and works -->
    <script src="js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
     <script>
        function shareApplication(msg){
            //alert(msg);
             Android.shareApplication(''+msg+'','MahaBuddy');
             //Android.shareApplication('test','Daily Door Services');
        }
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>
 
</body>

</html>
