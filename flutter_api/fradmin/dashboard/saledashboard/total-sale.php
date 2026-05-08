<?php 
session_start();
include_once '../../config.php';
$frids = $_REQUEST['user_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- BOOTSTRAP 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DATATABLES 2 -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.bootstrap5.min.css">

<!-- FEATHER ICONS -->
<script src="https://unpkg.com/feather-icons"></script>

<style>
    body{
        font-family: 'Inter', sans-serif;
        background: #f5f6fa;
    }

    /* Top Heading */
    .page-header{
        padding: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Glass Form Card */
    .filter-box{
        background: rgba(255,255,255,0.85);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        backdrop-filter: blur(6px);
    }

    /* Table Box */
    .data-box{
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
    }

    .table thead{
        background: #eef1f5;
        font-weight: 600;
    }

    .btn-primary{
        border-radius: 8px;
        padding: 6px 15px;
    }

    .btn-clear{
        border-radius: 8px;
        padding: 6px 12px;
    }
</style>

</head>
<body>

<div class="container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <h3 class="fw-bold"><i data-feather="shopping-bag"></i> Total Orders</h3>
    </div>

    <!-- Filters -->
    <div class="filter-box">
        <form method="post">
            <div class="row g-3">

                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="FromDate" value="<?= $_REQUEST['FromDate'] ?>" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="ToDate" value="<?= $_REQUEST['ToDate'] ?>" class="form-control">
                </div>

                <input type="hidden" name="Search" value="Search">

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100"><i data-feather="search"></i> Search</button>
                </div>

                <?php if(isset($_REQUEST['Search'])) { ?>
                <div class="col-md-1 d-flex align-items-end">
                    <a href="<?= $_SERVER['PHP_SELF']; ?>" class="btn btn-danger btn-clear w-100">
                        <i data-feather="x-circle"></i>
                    </a>
                </div>
                <?php } ?>

            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="data-box">
        <table id="ordersTable" class="table table-striped table-bordered nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Outlet Name</th>
                    <th>Zone</th>
                    <th>Sub Zone</th>
                    <th>Total Sales (₹)</th>
                    <th>Discount (₹)</th>
                    <th>Invoices</th>
                    <th>QSR Sales</th>
                    <th>Pack Food Sales</th>
                    <th>Cross Sales</th>
                    <th>Cash (₹)</th>
                    <th>UPI/Online (₹)</th>
                    <th>Credit (₹)</th>
                    <th>Zomato/Swiggy (₹)</th>
                </tr>
            </thead>
            <tbody>
                 <?php
// Initialize filters
$extraFilter  = "";
$extraFilter2 = "";
$from = $_REQUEST['FromDate'];
$to = $_REQUEST['ToDate'];

// Apply User (Franchise) filter
if (!empty($_REQUEST['UserId']) && $_REQUEST['UserId'] != 'all') {
    $frId = $_REQUEST['UserId'];
    $extraFilter  .= " AND ci.FrId = '$frId' ";
    $extraFilter2 .= " AND tc.FrId = '$frId' ";
}

// Apply Zone filter
if (!empty($_REQUEST['ZoneId']) && $_REQUEST['ZoneId'] != 'all') {
    $zoneId = $_REQUEST['ZoneId'];
    $extraFilter  .= " AND tu.ZoneId = '$zoneId' ";
    $extraFilter2 .= " AND tu.ZoneId = '$zoneId' ";
}

// Apply Sub-Zone filter
if (!empty($_REQUEST['SubZoneId']) && $_REQUEST['SubZoneId'] != 'all') {
    $subZoneId = $_REQUEST['SubZoneId'];
    $extraFilter  .= " AND tu.SubZoneId = '$subZoneId' ";
    $extraFilter2 .= " AND tu.SubZoneId = '$subZoneId' ";
}

// Date filters
$dateFilter     = " AND ci.InvoiceDate BETWEEN '$from' AND '$to' ";
$prodDateFilter = " AND tc.CreatedDate BETWEEN '$from' AND '$to' ";

// ----------------------------------------------------------
// Main Query
// ----------------------------------------------------------
$sqlTable = "
SELECT 
    tu.ShopName,
    (SELECT Name FROM tbl_zone z WHERE z.id = tu.ZoneId) AS ZoneName,
    (SELECT Name FROM tbl_sub_zone sz WHERE sz.id = tu.SubZoneId) AS SubZoneName,
    tu.id AS FrId,

    -- Total Sales, Discount & Invoice Count
    COALESCE(SUM(ci.NetAmount), 0) AS Sales,
    COALESCE(SUM(ci.Discount), 0) AS Discount,
    COUNT(ci.Unqid) AS Invoices,

    -- QSR Sales
    (
        SELECT COALESCE(SUM(tc.Qty), 0)
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id
        WHERE tp.ProdType2 = 2 
          AND tp.ProdType = 0 
          AND tp.CrossSell != 1 
          AND tc.FrId = tu.id AND tc.FrId IN($frids)
          $prodDateFilter
          $extraFilter2
    ) AS QsrSales,

    -- Pack Food Sales
    (
        SELECT COALESCE(SUM(tc.Qty), 0)
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id
        WHERE tp.ProdType2 = 1 
          AND tp.ProdType = 0 
          AND tp.CrossSell != 1 
          AND tc.FrId = tu.id AND tc.FrId IN($frids)
          $prodDateFilter
          $extraFilter2
    ) AS PackSales,

    -- Cross Sales
    (
        SELECT COALESCE(SUM(tc.Qty), 0)
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id
        WHERE tp.CrossSell = 1 
          AND tc.FrId = tu.id AND tc.FrId IN($frids)
          $prodDateFilter
          $extraFilter2
    ) AS CrossSales,

    -- Cash Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id 
          AND ci.PayType = 'Cash' AND ci.FrId IN($frids)
          $dateFilter
          $extraFilter
    ) AS CashSales,

    -- UPI / Online Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id AND ci.FrId IN($frids)
          AND ci.PayType IN ('Online', 'UPI', 'Paytm', 'Phone Pay', 'Website online')
          $dateFilter
          $extraFilter
    ) AS UpiSales,

    -- Credit Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id AND ci.FrId IN($frids)
          AND ci.PayType = 'Borrowing'
          $dateFilter
          $extraFilter
    ) AS CreditSales,

    -- Zomato / Swiggy Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id AND ci.FrId IN($frids)
          AND ci.PayType IN ('Zomato', 'Swiggy')
          $dateFilter
          $extraFilter
    ) AS ZomatoSales

