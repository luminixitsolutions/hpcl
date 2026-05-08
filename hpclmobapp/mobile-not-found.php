<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "QR Not Found";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$QrCode = $row11['Barcode'];

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='$UserId' GROUP BY Status) as a";
$row88 = getRecord($sql88);
$Wallet = $row88['Credit'] - $row88['Debit'];
?>
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
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main"  style="background-color: #f16822;">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
     <style>
         .h-50 {
    height: 70% !important;
}
     </style>
        
         <div class="container mt-3 mb-4 text-center" style="padding-top: 50px;">
             <div class="avatar avatar-140 rounded mb-3" style="border-radius: 50%!important;">
                <div class="background">
                    <img src="no_profile.jpg" alt="">
                </div>
            </div>
            <p class="text-default-secondary" style="color:#fff;">This user is not registered with Maha Buddy App... Please try again </p>
            
           
            <button onclick="scanQrCode()"class="btn btn-outline-default px-2 btn-block rounded" style="border: 1px solid #fff;color: #fff;"><span class="material-icons mr-1">qr_code_scanner</span> Scan QR Again</button>
        </div>


         <!--<div class="main-container ">
             
             
            <div class="container">
                <div class="row ">
                    <div class="col-12 col-md-6 col-lg-4 align-self-center text-center my-3 mx-auto">
                        <img src="../barcodes/<?php echo $QrCode;?>" style="width: 100%;">
                         <p class="text-secondary"><?php echo $Phone; ?> </p> 
                    </div>
                </div>
            </div>
        </div>-->
        
        
        </div>
    </main>

    <!-- footer-->
    


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


    <!-- page level custom script -->
    <script src="js/app.js"></script>
  
  <script>
      function scanQrCode(){
              Android.scanQrCode();
          }
          
          function getBarcodeValue(value){
              //$('#ImeiNo').val(value);
               window.location.href="pay-amount.php?mobno="+value;
          }
  </script>
</body>

</html>
