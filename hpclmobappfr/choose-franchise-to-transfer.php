<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "My Expenses";
$Page = "Recharge";
$WallMsg = "NotShow"; 
$url = "home.php";
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

function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 


$sql = "SELECT * FROM tbl_cust_products WHERE code=''";
$row = getList($sql);
foreach($row as $result){
    $n = 10;
    $Code2 = RandomStringGenerator($n); 
    $Code = $Code2."".$result['id'];
    $sql2 = "UPDATE tbl_cust_products SET code='$Code' WHERE id='".$result['id']."'";
    $conn->query($sql2);
}

//echo "<pre>";
//print_r($_SESSION["cart_item"]);
//unset($_SESSION["cart_item"]);
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

     <link rel="stylesheet" href="admin_css/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="admin_css/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="admin_css/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="admin_css/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="admin_css/assets/libs/datatables/datatables.css">

   
 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
  
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <?php include 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       
<?php include 'top_header.php';?>

        <!-- page content start -->
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_cust_prod_stock WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');

if(isset($_POST['submit'])){
    $ToFrId = $_POST['ToFrId'];
    echo "<script>window.location.href='add-transfer-franchise-to-franchise-stock.php?ToFrId=$ToFrId';</script>";
}
?>


        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
              
                <div class="card mb-4" style="padding:10px;">
                    <form id="validation-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="FromFrId" id="FromFrId" value="<?php echo $FranchiseId;?>">

    <div class="form-row"> 
        <div class="form-group col-md-4 ">
<label class="form-label">To Franchise</label>
 <select class="selectpicker form-control" name="ToFrId" data-live-search="true">
<option selected="" value="">Select Franchise</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND id!='$FranchiseId' AND ShowFrStatus=1 ";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ToFrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
</div>
</div>



<button type="submit" name="submit" class="btn btn-primary btn-finish">Next</button>
</form>
                    
                </div>


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

    <script src="admin_css/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="admin_css/assets/libs/datatables/datatables.js"></script>

    <!-- Demo -->
    
    <script src="admin_css/assets/js/pages/tables_datatables.js"></script>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
 <script>
     $(function() {
  $('.selectpicker').selectpicker();
});

</script>
   
</body>

</html>
