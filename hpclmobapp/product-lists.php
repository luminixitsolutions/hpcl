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

        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
<!--<div style="float:right;">
                                                                   
                 <a href="add-products.php" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>-->
<?php 
$id = $_REQUEST['catid'] ?? '';

$sql = "SELECT * FROM tbl_cust_products2 
        WHERE Status = 1 AND ProdType = 0";

if(!empty($id)){
    $sql .= " AND CatId = '$id'";
}

$sql .= " ORDER BY srno ASC";

$row = getList($sql);

foreach($row as $result){

    // Fetch Category Name
    $catSql = "SELECT Name FROM tbl_cust_category_2025 WHERE id='".$result['CatId']."'";
    $catData = getRecord($catSql);
    $catName = $catData['Name'] ?? 'No Category';

    // Fetch Stock Qty
    $sql2 = "SELECT SUM(CASE WHEN Status='Cr' THEN Qty ELSE 0 END) 
                    - SUM(CASE WHEN Status='Dr' THEN Qty ELSE 0 END) AS balqty 
            FROM tbl_cust_prod_stock_2025 
            WHERE MainProdId='".$result['id']."' AND ProdType=0";

    $row2 = getRecord($sql2);
    $stockqty = ($row2['balqty'] > 0) ? $row2['balqty'] : 0;
?>
    <div class="card mb-2">
        <div class="px-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item" style="border-radius: 10px;">
                    <div class="row align-items-center">
                        
                        <!-- Product Info -->
                        <div class="col align-self-center pr-0">
                            <h6 class="font-weight-normal mb-1" style="color:black;">
                                <?php echo $result['ProductName']; ?>
                            </h6>

                            <p class="small text-secondary mb-0">
                                Category : <?php echo $catName; ?>
                            </p>

                            <p class="small text-secondary mb-0">
                                Total Stock : <?php echo $stockqty; ?>
                            </p>

                            <p class="small text-secondary">
                                ₹ <?php echo number_format($result['MinPrice'],2); ?>
                            </p>
                        </div>

                    </div>
                </li>
            </ul>
        </div>
    </div>
<?php } ?>

                

                

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
</body>

</html>
