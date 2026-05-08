<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
$user_id = $_SESSION['Admin']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}

//echo $sql11;
$Roll = $row['Roll'];
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
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
  <!-- swiper CSS -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
</head>
<body>

  
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
    
   
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php //include_once 'top_header.php'; ?>


<div class="layout-content">
<?php
// ------------ BEGIN REPLACED DASHBOARD ------------
// (Paste this block in place of the main content area in your file)

// Assumptions: config.php defines $conn (mysqli connection).
// If you use custom helpers (getRecord etc.), swap the mysqli calls accordingly.

// helper: safe single value fetch
function fetch_single_value($conn, $sql, $default = 0) {
    $res = mysqli_query($conn, $sql);
    if (!$res) return $default;
    $row = mysqli_fetch_array($res);
    return $row ? array_values($row)[0] : $default;
}

// helper: fetch rows as associative array
function fetch_rows($conn, $sql) {
    $arr = [];
    $res = mysqli_query($conn, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $arr[] = $r;
    }
    return $arr;
}

// Today date
$today = date('Y-m-d');

// --- Summary counts (adjust table/column names to your DB) ---
$total_orders = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_customer_invoice_2025 WHERE 1");
$today_orders = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_customer_invoice_2025 WHERE DATE(InvoiceDate) = '$today'");

$total_dealers = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_users WHERE Roll=5 AND Status=1");
$total_distributors = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_users WHERE  Roll=166 AND Status=1");
$total_vendors = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_users WHERE Roll=3 AND Status=1");
$total_zones = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_zone WHERE 1");
$total_subzones = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_sub_zone WHERE 1");

// --- Today request / statuses (adjust column names) ---
$today_requests = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_dealer_req_orders WHERE DATE(CreatedDate) = '$today'");
$total_pending = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_dealer_req_order_items WHERE ReceiveStatus = 0");
$total_delivered = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_dealer_req_order_items WHERE ReceiveStatus = 1");
$total_partial_delivered = fetch_single_value($conn, "SELECT COUNT(*) FROM tbl_dealer_req_order_items WHERE ReceiveStatus = 2");

// --- Sales outlet-wise (last 30 days) for chart (adjust table & column names) ---
$sales_rows = fetch_rows($conn,
    "SELECT 
        u.ShopName AS outlet_name, 
        SUM(c.NetAmount) AS total_sales
     FROM tbl_customer_invoice_2025 c
     INNER JOIN tbl_users u ON u.id = c.FrId
     WHERE c.InvoiceDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
     GROUP BY u.ShopName
     ORDER BY total_sales DESC
     LIMIT 30"
);


// Prepare arrays for chart
$outlet_labels = [];
$outlet_sales = [];
foreach ($sales_rows as $r) {
    $outlet_labels[] = $r['outlet_name'];
    $outlet_sales[] = (float)$r['total_sales'];
}

// --- Top 10 outlets (by sales all-time) ---
$top_outlets = fetch_rows($conn,
    "SELECT 
        u.ShopName AS outlet_name,
        SUM(c.NetAmount) AS total_sales,
        COUNT(*) AS orders_count
     FROM tbl_customer_invoice_2025 c
     INNER JOIN tbl_users u ON u.id = c.FrId
     GROUP BY u.ShopName
     ORDER BY total_sales DESC
     LIMIT 10"
);


// --- Bottom 10 outlets (by sales) ---
$bottom_outlets = fetch_rows($conn,
    "SELECT 
        u.ShopName AS outlet_name,
        IFNULL(SUM(c.NetAmount), 0) AS total_sales,
        COUNT(*) AS orders_count
     FROM tbl_customer_invoice_2025 c
     INNER JOIN tbl_users u ON u.id = c.FrId
     GROUP BY u.ShopName
     ORDER BY total_sales ASC
     LIMIT 10"
);


