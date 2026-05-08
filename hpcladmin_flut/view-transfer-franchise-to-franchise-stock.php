<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Transfer-Raw-Stock-Godown-To-Franchise";
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



<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_transfer_godown_raw_prod_stock WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_transfer_godown_raw_stock_items WHERE TransferId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_godown_raw_prod_stock WHERE TransferId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-transfer-godwon-raw-stock.php";
    </script>
<?php } ?>

<div class="layout-content">
 
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Transfer Stock Franchise To Franchise
  <?php if(in_array("14", $Options)) {?> 
<span style="float: right;">
<a href="add-transfer-franchise-to-franchise-stock.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>From Franchise</th> 
               <th>To Franchise</th> 
                <th>Transfer Date</th> 
                <th>Total Product</th>
               
                <th>Narration</th>
             
                <th>Created Date</th>
              
                <!--<?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>-->
               <th>Action</th>
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS GodownName,tu2.ShopName FROM tbl_transfer_godown_raw_prod_stock tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.GodownId 
            LEFT JOIN tbl_users_bill tu2 ON tu2.id=tp.FranchiseId 
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT * FROM tbl_transfer_godown_raw_stock_items WHERE TransferId='".$row['id']."'";
               $rncnt2 = getRow($sql2);
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['GodownName']; ?></td>
               <td><?php echo $row['ShopName']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               <td><?php echo $rncnt2; ?></td> 
              
                <td><?php echo $row['Narration']; ?></td>
             
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
       
            <td>
              <?php if($user_id == 5 || $user_id == 162){?>
             <a onClick="return confirm('Are you sure you want delete this record?\nNote : Delete all record related this record (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a><?php } ?>
              <!-- <a href="use-raw-products.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a> -->
           <?php  if(in_array("11", $Options)){?>
              <!--&nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record?\nNote : Delete all record related this record (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a>-->
           <?php } ?>
            </td>
        
              
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
