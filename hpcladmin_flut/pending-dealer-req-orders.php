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





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pending Product Request
</h4>

<div class="card" style="padding: 10px;">
     <!--  <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    <div class="form-group col-md-3">
    <label class="form-label">Dealer <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="FrId" id="FrId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["FrId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
            <?php } ?>
        </select>
    </div>
    
     <div class="form-group col-md-3">
    <label class="form-label">Distributer <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="DistId" id="DistId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=166";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["DistId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname']; ?></option>
            <?php } ?>
        </select>
    </div>

     

<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off" required>
</div>

<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top: 20px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>-->
   <?php if(isset($_REQUEST['Search'])) {?>
<div class="card-datatable table-responsive">
<?php

$i = 1;
$FrId = $_POST['FrId'] ?? '';
$DistId = $_POST['DistId'] ?? '';
$CatId = $_POST['CatId'] ?? '';
$FromDate = $_REQUEST['FromDate'] ?? '';
$ToDate = $_REQUEST['ToDate'] ?? '';

$conditions = ["td.ReceiveStatus = 0"];

if ($FrId !== '' && $FrId !== 'all') {
    $conditions[] = "td.FrId = '$FrId'";
}
if ($DistId !== '' && $DistId !== 'all') {
    $conditions[] = "td.DistId = '$DistId'";
}

if ($FromDate) {
    $conditions[] = "td.CreatedDate >= '$FromDate'";
}

if ($ToDate) {
    $conditions[] = "td.CreatedDate <= '$ToDate'";
}

$conditionStr = implode(' AND ', $conditions);

$sql = "SELECT tu.ShopName,tu2.Fname,td2.OrderNo, td.*
        FROM tbl_dealer_req_order_items td 
        INNER JOIN tbl_dealer_req_orders td2 ON td.OrderId = td2.OrderId 
        INNER JOIN tbl_users tu ON td.FrId = tu.id 
        LEFT JOIN tbl_users tu2 ON td.DistId = tu2.id
        WHERE $conditionStr
        ORDER BY td.ItemId DESC";
//echo $sql;
$res = $conn->query($sql);
?>
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


<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Dealer Name</th>
            <th>Distributer Name</th>
            <th>Order No</th>
            <th>Request Date</th>
            <th>Qty</th>
            <th>Price</th>
            
            <th>Total Amount</th>
            <th>Receive Status</th>
            <!--<th>Receive Qty</th>
            <th>Receive Date</th>-->
           
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $res->fetch_assoc()) {
          
        ?>
            <tr style="<?php echo $bgcolor; ?>">
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row['ShopName'] ?? ''); ?></td>
                <td>
  <?php 
    $fname = trim($row['Fname'] ?? '');
    if ($fname === '') {
        echo '<span style="color:red;">Not Assign</span>';
    } else {
        echo htmlspecialchars($fname);
    }
  ?>
</td>

                <td><?php echo htmlspecialchars($row['OrderNo']); ?></td>
                <td><?php echo htmlspecialchars($row['CreatedDate']); ?></td>
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


               <!-- <td><?php echo htmlspecialchars($row['ReceiveQty']); ?></td>
                <td><?php echo htmlspecialchars($row['ReceiveDate']); ?></td>-->
              
            </tr>
        <?php } ?>
    </tbody>
</table>

</div>
<?php } ?>
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
