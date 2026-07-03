<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-2025";
$Page = "Raw-Product-Stock-Report-2025";
$filterFrId = $_REQUEST['FrId'] ?? '';
$filterCatId = $_REQUEST['CatId'] ?? '';
$filterFromDate = $_REQUEST['FromDate'] ?? '';
$filterToDate = $_REQUEST['ToDate'] ?? '';
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
<h4 class="font-weight-bold py-3 mb-0">Raw Product Inventory Stock Report
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Raw Product Name</th>
                 <th>Credit</th>
              <th>Debit</th>
              <th>Balance</th>
              <th>Unit</th>
            </tr>
        </thead>
        <tbody>
             <?php 
            $i = 1;
            $stockDateFilter = '';
            if ($filterFromDate !== '') {
                $FromDate = $filterFromDate;
                $stockDateFilter .= " AND s.StockDate>='$FromDate'";
            }
            if ($filterToDate !== '') {
                $ToDate = $filterToDate;
                $stockDateFilter .= " AND s.StockDate<='$ToDate'";
            }

            $frFilter = '';
            if ($filterFrId !== '' && $filterFrId !== 'all') {
                $frFilter = " AND s.FrId='$filterFrId'";
            }

            $sql = "SELECT p.id AS ProdId,
                    MAX(p.ProductName) AS ProductName,
                    MAX(COALESCE(p.MinQty, 0)) AS MinQty,
                    MAX(p.Unit) AS Unit,
                    MAX(s.Unit2) AS Unit2,
                    COALESCE(SUM(CASE WHEN s.Status='Cr' THEN s.Qty ELSE 0 END), 0) AS creditqty,
                    COALESCE(SUM(CASE WHEN s.Status='Dr' THEN s.Qty ELSE 0 END), 0) AS debitqty,
                    COALESCE(SUM(CASE WHEN s.Status='Cr' THEN s.Qty ELSE 0 END) - SUM(CASE WHEN s.Status='Dr' THEN s.Qty ELSE 0 END), 0) AS balqty
                    FROM tbl_cust_products_2025 p
                    LEFT JOIN tbl_cust_prod_stock_2025 s ON s.ProdId = p.id AND s.ProdType = 1 AND s.FrId = '$BillSoftFrId' $stockDateFilter $frFilter
                    WHERE p.CreatedBy = '$BillSoftFrId' AND p.ProdType = 1 AND p.checkstatus = 1 AND p.delete_flag = 0";

            if ($filterCatId !== '' && $filterCatId !== 'all') {
                $CatId = $filterCatId;
                $sql .= " AND p.CatId='$CatId'";
            }

            $sql .= " GROUP BY p.id ORDER BY ProductName ASC";

            $res = $conn->query($sql);
            if ($res) {
            while($row = $res->fetch_assoc())
            {
                $unit = $row['Unit'] ?? '';
                $creditqty = (float)($row['creditqty'] ?? 0);
                $debitqty = (float)($row['debitqty'] ?? 0);
                $balqty = (float)($row['balqty'] ?? 0);

                if ($unit !== 'Pieces') {
                    $creditqty = $creditqty / 1000;
                    $debitqty = $debitqty / 1000;
                    $balqty = $balqty / 1000;
                }

                $MinQty = (float)($row['MinQty'] ?? 0);
                if ($balqty < $MinQty) {
                    $bgcolor = "background-color: #ff9f9f;";
                } else {
                    $bgcolor = "";
                }

                $displayUnit = ($row['Unit2'] ?? '') !== '' ? $row['Unit2'] : $unit;
             ?>
            <tr style="<?php echo $bgcolor; ?>">
               <td><?php echo $i; ?></td>
               <td><?php echo htmlspecialchars($row['ProductName'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $creditqty; ?></td>
                <td><?php echo $debitqty; ?></td>
                <td><?php echo $balqty; ?></td>
              <td><?php echo htmlspecialchars($displayUnit, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
           <?php $i++; }
            } ?>
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
    if ($.fn.DataTable.isDataTable('#example')) {
        $('#example').DataTable().destroy();
    }
    $('#example').DataTable({
        "scrollX": true,
        order: [[4, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
