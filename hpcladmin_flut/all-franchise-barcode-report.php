<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
<h4 class="font-weight-bold py-3 mb-0">All Franchise Barcode Report</h4>
<br>

<div class="card">

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Franchise Name</th> 
               
                <th>Product Name</th>
                <th>Barcode No</th>
                <th>Min Qty</th>
            <th>Purchase Price</th>
            
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tc.ProductName,tc.BarcodeNo,tc.MinQty,tub.ShopName,tc.MinPrice,tc.PurchasePrice FROM tbl_cust_products tc 
                    
                    LEFT JOIN tbl_users_bill tub ON tub.id=tc.CreatedBy WHERE tc.ProdType=0 ";
            $sql.=" ORDER BY tc.ProductName ASC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $TotalAmt+=$row['Amount'];
             ?>
            <tr>
                <td><?php echo $i; ?> </td>
                <td><?php echo $row['ShopName']; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row['BarcodeNo']; ?></td>
                <td><?php echo $row['MinQty']; ?></td>
                <td>&#8377;<?php echo number_format($row['PurchasePrice'],2); ?></td>
               
               
              
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
      order: [[2, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });


});
</script>
</body>
</html>
