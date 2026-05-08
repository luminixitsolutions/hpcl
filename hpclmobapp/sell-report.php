<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";
$url = "sell-by-category.php";
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
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

<div class="card mb-4">

                    <form id="validation-form" method="post" enctype="multipart/form-data">
                        <div class="card-body">
<div class="form-row">


  <div class="form-group col-md-12">
<label class="form-label"> Franchise<span class="text-danger">*</span></label>
<select class="selectpicker form-control" style="width: 100%;padding-top: 12px;" name="FrId" id="FrId" required data-live-search="true">

<option selected="" value="all">All</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["FrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 col-6">
<label class="form-label">From Date <span class="text-danger">*</span></label>
<input type="date" name="FromDate" id="FromDate" class="form-control" placeholder="" value="<?php echo $_REQUEST["FromDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>
    
    <div class="form-group col-md-4 col-6">
<label class="form-label">To Date <span class="text-danger">*</span></label>
<input type="date" name="ToDate" id="ToDate" class="form-control" placeholder="" value="<?php echo $_REQUEST["ToDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

 </div>



<button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Search</button>
<span id="error_msg" style="color:red;"></span>
</div>
</form>
                
                <!-- <h3 style="color: red;padding-left: 10px;padding-right: 10px;font-size: 15px;">You Cant Add Stock. Product Not Allocated to You. Please Contact Head Office.</h3>-->

                </div>
                
                 <?php 
        $sql = "SELECT ShopName,id FROM tbl_users_bill WHERE ShopName!=''";
        if($_POST['FrId']){
                $FrId = $_POST['FrId'];
                if($FrId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND id='$FrId'";
                }
            }
$row = getList($sql);
foreach($row as $result){
    $FrId = $result['id'];
$FromDate = $_REQUEST['FromDate'] ?? '';
$ToDate = $_REQUEST['ToDate'] ?? '';

// Fetch Franchise Name


// Common date filter
$dateFilter = "";
if ($FromDate) {
    $dateFilter .= " AND tc.CreatedDate >= '$FromDate'";
}
if ($ToDate) {
    $dateFilter .= " AND tc.CreatedDate <= '$ToDate'";
}

// MRP Product
$sql2 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
         FROM tbl_customer_invoice_details_2025 tc 
         INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
         WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 1 AND tp.ProdType = 0 AND tc.MainProdId NOT IN (177,174) 
         $dateFilter";
$row2 = getRecord($sql2);

// Kitchen Product
$sql21 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 2 AND tp.ProdType = 0 AND tc.MainProdId NOT IN (177,174) 
          $dateFilter";
$row21 = getRecord($sql21);

// Cross Product
$sql22 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId = '$FrId' AND tc.MainProdId IN (177,174) 
          $dateFilter";
$row22 = getRecord($sql22);

$totalsell = $row2['TotSell']+$row21['TotSell']+$row22['TotSell'];
if($totalsell > 0){
?>
<h5 style="font-weight: bold;"><?= $result['ShopName']; ?></h5>
      <div class="card mb-2">
                    
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1" style="color:black;">QSR KITCHEN SALES</h6>
                                        <p class="small text-secondary">Total Sell : <?php echo $row21['TotSell'];?></p>
                                    </div>
                                    <div class="col-auto">
                                       ₹<?php echo number_format($row21['NetAmount'],2);?>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
              
                </div>
         
         
         <div class="card mb-2">
                    
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1" style="color:black;">PACK FOOD SALES</h6>
                                        <p class="small text-secondary">Total Sell : <?php echo $row2['TotSell'];?></p>
                                    </div>
                                    <div class="col-auto">
                                       ₹<?php echo number_format($row2['NetAmount'],2);?>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
              
                </div>
                
                 <div class="card mb-2">
                    
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1" style="color:black;">CROSS SALES</h6>
                                        <p class="small text-secondary">Total Sell : <?php echo $row22['TotSell'];?></p>
                                    </div>
                                    <div class="col-auto">
                                       ₹<?php echo number_format($row22['NetAmount'],2);?>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
              
                </div>
                
                <div class="card mb-2">
                    
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1" style="color:black;">TOTAL SALES</h6>
                                        <p class="small text-secondary">Total Sell : <?php echo $row2['TotSell']+$row21['TotSell']+$row22['TotSell'];?></p>
                                    </div>
                                    <div class="col-auto">
                                       ₹<?php echo number_format($row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount'],2);?>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
              
                </div>
         <?php } } ?>
                

                

            </div>
        </div>
    </main>


     <?php include_once 'footer.php';?>

    <!-- color settings style switcher -->
  <?php //include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>




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
  $(function() {
  $('.selectpicker').selectpicker();
});
    </script>
</body>

</html>
