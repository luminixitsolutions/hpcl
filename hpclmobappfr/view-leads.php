<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "My Leads";
$Page = "Recharge";
$WallMsg = "NotShow"; ?>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->
<style type="text/css">
    p {
    margin-top: 0;
    margin-bottom: 1px;
}
</style>
        <div class="main-container">
            <div class="container">
               
                        <?php 
                             $sql = "SELECT * FROM tbl_bp_leads WHERE UserId='$user_id'";
                            $row = getList($sql);
                            foreach($row as $result){
                            ?>

                            <div class="card mb-3">
                    <div class="card-body position-relative">
                        <div class="row mb-2">
                            <div class="col">
                                <p class="text-secondary small"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['CreatedDate'])))?></p>
                            </div>
                            <div class="col-auto">
                                <?php if($result['Status'] == 1){?>
                                <p class="text-success">Approved</p>
                                <?php } else {?>
                                        <p class="text-danger">Pending</p>
                                        <?php } ?>
                            </div>
                        </div>
                        <div class="media">                            
                            <div class="media-body">
                                <h6 class="mb-1 text-default"><?php echo $result['Name'];?></h6>
                                <p class=""><?php echo $result['Phone'];?></p>
                               <p class=""><?php echo $result['Address'];?></p>
                            </div>

                            <div class="col-auto">
                               <a href="add-lead.php?id=<?php echo $result['id'];?>" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-edit"></i></a>
                               &nbsp;
                                  <a href="#" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>
                            </div>
                                        
                        </div>
                    </div>
                </div>

                        
                         <?php } ?>
                        
                        
                    
            </div>
        </div>
    </main>

<?php include 'footer.php';?>
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

   
</body>

</html>
