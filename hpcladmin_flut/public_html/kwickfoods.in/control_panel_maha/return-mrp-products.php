<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Return-Products";
$Page = "Return-MRP-Product";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
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

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_fr_raw_stock WHERE id = '$id'";
  $conn->query($sql11);
  
  $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='raw product stock deleted',invid='$id',createddate='$createddate',roll='raw-product-stock'";
  $conn->query($sql);
  
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-fr-raw-stock.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Return MRP Product List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-return-mrp-product.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Invoice No</th> 
               <th>Return Date</th> 
                <th>Vendor Name</th> 
                <th>Return Qty</th> 
               <th>Reason</th>
                <th>Return Status</th>
                <th>Created Date</th>
              
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tp.*,tu.Fname AS VedName FROM tbl_return_prod_stock tp INNER JOIN tbl_users tu ON tp.VedId=tu.id WHERE tp.FrId='$BillSoftFrId' AND tp.ProdType=1
            ORDER BY tp.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT SUM(Qty) AS Qty FROM tbl_return_prod_items WHERE InvId='".$row['id']."'";
                $row2 = getRecord($sql2);
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['InvNo']; ?></td>
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ReturnDate']))); ?></td>
                <td><?php echo $row['VedName']; ?></td>
                <td><a href="view-return-product-lists.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $row2['Qty']; ?></a></td>
                   <td><?php echo $row['Narration']; ?></td>
                    
                 <td><?php if($row['ReturnStatus']=='1'){echo "<span style='color:green;'>Approve</span>";} else if($row['ReturnStatus']=='2'){echo "<span style='color:red;'>Reject</span>";} else { echo "<span style='color:orange;'>Pending</span>";} ?></td>
               
               <td><?php echo date("d/m/Y h:i a", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
           
           
        
              
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
