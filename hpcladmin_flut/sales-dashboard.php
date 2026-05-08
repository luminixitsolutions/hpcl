<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'] ?? 0;
if (!$user_id) die('Invalid session');

// Get user's assigned zones & subzones
$row = getRecord("SELECT zone, subzone FROM tbl_users_bill WHERE id = '$user_id'");
$zoneids = trim($row['zone'] ?? '');
$subzoneids = trim($row['subzone'] ?? '');

// Sanitize
$zoneids = implode(',', array_filter(array_map('intval', explode(',', $zoneids))));
$subzoneids = implode(',', array_filter(array_map('intval', explode(',', $subzoneids))));

// ✅ Build WHERE conditions dynamically
$where = ["Roll=5"];
if (!empty($zoneids)) $where[] = "ZoneId IN($zoneids)";
if (!empty($subzoneids)) $where[] = "SubZoneId IN($subzoneids)";
$whereSql = implode(' AND ', $where);

// ✅ Final SQL
$sql = "SELECT GROUP_CONCAT(id) AS FrId FROM tbl_users WHERE $whereSql";

// ✅ Fetch data
$row77 = getRecord($sql);
$frids = $row77['FrId'] ?? '';

//echo $frids ?: "No franchise IDs found.";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kwick Bill – Sales Dashboard</title>

 <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    :root{
      --kb-primary:#0ea5e9; /* cyan/blue */
      --kb-accent:#22c55e;  /* green */
      --kb-danger:#ef4444;  /* red */
      --kb-muted:#6b7280;   /* gray */
    }
    body{background:#f7f9fc;}
    .brand-gradient{background:linear-gradient(135deg,#0ea5e9,#4f46e5); color:#fff;}
    .card{border:0; border-radius:1rem; box-shadow:0 6px 18px rgba(27,31,35,.06);}
    .kpi .icon-wrap{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:#eef7ff;}
    .kpi .number{font-weight:800;font-size:1.4rem;}
    .section-title{font-weight:700; color:#111827;}
    .subtle{color:#6b7280; font-size:.9rem;}
    .table>thead th{font-weight:700;}
    .progress.mini{height:.5rem; border-radius:1rem;}
    .sticky-toolbar{position:sticky; top:0; z-index:1020; backdrop-filter:saturate(180%) blur(6px); background:rgba(255,255,255,.75); border-bottom:1px solid #eef2f7;}
    .chip{padding:.25rem .6rem;border-radius:999px;background:#eef2ff;color:#374151;font-weight:600;font-size:.8rem;}
    .rating i{color:#fbbf24;}
    /* mobile spacing tweaks */
    @media (max-width: 575.98px){ .kpi .number{font-size:1.2rem;} }
  </style>
</head>
<body>


  <!-- Top Bar -->
<nav class="sticky-toolbar navbar py-3 px-3">
  <div class="container-fluid">
      
       <div class="d-flex align-items-center gap-2">
  <div class="brand-gradient px-3 py-2 rounded-3 fw-bold bg-primary text-white d-flex align-items-center gap-2">
    <a href="dashboard.php" class="text-white text-decoration-none">
      <i class="fa-solid fa-house me-1"></i>
    
    <span>Dashboard</span></a>
  </div>
  <span class="subtle text-muted">Sales • Analytics</span>
</div>

   

   <form method="POST" class="d-flex align-items-center gap-2" id="dateFilterForm">
  <div class="input-group">
    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
    <input type="date" class="form-control" name="FromDate" id="fromDate"
           value="<?= $_REQUEST['FromDate'] ?? date('Y-m-d') ?>">
    <span class="input-group-text">to</span>
    <input type="date" class="form-control" name="ToDate" id="toDate"
           value="<?= $_REQUEST['ToDate'] ?? date('Y-m-d') ?>">
    <button type="submit" name="calendar" value="custom" class="btn btn-primary">
      <i class="fa-solid fa-filter"></i> Apply
    </button>
  </div>

  <div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
      Quick Range
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><button class="dropdown-item quick-range" data-range="today" type="button">Today</button></li>
      <li><button class="dropdown-item quick-range" data-range="yesterday" type="button">Yesterday</button></li>
      <li><button class="dropdown-item quick-range" data-range="week" type="button">Last 7 Days</button></li>
      <li><button class="dropdown-item quick-range" data-range="month" type="button">This Month</button></li>
    </ul>
  </div>
</form>

<script>
document.querySelectorAll('.quick-range').forEach(btn => {
  btn.addEventListener('click', function () {
    const range = this.getAttribute('data-range');
    const today = new Date();
    let from, to;

    if (range === 'today') {
      from = to = today;
    } else if (range === 'yesterday') {
      const y = new Date(today);
      y.setDate(today.getDate() - 1);
      from = to = y;
    } else if (range === 'week') {
      const last7 = new Date(today);
      last7.setDate(today.getDate() - 6);
      from = last7;
      to = today;
    } else if (range === 'month') {
      from = new Date(today.getFullYear(), today.getMonth(), 1);
      to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    }

    // Format date YYYY-MM-DD
    const fmt = d => d.toISOString().split('T')[0];
    document.getElementById('fromDate').value = fmt(from);
    document.getElementById('toDate').value = fmt(to);

    // Submit the form normally (page reload)
    const form = document.getElementById('dateFilterForm');

    // Create or update a hidden input for calendar filter
    let hidden = form.querySelector('input[name="calendar"]');
    if (!hidden) {
      hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'calendar';
      form.appendChild(hidden);
    }
    hidden.value = range;

    form.submit(); // ✅ triggers normal form submit → page reloads
  });
});
</script>

  </div>
</nav>


<?php

// ---------- DB Helper ----------
function getValue($sql) {
    global $conn;
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return floatval(array_values($row)[0]);
    }
    return 0;
}

// ---------- Unified Invoice Table Helper ----------
function unifiedInvoiceSQL($select, $where = '') {
    return "
        (
            SELECT $select FROM tbl_customer_invoice_2025 ci WHERE 1 $where 
        )
        UNION ALL
        (
            SELECT $select FROM tbl_customer_invoice ci WHERE 1 $where 
        )
    ";
}

// ---------- Sales Helper ----------
function getSales($where, $dateFilter = '', $extraFilter = '') {
    global $conn;
    $sql = "
        SELECT 
            COALESCE(SUM(tc.Total),0) AS NetAmount,
            COALESCE(SUM(tc.Qty),0) AS TotSell
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
        INNER JOIN tbl_users tu ON tc.FrId = tu.id
        WHERE $where $dateFilter $extraFilter
    ";
    $res = $conn->query($sql);
    return $res ? $res->fetch_assoc() : ['NetAmount' => 0, 'TotSell' => 0];
}

// ---------- Dates ----------
$today       = date('Y-m-d');
$monthStart  = date('Y-m-01');
$monthEnd    = date('Y-m-t');

// ---------- Default Filter ----------
$dateFilter = " AND ci.InvoiceDate = '$today' AND ci.FrId IN ($frids)";
$prodDateFilter = " AND tc.CreatedDate = '$today' AND tc.FrId IN ($frids)";
$extraFilter2 = "";
$filterStart = $today;
$filterEnd   = $today;

// ---------- Calendar Filter ----------
if (!empty($_POST['calendar'])) {
    $reportType = $_POST['calendar'];

    switch ($reportType) {
        case 'yesterday':
            $yesterday = date('Y-m-d', strtotime("-1 day"));
            $dateFilter = " AND ci.InvoiceDate = '$yesterday' AND ci.FrId IN ($frids) ";
            $prodDateFilter = " AND tc.CreatedDate = '$yesterday' AND tc.FrId IN ($frids) ";
            $targetMonth = date('m', strtotime($yesterday));
            $targetYear  = date('Y', strtotime($yesterday));
            $filterStart = $yesterday;
            $filterEnd   = $yesterday;
            $prevStart = date('Y-m-d', strtotime("-2 day"));
            $prevEnd   = date('Y-m-d', strtotime("-2 day"));
            break;

        case 'week':
            $weekStart = date('Y-m-d', strtotime('monday this week'));
            $weekEnd = $today;
            $dateFilter = " AND ci.InvoiceDate BETWEEN '$weekStart' AND '$today' AND ci.FrId IN ($frids) ";
            $prodDateFilter = " AND tc.CreatedDate BETWEEN '$weekStart' AND '$today' AND tc.FrId IN ($frids) ";
            $targetMonth = date('m');
            $targetYear  = date('Y');
            $filterStart = $weekStart;
            $filterEnd   = $today;
            $prevStart = date('Y-m-d', strtotime($weekStart . ' -7 days'));
            $prevEnd   = date('Y-m-d', strtotime($weekEnd . ' -7 days'));
            break;

        case 'month':
            $dateFilter = " AND ci.InvoiceDate BETWEEN '$monthStart' AND '$monthEnd' AND ci.FrId IN ($frids) ";
            $prodDateFilter = " AND tc.CreatedDate BETWEEN '$monthStart' AND '$monthEnd' AND tc.FrId IN ($frids) ";
            $targetMonth = date('m');
            $targetYear  = date('Y');
            $filterStart = $monthStart;
            $filterEnd   = $monthEnd;
            $prevStart = date('Y-m-01', strtotime('first day of last month'));
            $prevEnd   = date('Y-m-t', strtotime('last day of last month'));
            break;

        case 'custom':
            if (!empty($_POST['FromDate']) && !empty($_POST['ToDate'])) {
                $from = $_POST['FromDate'];
                $to   = $_POST['ToDate'];
                $dateFilter = " AND ci.InvoiceDate BETWEEN '$from' AND '$to' AND ci.FrId IN ($frids) ";
                $prodDateFilter = " AND tc.CreatedDate BETWEEN '$from' AND '$to' AND tc.FrId IN ($frids) ";
                $targetMonth = date('m', strtotime($from));
                $targetYear  = date('Y', strtotime($from));
                $filterStart = $from;
                $filterEnd   = $to;
                 // Calculate difference (in days)
                $days = (strtotime($to) - strtotime($from)) / (60 * 60 * 24);

                // Calculate previous equivalent date range
                $prevEnd   = date('Y-m-d', strtotime($from . ' -1 day'));
                $prevStart = date('Y-m-d', strtotime($prevEnd . " -$days day"));
            }
            break;

        default:
            $dateFilter = " AND ci.InvoiceDate = '$today' AND ci.FrId IN ($frids) ";
            $prodDateFilter = " AND tc.CreatedDate = '$today' AND tc.FrId IN ($frids) ";
            $targetMonth = date('m');
            $targetYear  = date('Y');
            $filterStart = $today;
            $filterEnd   = $today;
            $prevStart = date('Y-m-d', strtotime("-1 day"));
            $prevEnd   = date('Y-m-d', strtotime("-1 day"));
    }
} else {
    $dateFilter = " AND ci.InvoiceDate = '$today' AND ci.FrId IN ($frids) ";
    $prodDateFilter = " AND tc.CreatedDate = '$today' AND tc.FrId IN ($frids) ";
    $targetMonth = date('m');
    $targetYear  = date('Y');
    $filterStart = $today;
            $filterEnd   = $today;
            $prevStart = date('Y-m-d', strtotime("-1 day"));
    $prevEnd   = date('Y-m-d', strtotime("-1 day"));
}

// ---------- Main KPI Queries ----------
$sqlTotal = "SELECT COALESCE(SUM(NetAmount),0) FROM (" . unifiedInvoiceSQL("NetAmount", $dateFilter) . ") AS combined";
$sqlCash = "SELECT COALESCE(SUM(NetAmount),0) FROM (" . unifiedInvoiceSQL("NetAmount", "$dateFilter AND PayType='Cash'") . ") AS combined";
$sqlUPI = "SELECT COALESCE(SUM(NetAmount),0) FROM (" . unifiedInvoiceSQL("NetAmount", "$dateFilter AND PayType IN ('Online','UPI','Paytm','Phone Pay','Website online')") . ") AS combined";
$sqlZomato = "SELECT COALESCE(SUM(NetAmount),0) FROM (" . unifiedInvoiceSQL("NetAmount", "$dateFilter AND PayType IN ('Swiggy','Zomato')") . ") AS combined";
$sqlZomatoOrders = "SELECT COUNT(*) FROM (" . unifiedInvoiceSQL("id", "$dateFilter AND PayType IN ('Swiggy','Zomato')") . ") AS combined";
$sqlCredit = "SELECT COALESCE(SUM(NetAmount),0) FROM (" . unifiedInvoiceSQL("NetAmount", "$dateFilter AND PayType='Borrowing'") . ") AS combined";
$sqlDiscountAmt = "SELECT COALESCE(SUM(Discount),0) FROM (" . unifiedInvoiceSQL("Discount", "$dateFilter AND Discount>0") . ") AS combined";

// ---------- Last Week ----------
$lastWeekStart = date('Y-m-d', strtotime('monday last week'));
$lastWeekEnd   = date('Y-m-d', strtotime('sunday last week'));
$sqlLastWeek = "
    SELECT COALESCE(SUM(NetAmount), 0) 
    FROM (" . unifiedInvoiceSQL("NetAmount", "AND InvoiceDate BETWEEN '$prevStart' AND '$prevEnd'") . ") AS combined
";
//echo $sqlLastWeek;
// ---------- Execute ----------
$totalSales     = getValue($sqlTotal);
$cashSales      = getValue($sqlCash);
$upiSales       = getValue($sqlUPI);
$zomatoSales    = getValue($sqlZomato);
$zomatoOrders   = getValue($sqlZomatoOrders);
$creditSales    = getValue($sqlCredit);
$totalDiscount  = getValue($sqlDiscountAmt);
$lastWeekSales  = getValue($sqlLastWeek);

// ---------- KPI Calculations ----------
$growthPct = $lastWeekSales > 0 ? (($totalSales - $lastWeekSales) / $lastWeekSales) * 100 : 0;
$cashPct   = $totalSales > 0 ? ($cashSales / $totalSales) * 100 : 0;
$upiPct    = $totalSales > 0 ? ($upiSales / $totalSales) * 100 : 0;
$creditPct = $totalSales > 0 ? ($creditSales / $totalSales) * 100 : 0;
$discPct   = $totalSales > 0 ? ($totalDiscount / $totalSales) * 100 : 0;

// ---------- Round ----------
$growthPct = round($growthPct, 1);
$cashPct   = round($cashPct, 1);
$upiPct    = round($upiPct, 1);
$creditPct = round($creditPct, 1);
$discPct   = round($discPct, 1);

// ---------- Product Type Sales ----------
$qsrToday   = getSales("tp.ProdType2=2 AND tp.ProdType=0 AND tp.CrossSell!=1 AND tc.FrId IN ($frids) ", $prodDateFilter, $extraFilter2);
$packToday  = getSales("tp.ProdType2=1 AND tp.ProdType=0 AND tp.CrossSell!=1 AND tc.FrId IN ($frids)", $prodDateFilter, $extraFilter2);
$crossToday = getSales("tp.CrossSell=1", $prodDateFilter, $extraFilter2);

// ---------- Calculate ABV ----------
$qsrAbv   = $qsrToday['TotSell'] > 0 ? round($qsrToday['NetAmount'] / $qsrToday['TotSell'], 2) : 0;
$packAbv  = $packToday['TotSell'] > 0 ? round($packToday['NetAmount'] / $packToday['TotSell'], 2) : 0;
$crossAbv = $crossToday['TotSell'] > 0 ? round($crossToday['NetAmount'] / $crossToday['TotSell'], 2) : 0;

// ---------- Formatter ----------
function money($v){ return "₹ " . number_format($v, 2); }

// ---------- Hourly Sales ----------
$hourlySales = [];

for ($h = 0; $h < 24; $h++) { 
    $h1 = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00:00";
    $h2 = str_pad($h, 2, '0', STR_PAD_LEFT) . ":59:59";

    // For single-day filters (today/yesterday/custom single day)
    if ($filterStart == $filterEnd) {
        $hourlySales[] = getValue("
            SELECT COALESCE(SUM(NetAmount),0) FROM (
                " . unifiedInvoiceSQL("NetAmount", "AND InvoiceDate='$filterStart' AND CreatedTime BETWEEN '$h1' AND '$h2'") . "
            ) AS combined
        ");
    } 
    // For multi-day filters (week/month/custom range)
    else {
        $hourlySales[] = getValue("
            SELECT COALESCE(SUM(NetAmount),0) FROM (
                " . unifiedInvoiceSQL("NetAmount", "AND InvoiceDate BETWEEN '$filterStart' AND '$filterEnd' AND TIME(CreatedTime) BETWEEN '$h1' AND '$h2'") . "
            ) AS combined
        ");
    }
}

// ---------- Target ----------
$sqlTarget = "
    SELECT 
        COALESCE(SUM(target), 0) AS target,
        COALESCE(SUM(qsrkitchen_target), 0) AS qsrkitchen_target,
        COALESCE(SUM(packfood_target), 0) AS packfood_target,
        COALESCE(SUM(cross_sale_target), 0) AS cross_sale_target
    FROM tbl_set_target
    WHERE month = '$targetMonth' AND year = '$targetYear' AND frid IN($frids)
";

$resTarget = $conn->query($sqlTarget);
if ($resTarget && $resTarget->num_rows > 0) {
    $targetRow = $resTarget->fetch_assoc();
    $targetGoal      = floatval($targetRow['target']);
    $qsrTarget       = floatval($targetRow['qsrkitchen_target']);
    $packFoodTarget  = floatval($targetRow['packfood_target']);
    $crossSaleTarget = floatval($targetRow['cross_sale_target']);
} else {
    $targetGoal = $qsrTarget = $packFoodTarget = $crossSaleTarget = 0;
}
$achieved=$totalSales; 
$achvPct=$targetGoal>0?round(($achieved/$targetGoal)*100,1):0;

// ---------- Franchise Model ----------
$frModels = [];
$res = $conn->query("SELECT id, Name FROM tbl_fr_model WHERE Status=1 ORDER BY id ASC");
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        $frModels[$r['id']] = $r['Name'];
    }
}

$fofoLabels = [];
$fofoTotals = [];
$fofoABV = [];

foreach ($frModels as $id => $name) {
    $sqlSales = "
        SELECT COALESCE(SUM(NetAmount),0) AS TotalSales, COUNT(id) AS BillCount
        FROM (
            " . unifiedInvoiceSQL("NetAmount, id, FrId", "AND PayType != '' AND FrId IN (SELECT id FROM tbl_users WHERE OwnFranchise=$id) $dateFilter") . "
        ) AS combined
    ";
    $res = $conn->query($sqlSales);
    $data = $res ? $res->fetch_assoc() : ['TotalSales' => 0, 'BillCount' => 0];
    $totalSalesType = floatval($data['TotalSales']);
    $billCount = intval($data['BillCount']);
    $abv = $billCount > 0 ? round($totalSalesType / $billCount, 2) : 0;
    $fofoLabels[] = $name;
    $fofoTotals[] = $totalSalesType;
    $fofoABV[] = $abv;
}

$fofoSales = [
    'labels' => $fofoLabels,
    'totals' => $fofoTotals
];
$fofoABVData = [
    'labels' => $fofoLabels,
    'abv' => $fofoABV
];

// ---------- Repeat Customers ----------
$sqlRepeat = "
    SELECT CellNo, COUNT(*) AS Orders
    FROM (
        " . unifiedInvoiceSQL("CellNo, id, NetAmount, InvoiceDate, PayType", "$dateFilter AND CellNo <> ''") . "
    ) AS combined
    GROUP BY CellNo
";

$resRepeat = $conn->query($sqlRepeat);
$totalCustomers = 0;
$repeatCustomers = 0;
if ($resRepeat && $resRepeat->num_rows > 0) {
    while ($r = $resRepeat->fetch_assoc()) {
        $totalCustomers++;
        if ($r['Orders'] > 1) $repeatCustomers++;
    }
}
$repeatPct = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0;

// ---------- Top 10 Repeated Customers ----------
$sqlTopCustomers = "
    SELECT 
        CustName,
        CellNo,
        MAX(InvoiceDate) AS LastOrder,
        COUNT(*) AS Orders,
        SUM(NetAmount) AS LifetimeValue
    FROM (
        " . unifiedInvoiceSQL("CustName, CellNo, InvoiceDate, NetAmount, PayType", "AND CellNo <> '' AND LENGTH(TRIM(CellNo)) = 10 AND CellNo REGEXP '^[0-9]{10}$' AND PayType!='Borrowing'") . "
    ) AS combined
    GROUP BY CellNo
    HAVING Orders > 1
    ORDER BY Orders DESC, LifetimeValue DESC
    LIMIT 10
";

$resTop = $conn->query($sqlTopCustomers);
$topCustomers = [];
if ($resTop && $resTop->num_rows > 0) {
    while ($row = $resTop->fetch_assoc()) {
        $topCustomers[] = [
            'CustName' => $row['CustName'] ?: 'Unknown',
            'CellNo' => $row['CellNo'],
            'LastOrder' => $row['LastOrder'],
            'Orders' => $row['Orders'],
            'LifetimeValue' => $row['LifetimeValue']
        ];
    }
}

$sqlTopOutlets = "
    SELECT FrId, SUM(NetAmount) AS TotalSales 
    FROM (
        " . unifiedInvoiceSQL("FrId, NetAmount", $dateFilter) . "
    ) AS combined
    GROUP BY FrId 
    ORDER BY TotalSales DESC
    LIMIT 10
";

$resTop = $conn->query($sqlTopOutlets);
$topOutlets = [];
if ($resTop && $resTop->num_rows > 0) {
    while ($row = $resTop->fetch_assoc()) {
        $row2  = getRecord("SELECT ShopName,ZoneId,SubZoneId FROM tbl_users WHERE id='".$row['FrId']."'");
        $row3  = getRecord("SELECT Name FROM tbl_zone WHERE id='".$row2['ZoneId']."'");
        $row4 = getRecord("SELECT Name FROM tbl_sub_zone WHERE id='".$row2['SubZoneId']."'");
      
        $topOutlets[] = [
            'ShopName' => $row2['ShopName'] ?: 'Unknown',
            'Zone' => $row3['Name'],
            'SubZone' => $row4['Name'],
            'Sales' => $row['TotalSales']
        ];
    }
}



$sqlBottomOutlets = "
    SELECT FrId, SUM(NetAmount) AS TotalSales 
    FROM (
        " . unifiedInvoiceSQL("FrId, NetAmount", $dateFilter) . "
    ) AS combined
    GROUP BY FrId 
    HAVING TotalSales > 40
    ORDER BY TotalSales ASC
    LIMIT 10
";

$resBottom = $conn->query($sqlBottomOutlets);
$bottomOutlets = [];
if ($resBottom && $resBottom->num_rows > 0) {
    while ($row = $resBottom->fetch_assoc()) {
        $row2  = getRecord("SELECT ShopName,ZoneId,SubZoneId FROM tbl_users WHERE id='".$row['FrId']."'");
        $row3  = getRecord("SELECT Name FROM tbl_zone WHERE id='".$row2['ZoneId']."'");
        $row4 = getRecord("SELECT Name FROM tbl_sub_zone WHERE id='".$row2['SubZoneId']."'");
      
        $bottomOutlets[] = [
            'ShopName' => $row2['ShopName'] ?: 'Unknown',
            'Zone' => $row3['Name'],
            'SubZone' => $row4['Name'],
            'Sales' => $row['TotalSales']
        ];
    }
}

switch ($reportType ?? 'today') {
    case 'yesterday':
    case 'today':
        $compareLabel = "yesterday";
        break;
    case 'week':
        $compareLabel = "last week";
        break;
    case 'month':
        $compareLabel = "last month";
        break;
    case 'custom':
        $compareLabel = "previous period";
        break;
    default:
        $compareLabel = "yesterday";
}

?>

 
<style>
    .kpi {
  text-decoration: none !important;
}
.kpi a {
  text-decoration: none !important;
  color: inherit !important;
}
</style>

  <main class="container my-4">

    <!-- KPIs -->
    <div class="row g-3">
     <div class="col-6 col-md-4 col-lg-2">
  <div class="card kpi p-3 h-100" data-type="total" style="cursor: pointer;">
      <a href="saledashboard/total-sale.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
        <div class="d-flex align-items-center justify-content-between">
          <div class="icon-wrap"><i class="fa-solid fa-cart-shopping text-primary"></i></div>
          <span class="chip">Total</span>
        </div>
        <div class="mt-3 number" id="kpiTotal">₹ <?php echo number_format($totalSales,2);?></div>
       <div class="subtle">
  <?php if ($growthPct >= 0): ?>
    <i class="fa-solid fa-arrow-trend-up text-success"></i> 
    +<?= $growthPct ?>% vs <?= $compareLabel ?>
  <?php else: ?>
    <i class="fa-solid fa-arrow-trend-down text-danger"></i> 
    <?= $growthPct ?>% vs <?= $compareLabel ?>
  <?php endif; ?>
</div>
      </a>
  </div>
</div>

      <div class="col-6 col-md-4 col-lg-2">
       <div class="card kpi p-3 h-100" data-type="cash" style="cursor: pointer;">
        <a href="saledashboard/total-sale.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="icon-wrap"><i class="fa-solid fa-indian-rupee-sign text-success"></i></div>
            <span class="chip">Cash</span>
          </div>
          <div class="mt-3 number" id="kpiCash">₹ <?php echo number_format($cashSales,2);?></div>
          <div class="subtle"><?= $cashPct ?>% of sales</div>
            </a>
        </div>
      </div>
      
      <div class="col-6 col-md-4 col-lg-2">
        <div class="card kpi p-3 h-100" data-type="upi">
        <a href="saledashboard/total-sale.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="icon-wrap"><i class="fa-solid fa-mobile-screen-button text-info"></i></div>
            <span class="chip">UPI</span>
          </div>
          <div class="mt-3 number" id="kpiUpi">₹ <?php echo number_format($upiSales,2);?></div>
          <div class="subtle">UPI % <span class="fw-bold" id="upiPct"><?= $upiPct ?>%</span></div>
          </a>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="card kpi p-3 h-100" data-type="credit">
        <a href="saledashboard/total-sale.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="icon-wrap"><i class="fa-regular fa-credit-card text-secondary"></i></div>
            <span class="chip">Credit</span>
          </div>
          <div class="mt-3 number" id="kpiCredit">₹ <?php echo number_format($creditSales,2);?></div>
          <div class="subtle"><?= $creditPct ?>% of sales</div>
        </a>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
       <div class="card kpi p-3 h-100" data-type="zomato">
        <a href="saledashboard/total-sale.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="icon-wrap"><i class="fa-solid fa-motorcycle text-danger"></i></div>
            <span class="chip">Zomato/Swiggy</span>
          </div>
          <div class="mt-3 number" id="kpiAgg">₹ <?php echo number_format($zomatoSales,2);?></div>
          <div class="subtle">Orders: <?= $zomatoOrders ?></div>
        </a>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
       <div class="card kpi p-3 h-100" data-type="discount">
        <a href="saledashboard/total-sale.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="icon-wrap"><i class="fa-solid fa-ticket text-warning"></i></div>
            <span class="chip">Discount</span>
          </div>
          <div class="mt-3 number" id="kpiDisc">₹ <?php echo number_format($totalDiscount,2);?></div>
          <div class="subtle">Disc % <span class="fw-bold"><?= $discPct ?>%</span></div>
        </div>
        </a>
      </div>
    </div>

    <!-- Sales Breakdown -->
  <div class="row g-3 mt-1">
  <!-- QSR -->
  <div class="col-lg-4">
    <div class="card p-3 h-100">
    <a href="saledashboard/cross-sale-orders.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="section-title mb-0">
          <i class="fa-solid fa-burger text-primary me-2"></i>QSR
        </h5>
        <span class="chip">Average Bill</span>
      </div>
      <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item d-flex justify-content-between">
          <span>Total Sale Units</span>
          <strong><?= number_format($qsrToday['TotSell']) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between" data-type="qsr">
          <span>Total Amount</span>
          <strong>₹ <?= number_format($qsrToday['NetAmount'], 2) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>ABV</span>
          <strong>₹ <?= number_format($qsrAbv, 2) ?></strong>
        </li>
      </ul>
      </a>
    </div>
  </div>

  <!-- Pack Food -->
  <div class="col-lg-4">
    <div class="card p-3 h-100">
    <a href="saledashboard/cross-sale-orders.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="section-title mb-0">
          <i class="fa-solid fa-box-open text-success me-2"></i>Pack Food
        </h5>
        <span class="chip">Takeaway</span>
      </div>
      <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item d-flex justify-content-between">
          <span>Total Sale Units</span>
          <strong><?= number_format($packToday['TotSell']) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between" data-type="packfood">
          <span>Total Amount</span>
          <strong>₹ <?= number_format($packToday['NetAmount'], 2) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>ABV</span>
          <strong>₹ <?= number_format($packAbv, 2) ?></strong>
        </li>
      </ul>
      </a>
    </div>
  </div>

  <!-- Cross Sale -->
  <div class="col-lg-4">
    <div class="card p-3 h-100">
    <a href="saledashboard/cross-sale-orders.php" onclick="return openFilteredPopup(this);" style="text-decoration:none; color:inherit;">
      <div class="d-flex align-items-center justify-content-between">
        <h5 class="section-title mb-0">
          <i class="fa-solid fa-link text-info me-2"></i>Cross Sale
        </h5>
        <span class="chip">Add-ons</span>
      </div>
      <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item d-flex justify-content-between">
          <span>Total Sale Units</span>
          <strong><?= number_format($crossToday['TotSell']) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between" data-type="crosssale">
          <span>Total Amount</span>
          <strong>₹ <?= number_format($crossToday['NetAmount'], 2) ?></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>ABV</span>
          <strong>₹ <?= number_format($crossAbv, 2) ?></strong>
        </li>
      </ul>
       </a>
    </div>
    
  </div>
</div>



<div class="row g-3 mt-1">
  <div class="col-lg-4"><div class="card p-3 h-100"><h5 class="section-title mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Payment Mix</h5><canvas id="paymentPie" height="240"></canvas><div class="mt-3"><div class="d-flex justify-content-between"><span>UPI Percentage</span><span class="fw-bold" id="upiPct2"><?=$upiPct?>%</span></div><div class="progress mini mt-2"><div class="progress-bar bg-info" style="width:<?=$upiPct?>%"></div></div></div><div class="mt-3"><div class="d-flex justify-content-between"><span>All Percentage (Cash/UPI/Credit)</span><span class="subtle">100% total</span></div></div></div></div>
  <div class="col-lg-5"><div class="card p-3 h-100"><h5 class="section-title mb-3"><i class="fa-solid fa-chart-line me-2 text-success"></i>Hourly Sales</h5><canvas id="hourlyLine" height="240"></canvas><div class="d-flex gap-2 mt-3"><span class="chip"><i class="fa-regular fa-clock me-1"></i>Time filter active</span><span class="chip"><i class="fa-solid fa-calendar-day me-1"></i><span id="rangeLabel">Today</span></span></div></div></div>
  <div class="col-lg-3"><div class="card p-3 h-100"><h5 class="section-title mb-3"><i class="fa-solid fa-bullseye me-2 text-warning"></i>Target vs Achievement</h5><canvas id="targetDoughnut" height="200"></canvas><div class="text-center mt-2"><div class="fw-bold">Achievement: <span id="achvLbl"><?=$achvPct?>%</span></div><div class="subtle">Target ₹ <?=number_format($targetGoal)?></div></div></div></div>
</div>

    <!-- Charts Row -->
    <!--<div class="row g-3 mt-1">
      <div class="col-lg-4">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Payment Mix</h5>
          <canvas id="paymentPie" height="240" aria-label="Payment Mix"></canvas>
          <div class="mt-3">
            <div class="d-flex justify-content-between"><span>UPI Percentage</span>
              <span class="fw-bold" id="upiPct2">63%</span></div>
            <div class="progress mini mt-2">
              <div class="progress-bar bg-info" style="width:63%"></div>
            </div>
          </div>
          <div class="mt-3">
            <div class="d-flex justify-content-between"><span>All Percentage (Cash/UPI/Credit)</span><span class="subtle">100% total</span></div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-chart-line me-2 text-success"></i>Hourly Sales</h5>
          <canvas id="hourlyLine" height="240" aria-label="Hourly Sales"></canvas>
          <div class="d-flex gap-2 mt-3">
            <span class="chip"><i class="fa-regular fa-clock me-1"></i>Time filter active</span>
            <span class="chip"><i class="fa-solid fa-calendar-day me-1"></i><span id="rangeLabel">Today</span></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-bullseye me-2 text-warning"></i>Target vs Achievement</h5>
          <canvas id="targetDoughnut" height="200" aria-label="Target vs Achievement"></canvas>
          <div class="text-center mt-2">
            <div class="fw-bold">Achievement: <span id="achvLbl">82%</span></div>
            <div class="subtle">Target ₹ 15,00,000</div>
          </div>
        </div>
      </div>
    </div>-->

    <!-- Top / Bottom 10 -->
    <div class="row g-3 mt-1">
      <div class="col-lg-6">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-trophy me-2 text-warning"></i>Top 10 Selling</h5>
          <table class="table table-sm align-middle">
            <thead><tr><th>#</th><th>Item</th><th class="text-end">Qty</th><th class="text-end">Amount</th></tr></thead>
            <tbody id="top10Body22">
<?php 
$i = 1;

// Optional: Add a date filter (if you already have $prodDateFilter in use)
$prodDateFilter = isset($prodDateFilter) ? $prodDateFilter : "";

// Step 1: Fetch product-wise total sales
$sql = "
    SELECT 
        p.ProductName,
        SUM(tc.Qty) AS TotalQty,
        SUM(tc.Total) AS TotalAmt
    FROM tbl_customer_invoice_details_2025 tc
    INNER JOIN tbl_cust_products2 p ON p.id = tc.MainProdId
    WHERE 1=1 $prodDateFilter
    GROUP BY tc.MainProdId, p.ProductName
    ORDER BY TotalQty DESC
    LIMIT 10
";

$products = getList($sql);

// Step 2: Display Top 10 Selling Products
foreach($products as $row){
?>
<tr>
  <td><?php echo $i++; ?></td>
  <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
  <td class="text-end"><?php echo (float)$row['TotalQty']; ?></td>
  <td class="text-end">₹ <?php echo number_format($row['TotalAmt'], 2); ?></td>
</tr>
<?php } ?>
</tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-arrow-down-wide-short me-2 text-danger"></i>Bottom 10</h5>
          <table class="table table-sm align-middle">
            <thead><tr><th>#</th><th>Item</th><th class="text-end">Qty</th><th class="text-end">Amount</th></tr></thead>
            <tbody id="bottom10Body22">
             <?php 
$i = 1;

// Optional: Add a date filter (if you already have $prodDateFilter in use)
$prodDateFilter = isset($prodDateFilter) ? $prodDateFilter : "";

// Step 1: Fetch product-wise total sales
$sql = "
    SELECT 
        p.ProductName,
        SUM(tc.Qty) AS TotalQty,
        SUM(tc.Total) AS TotalAmt
    FROM tbl_customer_invoice_details_2025 tc
    INNER JOIN tbl_cust_products2 p ON p.id = tc.MainProdId
    WHERE 1=1 $prodDateFilter
    GROUP BY tc.MainProdId, p.ProductName
    ORDER BY TotalQty ASC
    LIMIT 10
";

$products = getList($sql);

// Step 2: Display Top 10 Selling Products
foreach($products as $row){
?>
<tr>
  <td><?php echo $i++; ?></td>
  <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
  <td class="text-end"><?php echo (float)$row['TotalQty']; ?></td>
  <td class="text-end">₹ <?php echo number_format($row['TotalAmt'], 2); ?></td>
</tr>
<?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- FOFO/FICO/FOCO -->
    <div class="row g-3 mt-1">
      <div class="col-lg-6">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-store me-2 text-primary"></i>COCO / FOFO / COFO / FOCO – Total Sales</h5>
          <canvas id="fofoBar" height="240" aria-label="COCO FOFO FICO FOCO Sales"></canvas>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-scale-balanced me-2 text-secondary"></i>COCO / FOFO / COFO / FOCO – ABV</h5>
          <canvas id="fofoAbv" height="240" aria-label="FOFO FICO FOCO ABV"></canvas>
        </div>
      </div>
    </div>

    <!-- Customers -->
    <div class="row g-3 mt-1">
      <div class="col-lg-4">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-face-smile me-2 text-success"></i>Feedbacks</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
              <span><i class="fa-regular fa-thumbs-up text-success me-2"></i>Positive</span><strong>78%</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span><i class="fa-regular fa-thumbs-down text-danger me-2"></i>Negative</span><strong>9%</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span><i class="fa-regular fa-comment-dots text-info me-2"></i>Neutral</span><strong>13%</strong>
            </li>
          </ul>
          <div class="mt-3">
            <span class="chip"><i class="fa-solid fa-rotate me-1"></i>Refresh weekly</span>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-solid fa-user-clock me-2 text-warning"></i>Repeat Customers</h5>
          <div class="display-6 fw-bold"><?= $repeatPct ?>%</div>
          <div class="subtle">Returning customers this period</div>
          <div class="progress mini mt-3">
            <div class="progress-bar bg-warning" style="width:<?= $repeatPct ?>%"></div>
          </div>
          <div class="mt-3">
            <i class="fa-solid fa-database me-2 text-secondary"></i><span class="subtle">Customer data enriched</span>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card p-3 h-100">
          <h5 class="section-title mb-3"><i class="fa-brands fa-google me-2 text-danger"></i>Google Ratings</h5>
          <div class="d-flex align-items-center gap-2">
            <div class="display-6 fw-bold">4.3</div>
            <div class="rating">
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-regular fa-star"></i>
            </div>
          </div>
          <div class="subtle">based on 1,284 reviews</div>
        </div>
      </div>
    </div>

    <!-- Customer Data Table -->
   <!-- <div class="card p-3 mt-3">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="section-title mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Top 10 Customer Data</h5>
        <div class="input-group" style="max-width:280px;">
          <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text" class="form-control" placeholder="Search customer…">
        </div>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Name</th><th>Phone</th><th>Last Order</th><th>Orders</th><th>Lifetime Value</th>
            </tr>
          </thead>
          <tbody>
      <?php if (!empty($topCustomers)): ?>
        <?php foreach ($topCustomers as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['CustName']) ?></td>
            <td><?= htmlspecialchars($c['CellNo']) ?></td>
            <td><?= htmlspecialchars($c['LastOrder']) ?></td>
            <td><?= htmlspecialchars($c['Orders']) ?></td>
            <td>₹ <?= number_format($c['LifetimeValue'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center text-muted">No repeated customers found</td></tr>
      <?php endif; ?>
    </tbody>
        </table>
      </div>
    </div>-->
    
    <div class="row g-3 mt-1">
      <div class="col-lg-6">
     <div class="card p-3 mt-3">
      <div class="d-flex justify-content-between align-items-center">
  <h5 class="section-title mb-0">
    <i class="fa-solid fa-trophy me-2 text-warning"></i>Top 10 Outlets
  </h5>
</div>

      <div class="table-responsive mt-3">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Outlet Name </th><th>Zone </th><th>Sub Zone</th><th>Sales</th>
            </tr>
          </thead>
          <tbody>
      <?php if (!empty($topOutlets)): ?>
        <?php foreach ($topOutlets as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['ShopName']) ?></td>
            <td><?= htmlspecialchars($c['Zone']) ?></td>
            <td><?= htmlspecialchars($c['SubZone']) ?></td>
            <td>₹<?= htmlspecialchars(number_format($c['Sales'],2)) ?></td>
            
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="4" class="text-center text-muted">No Outlet found</td></tr>
      <?php endif; ?>
    </tbody>
        </table>
      </div>
    </div>
    </div>
    
    
    <div class="col-lg-6">
     <div class="card p-3 mt-3">
      <div class="d-flex justify-content-between align-items-center">
  <h5 class="section-title mb-0">
    <i class="fa-solid fa-arrow-trend-down me-2 text-danger"></i>Bottom 10 Outlets
  </h5>
</div>

      <div class="table-responsive mt-3">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Outlet Name </th><th>Zone </th><th>Sub Zone</th><th>Sales</th>
            </tr>
          </thead>
          <tbody>
      <?php if (!empty($bottomOutlets)): ?>
        <?php foreach ($bottomOutlets as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['ShopName']) ?></td>
            <td><?= htmlspecialchars($c['Zone']) ?></td>
            <td><?= htmlspecialchars($c['SubZone']) ?></td>
            <td>₹<?= htmlspecialchars(number_format($c['Sales'],2)) ?></td>
            
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="4" class="text-center text-muted">No Outlet found</td></tr>
      <?php endif; ?>
    </tbody>
        </table>
      </div>
    </div>
    </div>
    </div>

    <footer class="text-center text-muted small my-4">
      © 2025 Kwick Bill • Sale Dashboard
    </footer>
  </main>


<!-- COMMON DATA MODAL -->
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="dataModalLabel">Records</h5>
        <div>
          <button id="exportExcelBtn" class="btn btn-sm btn-outline-success">
            <i class="fa-solid fa-file-excel"></i> Excel
          </button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="dataLoader" class="text-center py-4">
          <div class="spinner-border text-primary"></div>
          <p class="mt-2 text-muted">Loading records...</p>
        </div>
        <div class="table-responsive d-none" id="dataTableWrapper">
          <table class="table table-striped align-middle" id="dataTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Pay Type</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- RECORD DISPLAY SECTION -->
<div id="recordSection" class="mt-4 d-none">
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0" id="recordTitle">Records</h5>
      <button class="btn btn-light btn-sm" id="hideRecordSection">
        <i class="fa-solid fa-xmark"></i> Close
      </button>
    </div>
    <div class="card-body">
      <div id="recordLoader" class="text-center py-4">
        <div class="spinner-border text-primary"></div>
        <p class="mt-2 text-muted">Loading records...</p>
      </div>
      <div class="table-responsive d-none" id="recordTableWrapper">
        <table class="table table-striped align-middle" id="recordTable">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Invoice No</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Amount</th>
              <th>Pay Type</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>



  <!-- Bootstrap JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  let currentType = '';
  let currentFrom = '';
  let currentTo = '';
  let currentCalendar = '';
  let currentPage = 1;
  let totalPages = 1;

  const modalEl = document.getElementById('dataModal');
  const modal = new bootstrap.Modal(modalEl);
  const tbody = document.querySelector('#dataTable tbody');
  const thead = document.querySelector('#dataTable thead tr');
  const loader = document.getElementById('dataLoader');
  const wrapper = document.getElementById('dataTableWrapper');
  const modalTitle = document.getElementById('dataModalLabel');
  const paginationDiv = document.createElement('div');
  paginationDiv.className = "d-flex justify-content-between align-items-center mt-2";
  wrapper.after(paginationDiv);

  // 🔹 Reusable Fetch Function
  function loadRecords(page = 1) {
    loader.classList.remove('d-none');
    wrapper.classList.add('d-none');
    modalTitle.textContent = 'Loading...';
    tbody.innerHTML = '';

    const query = `fetch_dashboard_records.php?type=${currentType}&from=${currentFrom}&to=${currentTo}&calendar=${currentCalendar}&page=${page}&per_page=10`;
    console.log("Fetching:", query);

    fetch(query)
      .then(res => res.json())
      .then(data => {
        modalTitle.textContent = data.title || 'Records';
        currentPage = data.page || 1;
        totalPages = data.total_pages || 1;
        const rows = data.rows || [];

        if (['qsr', 'packfood', 'crosssale'].includes(currentType)) {
          thead.innerHTML = `
            <th>#</th>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Amount</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Pay Type</th>`;
        } else if (currentType === 'discount') {
          thead.innerHTML = `
            <th>#</th>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Discount %</th>
            <th>Discount Amt</th>
            <th>Pay Type</th>`;
        } else {
          thead.innerHTML = `
            <th>#</th>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Pay Type</th>`;
        }

        if (!rows.length) {
          tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted">No records found</td></tr>`;
        } else {
          rows.forEach((r, i) => {
            if (['qsr', 'packfood', 'crosssale'].includes(currentType)) {
              tbody.innerHTML += `
                <tr>
                  <td>${(i + 1) + (currentPage - 1) * 10}</td>
                  <td>${r.InvoiceNo}</td>
                  <td>${r.InvoiceDate}</td>
                  <td>${r.ProductName || ''}</td>
                  <td>${r.Qty || 0}</td>
                  <td>₹ ${parseFloat(r.Amount || 0).toLocaleString('en-IN')}</td>
                  <td>${r.CustName || ''}</td>
                  <td>${r.CellNo || ''}</td>
                  <td>${r.PayType || ''}</td>
                </tr>`;
            } else if (currentType === 'discount') {
              tbody.innerHTML += `
                <tr>
                  <td>${(i + 1) + (currentPage - 1) * 10}</td>
                  <td>${r.InvoiceNo}</td>
                  <td>${r.InvoiceDate}</td>
                  <td>${r.CustName || ''}</td>
                  <td>${r.CellNo || ''}</td>
                  <td>₹ ${parseFloat(r.NetAmount || 0).toLocaleString('en-IN')}</td>
                  <td>${r.DiscPer ? r.DiscPer + '%' : '-'}</td>
                  <td>₹ ${parseFloat(r.Discount || 0).toLocaleString('en-IN')}</td>
                  <td>${r.PayType}</td>
                </tr>`;
            } else {
              tbody.innerHTML += `
                <tr>
                  <td>${(i + 1) + (currentPage - 1) * 10}</td>
                  <td>${r.InvoiceNo}</td>
                  <td>${r.InvoiceDate}</td>
                  <td>${r.CustName || ''}</td>
                  <td>${r.CellNo || ''}</td>
                  <td>₹ ${parseFloat(r.NetAmount || 0).toLocaleString('en-IN')}</td>
                  <td>${r.PayType}</td>
                </tr>`;
            }
          });
        }

        // 🔹 Pagination Controls
        paginationDiv.innerHTML = `
          <div>
            <button id="prevPage" class="btn btn-sm btn-outline-secondary" ${currentPage <= 1 ? 'disabled' : ''}>Previous</button>
            <button id="nextPage" class="btn btn-sm btn-outline-secondary" ${currentPage >= totalPages ? 'disabled' : ''}>Next</button>
          </div>
          <small class="text-muted">Page ${currentPage} of ${totalPages} (${data.total_records} records)</small>
        `;

        document.getElementById('prevPage')?.addEventListener('click', () => {
          if (currentPage > 1) loadRecords(currentPage - 1);
        });

        document.getElementById('nextPage')?.addEventListener('click', () => {
          if (currentPage < totalPages) loadRecords(currentPage + 1);
        });

        loader.classList.add('d-none');
        wrapper.classList.remove('d-none');
      })
      .catch(err => {
        console.error("Fetch error:", err);
        modalTitle.textContent = 'Error loading data';
      });
  }

  // 🔹 For Record Click (QSR / PackFood / CrossSale)
  document.querySelectorAll('.record-click').forEach(el => {
    el.style.cursor = 'pointer';
    el.addEventListener('click', () => {
      currentType = el.dataset.type;
      currentFrom = document.getElementById('fromDate')?.value || '';
      currentTo = document.getElementById('toDate')?.value || '';
      currentCalendar =
        document.querySelector('button[name="calendar"].active')?.value ||
        document.querySelector('input[name="calendar"]:checked')?.value ||
        'custom';
      currentPage = 1;

      modal.show();
      loadRecords(currentPage);
    });
  });

  // 🔹 For KPI Click (Cash, UPI, Credit, Discount, etc.)
  document.querySelectorAll('.kpi-click').forEach(el => {
    el.style.cursor = 'pointer';
    el.addEventListener('click', () => {
      currentType = el.dataset.type;
      currentFrom = document.getElementById('fromDate')?.value || '';
      currentTo = document.getElementById('toDate')?.value || '';
      currentCalendar =
        document.querySelector('button[name="calendar"].active')?.value ||
        document.querySelector('input[name="calendar"]:checked')?.value ||
        'custom';
      currentPage = 1;

      modal.show();
      loadRecords(currentPage);
    });
  });

  // 🔹 Excel Export
  document.getElementById('exportExcelBtn').addEventListener('click', () => {
    if (!currentType) return alert('Please open a record first.');
    const from = currentFrom || document.getElementById('fromDate')?.value || '';
    const to = currentTo || document.getElementById('toDate')?.value || '';
    const cal = currentCalendar || 'custom';
    window.open(`fetch_dashboard_records.php?type=${currentType}&from=${from}&to=${to}&calendar=${cal}&action=excel`, '_blank');
  });
});
</script>



  <script>
    // ---- Sample data (replace with real values) ----
    const paymentMix={cash:<?=$cashPct?>,upi:<?=$upiPct?>,credit:<?=$creditPct?>};
    const hourly=<?php echo json_encode($hourlySales); ?>;
    /*const top10 = [
      ["Masala Tea", 620, 74400], ["Cheese Sandwich", 515, 77250],
      ["Cold Coffee", 498, 99600], ["Veg Puff", 470, 37600], ["French Fries", 455, 68300],
      ["Brownie", 420, 84000], ["Pav Bhaji", 390, 97650], ["Garlic Bread", 375, 56250],
      ["Choco Shake", 352, 88000], ["Samosa", 338, 27040]
    ];
    const bottom10 = [
      ["Herbal Latte", 22, 3080], ["Quinoa Salad", 25, 5250], ["Protein Bar", 28, 3920],
      ["Veg Lasagna", 31, 8060], ["Thai Curry", 33, 8580], ["Keto Cookie", 35, 4550],
      ["Paneer Wrap", 38, 6840], ["Peach Iced Tea", 40, 5200], ["Pesto Pasta", 41, 9430], ["BBQ Sandwich", 45, 8100]
    ];*/
   const fofoSales = <?= json_encode($fofoSales) ?>;
const fofoABV   = <?= json_encode($fofoABVData) ?>;
   const target={goal:<?=$targetGoal?>,achieved:<?=$achieved?>};

    // ---- Populate tables ----
    function currency(n){ return "₹ " + n.toLocaleString("en-IN"); }
    /*function renderList(id, arr){
      const tbody = document.getElementById(id);
      tbody.innerHTML = arr.map((r,i)=>`
        <tr>
          <td>${i+1}</td>
          <td>${r[0]}</td>
          <td class="text-end">${r[1]}</td>
          <td class="text-end">${currency(r[2])}</td>
        </tr>`).join("");
    }
    renderList("top10Body", top10);
    renderList("bottom10Body", bottom10);*/

    // ---- Charts ----
    Chart.defaults.font.family = "'Inter', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial";

    // Payment pie
    new Chart(document.getElementById("paymentPie"), {
      type: "pie",
      data: {
        labels: ["Cash","UPI","Credit"],
        datasets: [{ data: [paymentMix.cash, paymentMix.upi, paymentMix.credit] }]
      },
      options: { plugins:{legend:{position:"bottom"}} }
    });

    // Hourly sales line
    new Chart(document.getElementById("hourlyLine"), {
      type: "line",
      data: {
        labels: Array.from({length:24}, (_,i)=> (i<10?"0":"") + i + ":00"),
        datasets: [{ label:"Sales", data: hourly, tension:.35, fill:true }]
      },
      options: {
        scales:{ y:{ beginAtZero:true } },
        plugins:{ legend:{display:false} }
      }
    });

    // Target vs Achievement
    const achvPct = Math.round((target.achieved/target.goal)*100);
    document.getElementById("achvLbl").textContent = achvPct + "%";
    new Chart(document.getElementById("targetDoughnut"), {
      type: "doughnut",
      data: {
        labels:["Achieved","Remaining"],
        datasets:[{ data:[achvPct, 100-achvPct] }]
      },
      options:{ cutout:"70%", plugins:{legend:{display:false}} }
    });

    // COCO/FOFO/FOCO/COFO Total Sales
new Chart(document.getElementById("fofoBar"), {
  type: "bar",
  data: {
    labels: fofoSales.labels,
    datasets: [{
      label: "Total Sales (₹)",
      data: fofoSales.totals,
      backgroundColor: ["#3b82f6", "#22c55e", "#f59e0b", "#a855f7"]
    }]
  },
  options: {
    plugins:{legend:{display:false}},
    scales:{ y:{ beginAtZero:true } }
  }
});

// COCO/FOFO/FOCO/COFO ABV
new Chart(document.getElementById("fofoAbv"), {
  type: "bar",
  data: {
    labels: fofoABV.labels,
    datasets: [{
      label: "ABV (₹)",
      data: fofoABV.abv,
      backgroundColor: ["#06b6d4", "#16a34a", "#eab308", "#8b5cf6"]
    }]
  },
  options: {
    plugins:{legend:{display:false}},
    scales:{ y:{ beginAtZero:true } }
  }
});

    // ---- Date helpers (demo only) ----
    const from = document.getElementById("fromDate");
    const to   = document.getElementById("toDate");
    const label= document.getElementById("rangeLabel");
    document.querySelectorAll(".range").forEach(el=>{
      el.addEventListener("click", e=>{
        e.preventDefault();
        const t = new Date();
        const fmt = d => d.toISOString().slice(0,10);
        let start = new Date(t), end = new Date(t);
        if(el.dataset.range==="today"){ /* same day */ }
        if(el.dataset.range==="yesterday"){ start.setDate(t.getDate()-1); end.setDate(t.getDate()-1); label.textContent="Yesterday"; }
        if(el.dataset.range==="week"){ start.setDate(t.getDate()-6); label.textContent="Last 7 Days"; }
        if(el.dataset.range==="month"){ start.setDate(1); label.textContent="This Month"; }
        from.value = fmt(start); to.value = fmt(end);
      });
    });
    document.getElementById("applyFilter").addEventListener("click", ()=>{
      label.textContent = from.value && to.value ? (from.value+" → "+to.value) : label.textContent;
      // hook your AJAX/Fetch here to reload data using the selected dates
    });

    // Set initial quick range
    document.querySelector('.range[data-range="today"]').click();
    // UPI percent labels
    document.getElementById("upiPct").textContent = paymentMix.upi + "%";
    document.getElementById("upiPct2").textContent = paymentMix.upi + "%";
    
 
 function setCalendar(value) {
  document.getElementById('SelectedCalendar').value = value;
}

function formatDateLocal(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

function openFilteredPopup(link) {
  const calendar = document.getElementById('SelectedCalendar')?.value || 'custom';
  let fromDate, toDate;
  const today = new Date();

  switch (calendar) {
    case 'today':
      fromDate = toDate = formatDateLocal(today);
      break;
    case 'yesterday':
      const y = new Date();
      y.setDate(today.getDate() - 1);
      fromDate = toDate = formatDateLocal(y);
      break;
    case 'week':
      const weekAgo = new Date();
      weekAgo.setDate(today.getDate() - 6);
      fromDate = formatDateLocal(weekAgo);
      toDate = formatDateLocal(today);
      break;
    case 'month':
      const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
      const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
      fromDate = formatDateLocal(firstDay);
      toDate = formatDateLocal(lastDay);
      break;
    default:
      fromDate = document.getElementById('fromDate')?.value || '';
      toDate = document.getElementById('toDate')?.value || '';
      break;
  }

  const url = `${link.getAttribute('href')}?FromDate=${encodeURIComponent(fromDate)}&ToDate=${encodeURIComponent(toDate)}&calendar=${encodeURIComponent(calendar)}`;

  // ✅ Must return false to prevent default, but ensure window.open executes immediately
  window.open(url, '_blank', 'width=1200,height=600,left=50,top=50,scrollbars=yes,resizable=yes');
  return false;
}
  </script>
</body>
</html>
