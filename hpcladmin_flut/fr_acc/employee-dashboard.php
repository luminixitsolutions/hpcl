<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage = "Dashboard";
$Page = "Dashboard";
$userid = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users_bill WHERE id='$userid'";
	$row77 = getRecord($sql77);
$CocoFranchiseAccess = $row77['CocoFranchiseAccess'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
      <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/morris/morris.css">
</head>

<body>
    <style type="text/css">
        .mr_5 {
            margin-right: 3rem !important;
        }
    </style>

    <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

           
 
<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-dark container-p-x" id="layout-navbar">

<a href="index.php" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
<span class="app-brand-logo demo">
<img src="assets/img/logo-dark.png" alt="" class="img-fluid">
</span>
<!--<span class="app-brand-text demo font-weight-normal ml-2">The Young Brains</span>-->
</a>

<div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
    
    
<a class="nav-item nav-link px-0 mr-lg-4" href="javascript:">
<i class="ion ion-md-menu text-large align-middle"></i>
</a>
</div>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
<span class="navbar-toggler-icon"></span>
</button>
<div class="navbar-collapse collapse" id="layout-navbar-collapse">
<img src="logon1.jpg" height="40px">
<hr class="d-lg-none w-100 my-2">
<div class="navbar-nav align-items-lg-center ml-auto">


<div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>
<div class="demo-navbar-user nav-item dropdown">
<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
<span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
	<?php if($row77['Photo']=='') {?>
<img src="user_icon.jpg" alt class="d-block ui-w-30 rounded-circle">
<?php } else{?>
	<img src="../uploads/<?php echo $row77['Photo']; ?>" alt class="d-block ui-w-30 rounded-circle" style="width: 30px;height: 30px;">
<?php } ?>
<span class="px-1 mr-lg-2 ml-2 ml-lg-0"><?php echo $row77['Fname'];?></span>
</span>
</a>
<div class="dropdown-menu dropdown-menu-right">
 <!--<a href="company-information.php" class="dropdown-item">
<i class="feather icon-user text-muted"></i> &nbsp; My profile</a>-->
<a href="change-password.php" class="dropdown-item">
<i class="feather icon-unlock text-muted"></i> &nbsp; Change Password</a>
<!-- <a href="javascript:" class="dropdown-item">
<i class="feather icon-settings text-muted"></i> &nbsp; Account settings</a> -->
<div class="dropdown-divider"></div>
<a href="logout.php" class="dropdown-item">
<i class="feather icon-power text-danger"></i> &nbsp; Log Out</a>
</div>
</div>
</div>
</div>
</nav>


            <div class="layout-container">




                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

                        

                       <div class="row">
    <?php 
    if (empty($CocoFranchiseAccess) || $CocoFranchiseAccess == "0") {
        echo '
        <div class="col-12">
            <div class="alert alert-warning text-center" style="font-size: 16px; padding: 20px;">
                <strong>No Franchise Found</strong>
            </div>
        </div>';
    } else {
        $sql = "SELECT ShopName, id FROM tbl_users_bill WHERE id IN ($CocoFranchiseAccess)";
        $row = getList($sql);

        if (!empty($row)) {
            foreach ($row as $result) {
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <a href="dashboard.php?id=<?php echo $result['id']; ?>" target="_blank" style="text-decoration: none;">
                        <div class="card shadow border-0 h-100 d-flex justify-content-center align-items-center text-center" style="min-height: 120px;">
                            <div class="card-body d-flex align-items-center justify-content-center" style="padding: 20px;">
                                <h6 class="mb-0" style="color: #333; font-size: 15px; font-weight: 600;">
                                    <?php echo strtoupper($result['ShopName']); ?>
                                </h6>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '
            <div class="col-12">
                <div class="alert alert-info text-center" style="font-size: 16px; padding: 20px;">
                    <strong>No Franchise Found</strong>
                </div>
            </div>';
        }
    }
    ?>
</div>


                        

                    


                    </div>




                </div>



                <?php include_once 'footer.php'; ?>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


  <!-- Core scripts -->
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/eve/eve.js"></script>
    <script src="assets/libs/raphael/raphael.js"></script>
    <script src="assets/libs/morris/morris.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
  
</body>

</html>