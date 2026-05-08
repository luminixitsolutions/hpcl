<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Receive-Franchise-Stock";
$Page = "Receive-Franchise-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">




<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Franchise Transfer Stock Details 
 
</h4>

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
               <th>Receive</th>
               <th>Balance</th>
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.ShopName AS GodownName,tu2.ShopName FROM tbl_transfer_franchise_prod_stock_2025 tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FromFrId 
            LEFT JOIN tbl_users_bill tu2 ON tu2.id=tp.ToFrId WHERE tp.ToFrId='$BillSoftFrId'
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT * FROM tbl_transfer_franchise_prod_stock_items_2025 WHERE TransferId='".$row['id']."'";
               $rncnt2 = getRow($sql2);
               
               $sql22 = "SELECT * FROM tbl_transfer_franchise_prod_stock_items_2025 WHERE TransferId='".$row['id']."' AND Receive=1";
               $rncnt22 = getRow($sql22);
               
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['GodownName']; ?></td>
               <td><?php echo $row['ShopName']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               <td><?php echo $rncnt2; ?></td> 
              
                <td><a href="javascript:void(0)" class="badge badge-pill badge-success"><?php echo $rncnt22;?></a></td>
              <td><a href="receive-franchise-stock.php?id=<?php echo $row['id']; ?>" class="badge badge-pill badge-primary"><?php echo $rncnt2-$rncnt22; ?></a></td> 
             
           
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
    });
});
</script>
</body>
</html>
