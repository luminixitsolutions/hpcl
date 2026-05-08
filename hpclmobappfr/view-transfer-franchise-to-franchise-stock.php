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
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="admin_css/assets/libs/datatables/datatables.css">
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
  if (!empty($id) && is_numeric($id)) {
  $sql11 = "DELETE FROM tbl_transfer_franchise_prod_stock_2025 WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_transfer_franchise_prod_stock_items_2025 WHERE TransferId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_cust_prod_stock_2025 WHERE TransferId = '$id'";
  $conn->query($sql11);
  
  $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='transfer franchise to franchise stock deleted',invid='$id',createddate='$createddate',roll='transfer-franchise-to-franchise-stock'";
  $conn->query($sql);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-transfer-franchise-to-franchise-stock.php";
    </script>
<?php } } ?>

  
        <div class="main-container" style="padding-top: 80px;">
            <div class="container">
                <h5>Transfer Stock Franchise To Franchise</h5>
               <div style="float:right;">
                                                                   
                 <a href="choose-franchise-to-transfer.php" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>
               
<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>From Franchise</th> 
               <th>To Franchise</th> 
                <th>Transfer Date</th> 
                <th>Total Product</th>
               
                <th>Narration</th>
             
                <th>Created Date</th>
              
               
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS GodownName,tu2.ShopName FROM tbl_transfer_franchise_prod_stock_2025 tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FromFrId 
            LEFT JOIN tbl_users_bill tu2 ON tu2.id=tp.ToFrId WHERE tp.FromFrId='$FranchiseId'
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT * FROM tbl_transfer_franchise_prod_stock_items_2025 WHERE TransferId='".$row['id']."'";
               $rncnt2 = getRow($sql2);
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['GodownName']; ?></td>
               <td><?php echo $row['ShopName']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               <td><a href="view-transfer-product-list.php?id=<?php echo $row['id']; ?>">View (<?php echo $rncnt2; ?>)</a></td> 
              
                <td><?php echo $row['Narration']; ?></td>
             
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/libs/datatables/datatables.js"></script>
<script src="assets/js/pages/tables_datatables.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script>
    function getExpId(id){
        $('#myModal').modal('show');
        $('.Exp_Id').val(id);
    }
</script>
<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false,
        "scrollX": true
    });
});
</script>
   
</body>

</html>
