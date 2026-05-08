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
                 <div style="float:right;">
                                                                   
                 <a href="add-wastage-stock.php" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>
               
               
               

                        <?php 
function getTotalQty($invoice_id, $conn) {
    $query = "SELECT COUNT(*) AS total_qty FROM tbl_cust_prod_stock_2025 WHERE WastageInvId = '$invoice_id'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);
    return $data['total_qty'] ?? 0;
}

$sql = "SELECT * FROM tbl_wastage_invoice WHERE Type=0 AND FrId='$FranchiseId' ORDER BY id DESC";
$row = getList($sql);
foreach($row as $result){
    $totalQty = getTotalQty($result['id'], $conn);
    
     // Manager status label and color
    $statusLabel = '';
    $statusStyle = '';
    if ($result['MgrStatus'] == 0) {
        $statusLabel = 'Pending By Manager';
        $statusStyle = 'color: orange; font-weight: bold;';
    } elseif ($result['MgrStatus'] == 1) {
        $statusLabel = 'Approved';
        $statusStyle = 'color: green; font-weight: bold;';
    } elseif ($result['MgrStatus'] == 2) {
        $statusLabel = 'Rejected';
        $statusStyle = 'color: red; font-weight: bold;';
    }
?>
<div class="card mb-3 shadow-sm border-left-primary">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 class="mb-1 text-primary">Invoice #<?php echo $result['id']; ?></h5>
                <p class="mb-1">
                    <i class="fas fa-calendar-alt"></i> 
                    <?php echo date("d/m/Y", strtotime($result['StockDate'])); ?>
                </p>
                <p class="mb-0">
                    <strong>Total Product:</strong> <?php echo $totalQty; ?>
                </p>
               <!--<p class="mt-1" style="<?php echo $statusStyle; ?>">
                    <?php echo $statusLabel; ?>
                </p>-->
            </div>
            <div class="col-4 text-right">
                <a href="view-wastage-stocks-products.php?id=<?php echo $result['id']; ?>" class="btn btn-sm btn-success rounded">
                     View
                </a>
            </div>
        </div>
    </div>
</div>
<?php } ?>

                        
                        <input type="text" class="Exp_Id" value="">
                    
                    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h5 class="modal-title">Are you sure?</h5>
      </div>
      <div class="modal-body">
          
        <button type="button" class="btn btn-success" onclick="delete()">YES</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
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
