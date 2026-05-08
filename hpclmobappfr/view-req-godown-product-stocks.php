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

  
        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
                 <div style="float:right;">
                                                                   
                 <a href="add-req-godown-product-stock.php" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>
               
               
               

                    <style>
    .status-card {
        font-family: 'Segoe UI', sans-serif;
        font-size: 14px;
        line-height: 1.6;
    }
    .status-card h5 {
        font-weight: 600;
        color: #000;
    }
    .status-card .info-label {
        color: #333;
        font-weight: 500;
        margin-right: 5px;
    }
    .status-card .info-value {
        font-weight: 600;
    }
    .status-approved {
        color: green;
    }
    .status-rejected {
        color: red;
    }
    .status-pending {
        color: orange;
    }
    .status-partial {
        color: #007bff;
    }
</style>

<?php 
$sql = "SELECT tc.*, tu.ShopName 
        FROM tbl_fr_req_stock_inv tc 
        LEFT JOIN tbl_users_bill tu ON tu.id = tc.FrId 
        WHERE tc.FrId = '$FranchiseId' AND ProdType = 2 ORDER BY tc.id DESC";
$row = getList($sql);

foreach($row as $result){
    $invId = $result['id'];

    // Fetch product statuses
    $sqlItems = "SELECT PurchaseStatus FROM tbl_fr_req_prod_stock WHERE InvId = '$invId'";
    $items = getList($sqlItems);

    $approveCount = $rejectCount = $pendingCount = 0;
    foreach ($items as $item) {
        if ($item['PurchaseStatus'] == '1') $approveCount++;
        elseif ($item['PurchaseStatus'] == '2') $rejectCount++;
        else $pendingCount++;
    }

    $totalProducts = count($items);

    // Status logic
    $statusLabel = 'Pending';
    $statusClass = 'status-pending';
    if ($approveCount == $totalProducts && $totalProducts > 0) {
        $statusLabel = 'Approved';
        $statusClass = 'status-approved';
    } elseif ($rejectCount == $totalProducts && $totalProducts > 0) {
        $statusLabel = 'Rejected';
        $statusClass = 'status-rejected';
    } elseif ($approveCount > 0 || $rejectCount > 0) {
        $statusLabel = 'Partially Approved';
        $statusClass = 'status-partial';
    }
?>
<div class="card mb-3 status-card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h5>#<?php echo $result['InvNo']; ?></h5>
                <div><span class="info-label">Franchise:</span><span class="info-value"><?php echo $result['ShopName']; ?></span></div>
                <div><span class="info-label">Date:</span><span class="info-value"><?php echo date("d/m/Y", strtotime($result['StockDate'])); ?></span></div>
                <div><span class="info-label">Total Products:</span><span class="info-value"><?php echo $totalProducts; ?></span></div>
                <div>
                    <span class="info-label">Approved:</span><span class="info-value status-approved"><?php echo $approveCount; ?></span> |
                    <span class="info-label">Rejected:</span><span class="info-value status-rejected"><?php echo $rejectCount; ?></span> |
                    <span class="info-label">Pending:</span><span class="info-value status-pending"><?php echo $pendingCount; ?></span>
                </div>
                <div>
                    <span class="info-label">Purchase Status:</span> 
                    <span class="info-value <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                </div>
                <div><span class="info-label">Approve Date:</span><span class="info-value"><?php echo $result['PurchaseApproveDate'] ? date("d/m/Y", strtotime($result['PurchaseApproveDate'])) : '-'; ?></span></div>
                <div><span class="info-label">Comments:</span><span class="info-value"><?php echo $result['PurchaseComments'] ?: '-'; ?></span></div>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="view-fr-req-godown-stock-product.php?id=<?php echo $result['id']; ?>" class="btn btn-success btn-sm rounded-pill px-3">
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
