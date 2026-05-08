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
<h4 class="font-weight-bold py-3 mb-0">Product Stock Report
</h4>

<div class="card" style="padding: 10px;">
       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    <div class="form-group col-md-4">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="FrId" id="FrId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["FrId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
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
<div class="form-group col-md-1">
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
   </div>
   <?php if(isset($_REQUEST['Search'])) {?>
<div class="card-datatable table-responsive">
<?php

$i = 1;
$FrId = $_POST['FrId'] ?? '';
$CatId = $_POST['CatId'] ?? '';
$FromDate = $_REQUEST['FromDate'] ?? '';
$ToDate = $_REQUEST['ToDate'] ?? '';

$conditions = ["ts.Status='Cr'", "ts.FrId!=0", "ts.ProdType=0"];

if ($FrId !== '' && $FrId !== 'all') {
    $conditions[] = "ts.FrId = '$FrId'";
}

if ($CatId !== '' && $CatId !== 'all') {
    $conditions[] = "p.CatId = '$CatId'";
}

if ($FromDate) {
    $conditions[] = "ts.StockDate >= '$FromDate'";
}

if ($ToDate) {
    $conditions[] = "ts.StockDate <= '$ToDate'";
}

$conditionStr = implode(' AND ', $conditions);

$sql = "SELECT ts.FrId, ts.ProdId, p.ProductName, tcc.Name AS CatName, p.MinQty, p.id AS Prod_Id
        FROM tbl_cust_prod_stock_2025 ts
        INNER JOIN tbl_cust_products_2025 p ON ts.ProdId = p.id
        INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id
        WHERE $conditionStr
        GROUP BY p.id
        ORDER BY ts.id DESC";

$res = $conn->query($sql);
?>

<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Franchise Name</th>
            <th>Product Name</th>
            <th>Category Name</th>
            <th>Min Qty</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $res->fetch_assoc()) {
            $shopSql = "SELECT ShopName FROM tbl_users_bill WHERE id = '" . $row['FrId'] . "'";
            $row3 = getRecord($shopSql);

            // Previous Balance before FromDate
            $sqlPrev = "SELECT SUM(creditqty) - SUM(debitqty) AS balqty FROM (
                        SELECT 
                            CASE WHEN Status = 'Cr' THEN SUM(Qty) ELSE 0 END AS creditqty,
                            CASE WHEN Status = 'Dr' THEN SUM(Qty) ELSE 0 END AS debitqty
                        FROM tbl_cust_prod_stock_2025
                        WHERE FrId = '{$row['FrId']}' AND ProdId = '{$row['Prod_Id']}' AND ProdType = 0 AND StockDate < '$FromDate'
                        GROUP BY Status
                    ) a";
            $prev = getRecord($sqlPrev);

            // Current Credit/Debit
            $dateFilter = "";
            if ($FromDate) {
                $dateFilter .= " AND StockDate >= '$FromDate'";
            }
            if ($ToDate) {
                $dateFilter .= " AND StockDate <= '$ToDate'";
            }

            $sqlCurrent = "SELECT SUM(creditqty) AS creditqty, SUM(debitqty) AS debitqty, SUM(creditqty) - SUM(debitqty) AS balqty FROM (
                            SELECT 
                                CASE WHEN Status = 'Cr' THEN SUM(Qty) ELSE 0 END AS creditqty,
                                CASE WHEN Status = 'Dr' THEN SUM(Qty) ELSE 0 END AS debitqty
                            FROM tbl_cust_prod_stock_2025
                            WHERE FrId = '{$row['FrId']}' AND ProdId = '{$row['Prod_Id']}' AND ProdType = 0 $dateFilter
                            GROUP BY Status
                        ) a";
            $curr = getRecord($sqlCurrent);

            $totalBal = ($curr['balqty'] ?? 0) + ($prev['balqty'] ?? 0);
            $bgcolor = $totalBal < $row['MinQty'] ? "background-color: #ff9f9f;" : "";
        ?>
            <tr style="<?php echo $bgcolor; ?>">
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row3['ShopName'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                <td><?php echo htmlspecialchars($row['CatName']); ?></td>
                <td><?php echo $row['MinQty']; ?></td>
                <td><?php echo $curr['creditqty'] ?? 0; ?></td>
                <td><?php echo $curr['debitqty'] ?? 0; ?></td>
                <td><?php echo $totalBal; ?></td>
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
