<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage = "Dashboard";
$Page = "Dashboard";
$frId = trim((string) ($_REQUEST['id'] ?? ''));
if ($frId !== '') {
    $_SESSION['fr_admin'] = $frId;
} elseif (!isset($_SESSION['fr_admin'])) {
    $_SESSION['fr_admin'] = $_SESSION['Admin']['id'] ?? '';
}

function monthlyIncome($BillSoftFrId, $month, $year)
{
    global $conn;
    $sql = "SELECT COALESCE(SUM(NetAmount), 0) AS TotalNetAmount 
            FROM tbl_customer_invoice_2025 
            WHERE MONTH(InvoiceDate) = '$month' 
            AND YEAR(InvoiceDate) = '$year' 
            AND FrId = '$BillSoftFrId'";
    $res2 = $conn->query($sql);
    if (!$res2) {
        return 0;
    }
    $row2 = $res2->fetch_assoc();
    return (float) ($row2['TotalNetAmount'] ?? 0);
}

function getWeeksInMonth($year, $month)
{
    $weeks = [];
    $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $startOfWeek = 1;
    while ($startOfWeek <= $totalDays) {
        $from_date = date('Y-m-d', strtotime("$year-$month-$startOfWeek"));
        $endOfWeek = $startOfWeek + (7 - date('N', strtotime($from_date)));
        if ($endOfWeek > $totalDays) {
            $endOfWeek = $totalDays;
        }
        $to_date = date('Y-m-d', strtotime("$year-$month-$endOfWeek"));
        $weeks[] = ['from_date' => $from_date, 'to_date' => $to_date];
        $startOfWeek = $endOfWeek + 1;
    }
    return $weeks;
}

function weeklyIncome($BillSoftFrId, $fromdate, $todate)
{
    global $conn;
    $sql = "SELECT COALESCE(SUM(NetAmount), 0) as total 
            FROM tbl_customer_invoice_2025 
            WHERE FrId = '$BillSoftFrId' 
            AND InvoiceDate BETWEEN '$fromdate' AND '$todate'";
    $res2 = $conn->query($sql);
    if (!$res2) {
        return 0;
    }
    $row2 = $res2->fetch_assoc();
    return (float) ($row2['total'] ?? 0);
}

function fmtMoney($amount)
{
    return '₹' . number_format((float) ($amount ?? 0), 2);
}

