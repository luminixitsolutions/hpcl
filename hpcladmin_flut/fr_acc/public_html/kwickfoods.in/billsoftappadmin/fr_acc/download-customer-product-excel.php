<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Download-Customer-Products-Excel";
$Page = "Download-Customer-Products-Excel";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Stock List</title>
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



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Customer Products
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Product Name</th>
                <th>Product Id</th>
                 <th>Qty</th>
              <th>Purchase Price</th>
              <th>Sell Price</th>
              <th>Date</th>
              
             
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            
            $sql = "SELECT p.ProductName,p.MinPrice,p.id FROM tbl_cust_products p 
                    WHERE p.CreatedBy='$BillSoftFrId' AND p.ProdType=0 AND p.delete_flag=0";
            $sql.=" GROUP BY p.id ORDER BY p.ProductName ASC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
              
                <td><?php echo $row['id']; ?></td>
                <td></td>
                <td></td>
                <td><?php echo $row['MinPrice']; ?></td>
                <td></td>
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
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