FROM (
    SELECT FrId, NetAmount, Discount, Unqid, InvoiceDate 
    FROM tbl_customer_invoice
    UNION ALL
    SELECT FrId, NetAmount, Discount, Unqid, InvoiceDate 
    FROM tbl_customer_invoice_2025
) ci
INNER JOIN tbl_users tu ON ci.FrId = tu.id
WHERE 1 = 1 AND ci.FrId IN($frids)
  $dateFilter
  $extraFilter
GROUP BY tu.id
ORDER BY Sales DESC
";


// Uncomment to debug
//echo "<pre>$sqlTable</pre>";

// Fetch data
$rows = getList($sqlTable);

// Output rows
foreach ($rows as $row) {
?>
                <tr>
                    <td><?= $row['ShopName']; ?></td>
                    <td><?= $row['ZoneName']; ?></td>
                    <td><?= $row['SubZoneName']; ?></td>
                    <td><?= number_format($row['Sales'],2); ?></td>
                    <td><?= number_format($row['Discount'],2); ?></td>
                    <td><?= $row['Invoices']; ?></td>
                    <td><?= $row['QsrSales']; ?></td>
                    <td><?= $row['PackSales']; ?></td>
                    <td><?= $row['CrossSales']; ?></td>
                    <td><?= number_format($row['CashSales'],2); ?></td>
                    <td><?= number_format($row['UpiSales'],2); ?></td>
                    <td><?= number_format($row['CreditSales'],2); ?></td>
                    <td><?= number_format($row['ZomatoSales'],2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>


<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>

<script>
    feather.replace();

    new DataTable('#ordersTable', {
        scrollX: true,
        order: [[3, 'desc']],
        layout: {
            topStart: {
                buttons: ['excel']
            }
        }
    });
</script>

</body>
</html>
