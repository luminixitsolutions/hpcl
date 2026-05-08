<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Franchise-Report-2025";
$Page = "Product-Stock-Report-2025";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
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

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
$id = $_GET['id'];
$sql = "SELECT * FROM tbl_dealer_req_orders WHERE OrderId='$id'";
$row = getRecord($sql);
?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product List Of Order No : <?php echo $row['OrderNo'];?>
</h4>
<style>
.badge {
  padding: 6px 12px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 500;
  color: #fff !important; /* White text */
}

/* Status colors */
.status-pending {
  background-color: #f0ad4e; /* soft amber */
}

.status-delivered {
  background-color: #28a745; /* green */
}

.status-partial {
  background-color: #17a2b8; /* teal/blue */
}

.status-unknown {
  background-color: #6c757d; /* grey */
}
</style>
<div class="card" style="padding: 10px;">
       
<div class="card-datatable table-responsive">
<?php

$i = 1;


$sql = "SELECT td.*,tc.ProductName,tu.Fname FROM tbl_dealer_req_order_items td INNER JOIN tbl_cust_products2 tc ON td.MainProdId=tc.id 
LEFT JOIN tbl_users tu ON td.DistId=tu.id WHERE td.OrderId='$id'";
//echo $sql;
$res = $conn->query($sql);
?>

<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Product Name</th>
             <th>Qty</th>
            <th>Price</th>
           
            <th>Total Amount</th>
            <th>Receive Status</th>
            <th>Receive Qty</th>
            <th>Receive Date</th>
            <th>Distributer</th>
           
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $res->fetch_assoc()) {
          
        ?>
            <tr style="<?php echo $bgcolor; ?>">
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row['ProductName'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['Qty']); ?></td>
                <td><?php echo htmlspecialchars($row['Price']); ?></td>
                <td><?php echo htmlspecialchars($row['Total']); ?></td>
                 <td>
<?php
$status = $row['ReceiveStatus']; // database field

if ($status == 0) {
    echo '<span class="badge status-pending">Pending</span>';
} elseif ($status == 1) {
    echo '<span class="badge status-delivered">Delivered</span>';
} elseif ($status == 2) {
    echo '<span class="badge status-partial">Partial</span>';
} else {
    echo '<span class="badge status-unknown">Unknown</span>';
}
?>
</td>
                <td><?php echo htmlspecialchars($row['ReceiveQty']); ?></td>
                <td><?php echo htmlspecialchars($row['ReceiveDate']); ?></td>
                <td><?php echo $row['Fname'];?></td>
              
            </tr>
        <?php } ?>
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
