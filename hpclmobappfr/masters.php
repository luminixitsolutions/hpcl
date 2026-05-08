<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";
$url = "home.php";
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
 <link href="css/toastr.min.css" rel="stylesheet">
   
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="analytics">
    <!-- screen loader -->
   

    <!-- menu main -->
    <?php include_once 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       <?php include 'top_header.php';?>

     

        <!-- page content start -->

        <div class="main-container" style="padding-top: 80px;background-color: white;">

                <div class="container mb-4">
                <div class="">
                    <div class=" text-center ">
                        <div class="row justify-content-equal no-gutters mt-4">
                            <a href="prod-category.php">
                                <div class="col-4 col-md-2 mb-3">
                                
                                    <div class="avatar avatar-60 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="icons/cat.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                
                                <a href="prod-category.php"><p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Category</small></p></a>
                            </div></a>

                             <a href="product-lists.php">
                                <div class="col-4 col-md-2 mb-3">
                                
                                    <div class="avatar avatar-60 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="icons/pro.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                
                                <a href="product-lists.php"><p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Products</small></p></a>
                            </div></a>
                            
                              <a href="view-expenses.php">
                                <div class="col-4 col-md-2 mb-3">
                                
                                    <div class="avatar avatar-60 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="icons/exp.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                
                                <a href="view-expenses.php"><p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Expenses</small></p></a>
                            </div></a>

                             <a href="view-customers.php">
                                <div class="col-4 col-md-2 mb-3">
                                
                                    <div class="avatar avatar-60 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="icons/cust.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                
                                <a href="view-customers.php"><p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Customers</small></p></a>
                            </div></a>

                            <a href="view-employee.php">
                                <div class="col-4 col-md-2 mb-3">
                                
                                    <div class="avatar avatar-60 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="icons/workforce.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                
                                <a href="view-employee.php"><p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Employees</small></p></a>
                            </div></a>

                        </div>
                        </div>
                        </div>
                        </div>

            
        </div>
    </main>


     <?php include_once 'footer.php';?>

    <!-- color settings style switcher -->
   <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>



    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- chart js-->
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/chartjs/utils.js"></script>
    <script src="vendor/chartjs/chart-js-data.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
 <script type="text/javascript" src="js/toastr.min.js"></script>
  
</body>

</html>