// --- Top & Bottom 10 selling products (adjust product table & order_items table names) ---
$top_products = fetch_rows($conn,
    "SELECT 
        p.ProductName, 
        SUM(oi.Qty) AS qty_sold, 
        SUM(oi.Qty * oi.Price) AS total_value
     FROM tbl_customer_invoice_details_2025 oi
     INNER JOIN tbl_cust_products2 p ON p.id = oi.MainProdId
     GROUP BY oi.MainProdId, p.ProductName
     ORDER BY qty_sold DESC
     LIMIT 10"
);
$bottom_products = fetch_rows($conn,
    "SELECT 
        p.ProductName, 
        SUM(oi.Qty) AS qty_sold, 
        SUM(oi.Qty * oi.Price) AS total_value
     FROM tbl_customer_invoice_details_2025 oi
     INNER JOIN tbl_cust_products2 p ON p.id = oi.MainProdId
     GROUP BY oi.MainProdId, p.ProductName
     ORDER BY qty_sold ASC
     LIMIT 10"
);

?>
<!-- ==================== DASHBOARD STYLES ==================== -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #f1f5ff, #f9fafc);
  color: #333;
  overflow-x: hidden;
}

/* ======= HEADER ======= */
.dashboard-header {
  background: linear-gradient(135deg, #3b82f6, #9333ea);
  color: #fff;
  border-radius: 20px;
  padding: 30px 40px;
  margin-bottom: 35px;
  box-shadow: 0 10px 30px rgba(59,130,246,0.25);
  position: relative;
  overflow: hidden;
}
.dashboard-header::after {
  content: '';
  position: absolute;
  width: 240px;
  height: 240px;
  background: rgba(255,255,255,0.1);
  top: -70px;
  right: -70px;
  border-radius: 50%;
}
.dashboard-header h2 {
  font-weight: 700;
  font-size: 30px;
  margin: 0;
}
.dashboard-header small {
  font-size: 15px;
  opacity: 0.9;
}

/* ======= STAT CARDS ======= */
.stat-card {
  border-radius: 18px;
  color: #fff;
  padding: 22px 20px;
  text-align: center;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 14px 28px rgba(0,0,0,0.12);
}
.stat-card .icon {
  font-size: 32px;
  opacity: 0.9;
  margin-bottom: 10px;
}
.stat-card h3 {
  margin: 0;
  font-size: 25px;
  font-weight: 700;
}
.stat-card p {
  margin: 0;
  font-size: 14px;
  opacity: 0.95;
}

.bg-blue { background: linear-gradient(135deg, #4f46e5, #3b82f6); }
.bg-green { background: linear-gradient(135deg, #059669, #10b981); }
.bg-orange { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
.bg-red { background: linear-gradient(135deg, #dc2626, #f87171); }
.bg-gray { background: linear-gradient(135deg, #6b7280, #9ca3af); }

/* ======= CARDS ======= */
.card-glass {
  background: rgba(255,255,255,0.65);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  border: 1px solid rgba(255,255,255,0.4);
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  padding: 20px;
  transition: all 0.3s ease;
}
.card-glass:hover { transform: translateY(-4px); }

.table th {
  background-color: rgba(243,244,246,0.8);
  font-weight: 600;
  font-size: 13px;
}
.table td {
  font-size: 13px;
}
.fade-in {
  animation: fadeInUp 0.7s ease both;
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.today-card {
  background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(245,247,250,0.7));
  border-radius: 18px;
  border: 1px solid rgba(255,255,255,0.4);
  backdrop-filter: blur(12px);
  box-shadow: 0 6px 20px rgba(0,0,0,0.06);
  transition: all 0.3s ease;
  padding: 25px 20px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.today-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.1);
}

.today-card::after {
  content: "";
  position: absolute;
  top: -40px;
  right: -40px;
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: radial-gradient(circle at center, rgba(255,255,255,0.5), transparent 70%);
}

.today-card h5 {
  font-weight: 600;
  font-size: 16px;
  color: #111827;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

.today-card .icon-box {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  margin: 0 auto 10px;
  color: #fff;
}

.today-card h3 {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 4px;
}

.today-card small {
  color: #6b7280;
}

/* individual gradients */
.bg-request { background: linear-gradient(135deg, #f97316, #fb923c); }
.bg-pending { background: linear-gradient(135deg, #facc15, #fbbf24); color: #111; }
.bg-delivered { background: linear-gradient(135deg, #10b981, #34d399); }

.navbar.bg-dark {
    background-color: #fff !important;
    color: rgba(255, 255, 255, 0.7648564706);
}
</style>
<br>
<!-- ==================== DASHBOARD CONTENT ==================== -->
<div class="container-fluid flex-grow-1 container-p-y fade-in">
  <!-- HEADER -->
  <!--<div class="dashboard-header d-flex justify-content-between align-items-center">
    <div>
      <h2>Happy Shop Dashboard</h2>
      <small>Welcome back, <?php echo htmlspecialchars($row['Fname']); ?> 👋</small>
    </div>
    <div>
      <span class="badge bg-light text-dark p-3 fs-6 rounded-pill shadow-sm">
        <?php echo date('l, d M Y'); ?>
      </span>
    </div>
  </div>-->

  <!-- SUMMARY CARDS -->
 <!-- <div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4 col-lg-2">
      <a href="today-orders.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>" target="_new"><div class="stat-card bg-blue">
        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        <h3><?php echo number_format($today_orders); ?></h3>
        <p>Today's Orders</p>
      </div></a>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-2">
      <a href="view-franchises.php" target="_new"><div class="stat-card bg-green">
        <div class="icon"><i class="fas fa-user-tie"></i></div>
        <h3><?php echo number_format($total_dealers); ?></h3>
        <p>Total Dealers</p>
      </div></a>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-2">
      <a href="view-distributers.php" target="_new"><div class="stat-card bg-orange">
        <div class="icon"><i class="fas fa-warehouse"></i></div>
        <h3><?php echo number_format($total_distributors); ?></h3>
        <p>Total Distributors</p>
      </div></a>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-2">
      <a href="view-vendors.php" target="_new"><div class="stat-card bg-red">
        <div class="icon"><i class="fas fa-truck-loading"></i></div>
        <h3><?php echo number_format($total_vendors); ?></h3>
        <p>Total Vendors</p>
      </div></a>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-2">
      <a href="zones.php" target="_new"><div class="stat-card bg-gray">
        <div class="icon"><i class="fas fa-globe"></i></div>
        <h3><?php echo number_format($total_zones); ?></h3>
        <p>Total Zones</p>
      </div></a>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-2">
      <a href="sub-zones.php" target="_new"><div class="stat-card bg-dark">
        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
        <h3><?php echo number_format($total_subzones); ?></h3>
        <p>Total Subzones</p>
      </div></a>
    </div>
  </div>-->

  <!-- TODAY REQUESTS / PENDING / DELIVERED -->
 <div class="row g-3 mb-4">

  <!-- Today Requests -->
  <div class="col-md-3">
    <a href="dealer-product-request-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>&Search=Search" target="_new">
      <div class="today-card">
        <div class="icon-box" style="background:#3b82f6;">
          <i class="fas fa-clipboard-list"></i>
        </div>
        <h5>Today Requests</h5>
        <h3 style="color:#3b82f6;"><?php echo number_format($today_requests); ?></h3>
        <small>Requests created today</small>
      </div>
    </a>
  </div>

  <!-- Pending Orders -->
  <div class="col-md-3">
    <a href="pending-dealer-req-orders.php?Search=Search" target="_new">
      <div class="today-card">
        <div class="icon-box" style="background:#f59e0b;">
          <i class="fas fa-clock"></i>
        </div>
        <h5>Pending Orders</h5>
        <h3 style="color:#f59e0b;"><?php echo number_format($total_pending); ?></h3>
        <small>Awaiting delivery</small>
      </div>
    </a>
  </div>

  <!-- Partial Delivered Orders -->
  <div class="col-md-3">
    <a href="partial-dealer-req-orders.php?Search=Search" target="_new">
      <div class="today-card">
        <div class="icon-box" style="background:#8b5cf6;">
          <i class="fas fa-truck-loading"></i>
        </div>
        <h5>Partial Delivered Orders</h5>
        <h3 style="color:#8b5cf6;"><?php echo number_format($total_partial_delivered); ?></h3>
        <small>Orders partially completed</small>
      </div>
    </a>
  </div>

  <!-- Delivered Orders -->
  <div class="col-md-3">
    <a href="delivered-dealer-req-orders.php?Search=Search" target="_new">
      <div class="today-card">
        <div class="icon-box" style="background:#10b981;">
          <i class="fas fa-check-circle"></i>
        </div>
        <h5>Delivered Orders</h5>
        <h3 style="color:#10b981;"><?php echo number_format($total_delivered); ?></h3>
        <small>Orders completed</small>
      </div>
    </a>
  </div>

</div>


  <!-- TABLES SECTION -->
 <!-- <div class="row g-4">
   
    <div class="col-lg-6">
      <div class="card-glass">
        <h5 class="text-primary mb-3"><i class="fas fa-crown me-2"></i>Top 10 Selling Products</h5>
        <div class="table-responsive">
          <table class="table table-sm table-striped align-middle">
            <thead><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Total Value (₹)</th></tr></thead>
            <tbody>
              <?php $i=1; foreach($top_products as $p){ ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($p['ProductName']); ?></td>
                <td><?php echo number_format($p['qty_sold']); ?></td>
                <td><?php echo number_format($p['total_value'],2); ?></td>
              </tr>
              <?php } if (count($top_products)==0){ ?>
              <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    
    <div class="col-lg-6">
      <div class="card-glass">
        <h5 class="text-danger mb-3"><i class="fas fa-arrow-down me-2"></i>Bottom 10 Selling Products</h5>
        <div class="table-responsive">
          <table class="table table-sm table-striped align-middle">
            <thead><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Total Value (₹)</th></tr></thead>
            <tbody>
              <?php $i=1; foreach($bottom_products as $p){ ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($p['ProductName']); ?></td>
                <td><?php echo number_format($p['qty_sold']); ?></td>
                <td><?php echo number_format($p['total_value'],2); ?></td>
              </tr>
              <?php } if (count($bottom_products)==0){ ?>
              <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    </div><br>-->
 <!--<div class="row g-4">
   
    <div class="col-lg-6">
      <div class="card-glass mb-4">
        <h5 class="text-success mb-2"><i class="fas fa-store me-2"></i>Top 10 Outlets</h5>
        <div class="table-responsive" style="max-height:260px; overflow:auto;">
          <table class="table table-sm table-hover">
            <thead><tr><th>#</th><th>Outlet</th><th>Sales (₹)</th></tr></thead>
            <tbody>
              <?php $i=1; foreach($top_outlets as $o){ ?>
              <tr><td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($o['outlet_name']); ?></td>
              <td><?php echo number_format($o['total_sales'],2); ?></td></tr>
              <?php } if (count($top_outlets)==0){ ?>
              <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card-glass">
        <h5 class="text-danger mb-2"><i class="fas fa-store-slash me-2"></i>Bottom 10 Outlets</h5>
        <div class="table-responsive" style="max-height:260px; overflow:auto;">
          <table class="table table-sm table-hover">
            <thead><tr><th>#</th><th>Outlet</th><th>Sales (₹)</th></tr></thead>
            <tbody>
              <?php $i=1; foreach($bottom_outlets as $o){ ?>
              <tr><td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($o['outlet_name']); ?></td>
              <td><?php echo number_format($o['total_sales'],2); ?></td></tr>
              <?php } if (count($bottom_outlets)==0){ ?>
              <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>-->
</div>

<!-- ICONS -->
<script src="https://kit.fontawesome.com/a2e0bf0a4c.js" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const outletLabels = <?php echo json_encode($outlet_labels); ?>;
  const outletData = <?php echo json_encode($outlet_sales); ?>;
  const miniCtx = document.getElementById('miniSalesChart').getContext('2d');

  new Chart(miniCtx, {
    type: 'line',
    data: { labels: outletLabels.slice(0,10), datasets: [{
      data: outletData.slice(0,10),
      fill: true, 
      tension: 0.4,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.1)',
      pointRadius: 0
    }]},
    options: { plugins:{legend:{display:false}}, maintainAspectRatio:false, scales:{x:{display:false}, y:{display:false}}}
  });

  if (typeof $ !== 'undefined' && $.fn.dataTable) {
    $('#topProductsTable').DataTable({ responsive:true, pageLength: 5, lengthChange:false });
    $('#topOutlets').DataTable({ responsive:true, paging:false, searching:false, info:false });
    $('#bottomOutlets').DataTable({ responsive:true, paging:false, searching:false, info:false });
  }
});
</script>


</div>



<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


</body>
</html>
