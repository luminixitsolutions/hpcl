<?php session_start();
require_once 'config.php';
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
$PageName = "My Expenses";
$Page = "Recharge";
$WallMsg = "NotShow"; 
$url = "home.php";?>
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
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_cash_uses WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="view-cash-uses.php";
    </script>
<?php } ?>

  
        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
               
               
               
               <?php 
               $id = $_GET['id'];
$query = "SELECT * FROM tbl_request_product_stock_2025 WHERE id = '$id'";
$row = getRecord($query);

                             $sql77 = "SELECT ttg.*,tgp.ProductName FROM tbl_request_product_stock_items_2025 ttg LEFT JOIN tbl_cust_products_2025 tgp ON ttg.ProdId=tgp.id WHERE ttg.TransferId='$id'";
                            $row77 = getList($sql77);
                            foreach($row77 as $result){
                                $TotQty+=$result['Qty'];
$TotRate+=$result['Price'];
$TotGst+=$result['GstAmt'];
$TotSGst+=$result['SgstAmt'];
$TotCGst+=$result['CgstAmt'];
                            ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="mb-1"><?php echo $result['ProductName'];?> </h5>
                                        <p class="text-secondary">
                                           Qty : <?php echo $result['Qty']." ".$result['Unit'];?>
                                       </p>
                                     
                                       
                                        
                                        

                                    </div>
                                    <div class="col-auto pl-0">
                                       
                                          <!--  <a href="add-cash-book.php?id=<?php echo $result['id']; ?>" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-edit"></i></a>-->
                                        <!--<a href="javascript:void(0)" onclick="getExpId(<?php echo $result['id'];?>)"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>-->
                                        <!--<a href="view-cash-uses.php?action=delete&id=<?php echo $result['id'];?>"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>-->
                     
                                      
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                         <?php } ?>

                      <div class="row">

                    <div class="col-12 col-md-6">
                        <div class="card mb-2">
                            <div class="card-body">
                <div class="row h6 font-weight-bold">
                    <div class="col">Total Qty</div>
                    <div class="col text-right text-mute"><?php echo $TotQty;?></div>
                </div>
                  
               

                            </div>
                        </div>
                    </div>

                    </div>
                        
                     
                 

            </div>
        </div>
    </main>

<?php include 'footer.php';?>
  <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>
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
    function getExpId(id){
        $('#myModal').modal('show');
        $('.Exp_Id').val(id);
    }
</script>
   
</body>

</html>