function stockBarMeta($daysLeft)
{
    if ($daysLeft <= 10) {
        return ['width' => 20, 'color' => 'danger'];
    }
    if ($daysLeft <= 20) {
        return ['width' => 40, 'color' => 'warning'];
    }
    if ($daysLeft <= 30) {
        return ['width' => 60, 'color' => 'info'];
    }
    if ($daysLeft <= 40) {
        return ['width' => 80, 'color' => 'primary'];
    }
    return ['width' => 90, 'color' => 'success'];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/morris/morris.css">
</head>

<body>
    <style>
        .fr-dash { --dash-primary: #1a56db; --dash-accent: #ff4a00; --dash-success: #0e9f6e; --dash-warning: #f59e0b; --dash-danger: #e02424; --dash-surface: #ffffff; --dash-muted: #6b7280; --dash-border: #e5e7eb; --dash-shadow: 0 4px 24px rgba(15, 23, 42, 0.06); }
        .fr-dash-hero { background: linear-gradient(135deg, #1e3a8a 0%, #1a56db 55%, #3b82f6 100%); border-radius: 16px; color: #fff; padding: 1.75rem 2rem; margin-bottom: 1.5rem; box-shadow: var(--dash-shadow); position: relative; overflow: hidden; }
        .fr-dash-hero::after { content: ''; position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; background: rgba(255,255,255,0.08); border-radius: 50%; }
        .fr-dash-hero h4 { font-weight: 600; margin-bottom: 0.25rem; letter-spacing: -0.02em; }
        .fr-dash-hero .hero-meta { opacity: 0.9; font-size: 0.9rem; }
        .fr-dash-hero .hero-stat { background: rgba(255,255,255,0.15); border-radius: 12px; padding: 0.85rem 1.1rem; backdrop-filter: blur(4px); }
        .fr-dash-hero .hero-stat .label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; opacity: 0.85; margin-bottom: 0.15rem; }
        .fr-dash-hero .hero-stat .value { font-size: 1.35rem; font-weight: 600; line-height: 1.2; }
        .fr-section-title { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--dash-muted); margin: 0.5rem 0 1rem; padding-left: 0.25rem; }
        .fr-stat-card { border: none; border-radius: 14px; box-shadow: var(--dash-shadow); transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden; height: 100%; }
        .fr-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(15, 23, 42, 0.1); }
        .fr-stat-card a { text-decoration: none; color: inherit; display: block; height: 100%; }
        .fr-stat-card .card-body { padding: 1.15rem 1.25rem; display: flex; align-items: center; gap: 1rem; }
        .fr-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
        .fr-stat-icon.sales { background: rgba(26, 86, 219, 0.12); color: var(--dash-primary); }
        .fr-stat-icon.cash { background: rgba(14, 159, 110, 0.12); color: var(--dash-success); }
        .fr-stat-icon.upi { background: rgba(245, 158, 11, 0.12); color: var(--dash-warning); }
        .fr-stat-icon.products { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }
        .fr-stat-icon.alert { background: rgba(224, 36, 36, 0.12); color: var(--dash-danger); }
        .fr-stat-label { font-size: 0.8rem; color: var(--dash-muted); font-weight: 500; margin-bottom: 0.2rem; }
        .fr-stat-value { font-size: 1.35rem; font-weight: 600; color: #111827; line-height: 1.2; }
        .fr-panel { border: none; border-radius: 14px; box-shadow: var(--dash-shadow); margin-bottom: 1.5rem; overflow: hidden; }
        .fr-panel .card-header { background: #f9fafb; border-bottom: 1px solid var(--dash-border); font-weight: 600; font-size: 0.95rem; padding: 0.9rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
        .fr-panel .card-header .btn-link-view { font-size: 0.8rem; font-weight: 500; color: var(--dash-primary); padding: 0.2rem 0.6rem; border-radius: 6px; }
        .fr-panel .card-header .btn-link-view:hover { background: rgba(26, 86, 219, 0.08); text-decoration: none; }
        .fr-panel .card-body { padding: 1.25rem; }
        .fr-stock-item { margin-bottom: 1.1rem; }
        .fr-stock-item:last-child { margin-bottom: 0; }
        .fr-stock-item .stock-name { font-size: 0.875rem; font-weight: 500; color: #374151; }
        .fr-stock-item .stock-days { font-size: 0.8rem; color: var(--dash-muted); }
        .fr-stock-item .progress { height: 8px; border-radius: 99px; background: #f3f4f6; margin-top: 0.35rem; }
        .fr-stock-item .progress-bar { border-radius: 99px; }
        .fr-table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--dash-muted); font-weight: 600; border-top: none; white-space: nowrap; }
        .fr-table tbody td { vertical-align: middle; font-size: 0.875rem; }
        .fr-table .profit-positive { color: var(--dash-success); font-weight: 600; }
        .fr-chart-card .card-body { padding: 1rem 1.25rem 0.5rem; }
        .fr-chart-card .chart-title { font-weight: 600; font-size: 0.95rem; color: #111827; margin-bottom: 0.75rem; }
        .fr-empty-state { text-align: center; padding: 2rem 1rem; color: var(--dash-muted); font-size: 0.9rem; }
        @media (max-width: 991px) {
            .fr-dash-hero { padding: 1.25rem; }
            .fr-dash-hero .hero-stat { margin-top: 0.75rem; }
        }
    </style>

    <div class="layout-wrapper layout-1 layout-without-sidenav fr-dash">
        <div class="layout-inner">

            <?php include_once 'top_header.php';
            include_once 'sidebar.php';

            $today = date('Y-m-d');
            $shopName = trim(($row77['ShopName'] ?? '') ?: (($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? '')));

            $todaySellCount = getRow("SELECT * FROM tbl_customer_invoice_2025 WHERE InvoiceDate='$today' AND FrId='$BillSoftFrId'");
            $todaySellRow = getRecord("SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM tbl_customer_invoice_2025 WHERE InvoiceDate='$today' AND FrId='$BillSoftFrId'");
            $todayCashRow = getRecord("SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM tbl_customer_invoice_2025 WHERE InvoiceDate='$today' AND FrId='$BillSoftFrId' AND PayType='Cash'");
            $todayUpiRow = getRecord("SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM tbl_customer_invoice_2025 WHERE InvoiceDate='$today' AND FrId='$BillSoftFrId' AND PayType!='Cash'");

            $sellingProdCount = getRow("SELECT * FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0 AND checkstatus=1 AND ProdType=0 AND ProdType2!=3");
            $rawProdCount = getRow("SELECT * FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0 AND checkstatus=1 AND ProdType=1");
            $otherProdCount = getRow("SELECT * FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0 AND checkstatus=1 AND ProdType=0 AND ProdType2=3");

            $lowStockSql = "SELECT COUNT(*) AS Low_Stock_Count FROM 
                ( SELECT COALESCE(p.MinQty, 0) AS MinQty, 
                COALESCE(SUM(CASE WHEN s.Status = 'Cr' THEN s.Qty ELSE 0 END) - SUM(CASE WHEN s.Status = 'Dr' THEN s.Qty ELSE 0 END), 0) AS balqty 
                FROM tbl_cust_products_2025 p INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                LEFT JOIN tbl_cust_prod_stock_2025 s ON p.id = s.ProdId AND s.ProdType = 0 AND s.FrId = '$BillSoftFrId' 
                AND s.StockDate BETWEEN '2025-01-28' AND '$today' WHERE p.CreatedBy = '$BillSoftFrId' AND p.ProdType = 0 AND p.ProdType2 IN (1,3) 
                AND p.CatId != 28 AND p.delete_flag = 0 AND p.checkstatus = 1 GROUP BY p.id ORDER BY p.ProductName ASC) as a WHERE balqty < MinQty";
            $lowStockRow = getRecord($lowStockSql);
            $lowStockCount = $lowStockRow['Low_Stock_Count'] ?? 0;

            $incomeSql = "SELECT tc.FrId, LEFT(tu.ShopName, 10) AS ShopName, COALESCE(SUM(tc.NetAmount), 0) AS TotalIncome, 
                COALESCE(SUM(CASE WHEN tc.InvoiceDate = '$today' THEN tc.NetAmount ELSE 0 END), 0) AS TodayIncome 
                FROM tbl_customer_invoice_2025 AS tc 
                INNER JOIN tbl_users_bill AS tu ON tu.id = tc.FrId WHERE tc.FrId = '$BillSoftFrId' GROUP BY tc.FrId";
            $incomeRow = getRecord($incomeSql);
            $TotalIncome = $incomeRow['TotalIncome'] ?? 0;
            $TodayIncome = $incomeRow['TodayIncome'] ?? 0;

            $productList = [];
            $limitRecords = 6;
            $stockSql = "SELECT p.BarcodeNo, p.CreatedBy AS FrId, p.id AS ProdId, p.ProductName, 
                       tcc.Name AS CatName, COALESCE(p.MinQty, 0) AS MinQty, 
                       p.PurchasePrice, p.MinPrice 
                FROM tbl_cust_products_2025 p 
                INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                WHERE p.CreatedBy = '$BillSoftFrId' 
                AND p.ProdType = 0 
                AND p.ProdType2 IN (1,3) 
                AND p.CatId != 28 
                AND p.delete_flag = 0 
                AND p.checkstatus = 1 
                GROUP BY p.id 
                ORDER BY p.ProductName ASC";
            $res = $conn->query($stockSql);
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $sql2 = "SELECT COALESCE(SUM(creditqty), 0) AS creditqty, 
                            COALESCE(SUM(debitqty), 0) AS debitqty, 
                            COALESCE(SUM(creditqty) - SUM(debitqty), 0) AS balqty 
                     FROM (
                        SELECT 
                            (CASE WHEN Status='Dr' THEN SUM(Qty) ELSE '0' END) as debitqty, 
                            (CASE WHEN Status='Cr' THEN SUM(Qty) ELSE '0' END) as creditqty 
                        FROM `tbl_cust_prod_stock_2025` 
                        WHERE ProdId='" . $row['ProdId'] . "' 
                        AND ProdType=0 
                        AND FrId='$BillSoftFrId' 
                        AND StockDate >= '2025-01-28' 
                        AND StockDate <= '$today' 
                        GROUP BY Status
                    ) as a";
                    $row2 = getRecord($sql2);
                    $yesterday = date('Y-m-d', strtotime('-1 day'));
                    $sql3 = "SELECT COALESCE(SUM(Qty), 0) AS sellqty 
                     FROM tbl_customer_invoice_details_2025 
                     WHERE FrId='$BillSoftFrId' 
                     AND ProdId='" . $row['ProdId'] . "' 
                     AND CreatedDate='$yesterday'";
                    $row3 = getRecord($sql3);
                    $dailySale = $row3['sellqty'] ?? 0;
                    $balqty = $row2['balqty'] ?? 0;
                    if ($dailySale > 0) {
                        $daysLeft = (int) ceil($balqty / $dailySale);
                    } else {
                        $daysLeft = PHP_INT_MAX;
                    }
                    if ($balqty > 0 && $daysLeft !== PHP_INT_MAX) {
                        $productList[] = [
                            'ProductName' => $row['ProductName'],
                            'balqty' => $balqty,
                            'daysLeft' => $daysLeft,
                        ];
                    }
                }
            }
            usort($productList, function ($a, $b) {
                return $a['daysLeft'] <=> $b['daysLeft'];
            });
            if ($limitRecords > 0) {
                $productList = array_slice($productList, 0, $limitRecords);
            }

            $topSellSql = "SELECT p.id, p.ProductName, COALESCE(SUM(tcid.Qty), 0) AS Total_Sell, p.PurchasePrice, 
                            COALESCE(SUM(tcid.Total), 0) AS Sell_Amount, 
                            COALESCE(SUM(tcid.Total) - (SUM(tcid.Qty) * p.PurchasePrice), 0) AS Profit_Amount
                            FROM tbl_cust_products_2025 p
                            INNER JOIN tbl_customer_invoice_details_2025 tcid ON p.id = tcid.ProdId
                            INNER JOIN tbl_customer_invoice_2025 tci ON tci.id = tcid.InvId
                            WHERE p.CreatedBy = $BillSoftFrId 
                            AND p.checkstatus = 1 
                            AND p.delete_flag = 0 
                            AND tci.Roll = 2 
                            AND tci.FrId = '$BillSoftFrId' AND month(tci.InvoiceDate)='" . date('m') . "' AND year(tci.InvoiceDate)='" . date('Y') . "' 
                            GROUP BY p.id, p.ProductName, p.PurchasePrice
                            ORDER BY Total_Sell DESC
                            LIMIT 6";
            $topSellRes = $conn->query($topSellSql);

            $year = (int) date('Y');
            $month = (int) date('m');
            $weeks = getWeeksInMonth($year, $month);
            $weeklyChartData = [];
            $weekNum = 1;
            foreach ($weeks as $week) {
                $weeklyChartData[] = [
                    'label' => 'Week ' . $weekNum,
                    'value' => weeklyIncome($BillSoftFrId, $week['from_date'], $week['to_date']),
                ];
                $weekNum++;
            }
            ?>

            <div class="layout-container">
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">

                        <!-- Hero -->
                        <div class="fr-dash-hero">
                            <div class="row align-items-center">
                                <div class="col-lg-7">
                                    <h4><?php echo htmlspecialchars($shopName ?: 'Outlet Dashboard'); ?></h4>
                                    <div class="hero-meta">
                                        <i class="feather icon-calendar mr-1"></i>
                                        <?php echo date('l, d F Y'); ?>
                                    </div>
                                </div>
                                <div class="col-lg-5 mt-3 mt-lg-0">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="hero-stat">
                                                <div class="label">Total Income</div>
                                                <div class="value"><?php echo fmtMoney($TotalIncome); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="hero-stat">
                                                <div class="label">Today's Income</div>
                                                <div class="value"><?php echo fmtMoney($TodayIncome); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Sales -->
                        <div class="fr-section-title">Today's Sales</div>
                        <div class="row">
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-today-orders.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon sales"><i class="ion ion-md-cart"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Today Sell</div>
                                                <div class="fr-stat-value"><?php echo (int) $todaySellCount; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-today-orders.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon sales"><i class="ion ion-md-trending-up"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Today Sell Amount</div>
                                                <div class="fr-stat-value"><?php echo fmtMoney($todaySellRow['NetAmount'] ?? 0); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-today-orders.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon cash"><i class="ion ion-md-cash"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Today Cash</div>
                                                <div class="fr-stat-value"><?php echo fmtMoney($todayCashRow['NetAmount'] ?? 0); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-today-orders.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon upi"><i class="ion ion-md-phone-portrait"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Today UPI</div>
                                                <div class="fr-stat-value"><?php echo fmtMoney($todayUpiRow['NetAmount'] ?? 0); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="fr-section-title">Products & Inventory</div>
                        <div class="row">
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-customer-products-2025.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon products"><i class="ion ion-md-pricetags"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Selling Products</div>
                                                <div class="fr-stat-value"><?php echo (int) $sellingProdCount; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-raw-products-2025.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon products"><i class="ion ion-md-nutrition"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Raw Products</div>
                                                <div class="fr-stat-value"><?php echo (int) $rawProdCount; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="view-other-products-2025.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon products"><i class="ion ion-md-cube"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Other Products</div>
                                                <div class="fr-stat-value"><?php echo (int) $otherProdCount; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3 mb-4">
                                <div class="card fr-stat-card">
                                    <a href="min-inventory-stock-report.php">
                                        <div class="card-body">
                                            <div class="fr-stat-icon alert"><i class="ion ion-md-warning"></i></div>
                                            <div>
                                                <div class="fr-stat-label">Low Stock (MRP)</div>
                                                <div class="fr-stat-value"><?php echo (int) $lowStockCount; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Stock & Top Selling -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card fr-panel">
                                    <div class="card-header">
                                        <span><i class="feather icon-layers mr-1 text-muted"></i> Stock Level</span>
                                        <a href="stock-level-report.php" class="btn-link-view">View All</a>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($productList)) { ?>
                                            <div class="fr-empty-state">
                                                <i class="feather icon-check-circle d-block mb-2" style="font-size:2rem;opacity:0.4;"></i>
                                                No low stock alerts right now
                                            </div>
                                        <?php } else {
                                            foreach ($productList as $product) {
                                                $bar = stockBarMeta($product['daysLeft']);
                                        ?>
                                            <div class="fr-stock-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="stock-name"><?php echo htmlspecialchars($product['ProductName']); ?></span>
                                                    <span class="stock-days"><?php echo (int) $product['daysLeft']; ?> days left</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-<?php echo $bar['color']; ?>" style="width:<?php echo $bar['width']; ?>%"></div>
                                                </div>
                                            </div>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card fr-panel">
                                    <div class="card-header">
                                        <span><i class="feather icon-award mr-1 text-muted"></i> Top Selling — <?php echo date('F Y'); ?></span>
                                        <a href="top-selling-product-report.php" class="btn-link-view">Show More</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover fr-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Purchase</th>
                                                    <th>Sell</th>
                                                    <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                if ($topSellRes && $topSellRes->num_rows > 0) {
                                                    while ($row = $topSellRes->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo htmlspecialchars($row['ProductName']); ?></td>
                                                        <td><?php echo (int) $row['Total_Sell']; ?></td>
                                                        <td><?php echo fmtMoney($row['PurchasePrice'] * $row['Total_Sell']); ?></td>
                                                        <td><?php echo fmtMoney($row['Sell_Amount']); ?></td>
                                                        <td class="profit-positive"><?php echo fmtMoney($row['Profit_Amount']); ?></td>
                                                    </tr>
                                                <?php }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-4">No sales data for this month yet</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card fr-panel fr-chart-card">
                                    <div class="card-body">
                                        <div class="chart-title">Monthly Income — <?php echo date('Y'); ?></div>
                                        <div id="morrisjs-bars2" style="height: 300px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card fr-panel fr-chart-card">
                                    <div class="card-body">
                                        <div class="chart-title">Weekly Income — <?php echo date('F Y'); ?></div>
                                        <div id="morrisjs-bars3" style="height: 300px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <?php include_once 'footer.php'; ?>
            </div>
        </div>
    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>

    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/eve/eve.js"></script>
    <script src="assets/libs/raphael/raphael.js"></script>
    <script src="assets/libs/morris/morris.js"></script>
    <script src="assets/js/demo.js"></script>
    <script src="assets/js/analytics.js"></script>
    <script>
        $(function() {
            var gridBorder = '#e5e7eb';
            var barColor = '#1a56db';
            var barColorAccent = '#ff4a00';

            new Morris.Bar({
                element: 'morrisjs-bars2',
                data: [
                    <?php
                    $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    $monthlyData = [];
                    for ($m = 1; $m <= 12; $m++) {
                        $monthlyData[] = "{ device: '" . $monthLabels[$m - 1] . "', income: " . round(monthlyIncome($BillSoftFrId, $m, date('Y')), 2) . " }";
                    }
                    echo implode(",\n                    ", $monthlyData);
                    ?>
                ],
                xkey: 'device',
                ykeys: ['income'],
                labels: ['Income (₹)'],
                barRatio: 0.45,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: [barColor],
                gridLineColor: gridBorder,
                resize: true
            });

            new Morris.Bar({
                element: 'morrisjs-bars3',
                data: [
                    <?php
                    $weeklyJs = [];
                    foreach ($weeklyChartData as $w) {
                        $weeklyJs[] = "{ device: '" . $w['label'] . "', income: " . round($w['value'], 2) . " }";
                    }
                    echo implode(",\n                    ", $weeklyJs);
                    ?>
                ],
                xkey: 'device',
                ykeys: ['income'],
                labels: ['Income (₹)'],
                barRatio: 0.45,
                xLabelAngle: 0,
                hideHover: 'auto',
                barColors: [barColorAccent],
                gridLineColor: gridBorder,
                resize: true
            });
        });
    </script>
</body>

</html>
