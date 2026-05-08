<?php session_start();
$sessionid = session_id();
require_once 'config.php';
//require_once 'auth.php';
$PageName = "Home";
$url = "home.php";
$user_id = $_SESSION['User']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}
//echo $sql11;
$Roll = $row['Roll'];
if($Roll == 5){
    $BillSoftFrId = $row['id'];
}
else if($Roll != 5){
    $BillSoftFrId = $row['BillSoftFrId'];
}
else{
    $BillSoftFrId = 0;
}

if($Roll == 5){
    echo "<script>window.location.href='../billsoftmobappfr2025/home.php?frid=$BillSoftFrId';</script>";exit();
}
else if($Roll == 105){
    echo "<script>window.location.href='../billsoft_partnerapp/home.php';</script>";exit();
}
else if($BillSoftFrId!=0){
    echo "<script>window.location.href='../billsoftmobappfr2025/home.php?frid=$BillSoftFrId';</script>";exit();
}
if($_REQUEST['frid']!=''){
    $_SESSION['FranchiseId'] = $_REQUEST['frid'];
}
$FranchiseId = $_SESSION['FranchiseId'];
$sql55 = "SELECT * FROM tbl_users WHERE id='$FranchiseId'";
$row55 = getRecord($sql55);
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>Maha Buddy</title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
  
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">

 <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
   
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    <!-- screen loader -->
   

    <!-- menu main -->
    <?php include_once 'sidebar.php';?>
    <div class="backdrop"></div>


    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       <?php include 'top_header.php';?>

<?php if($_GET['ownfr']==''){
    $ownfr = 1;
}
else{
     $ownfr = $_GET['ownfr'];
}
?>
     

        <!-- page content start -->

        <div class="main-container" style="padding-top: 80px;">

            <div class="container">
               
   <style>
   

     

    h2 {
      text-align: center;
      color: #083068;
      margin-bottom: 24px;
      font-size:13px;
    }
@media (max-width: 480px) {
  .chart-legend {
    font-size: 12px;
    gap: 8px;
  }

  .legend-item {
    min-width: 100px;
    flex-direction: column;
    align-items: flex-start;
  }
}

   .chart-legend {
  display: flex;
  flex-wrap: wrap; /* Allow items to wrap in mobile */
  justify-content: center;
  gap: 12px; /* Reduce gap for better fit */
  margin-top: 10px;
  font-size: 13px;
  padding: 0 10px;
  text-align: center;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 5px;
  min-width: 120px; /* ensures some width on small screens */
  word-break: break-word; /* wraps long names */
}

.legend-color {
  flex-shrink: 0;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
    }

    th {
      background-color: #083068;
      color: white;
      padding: 14px;
      font-size: 15px;
      text-align: left;
    }

    td {
      padding: 12px 16px;
      font-size: 14px;
      color: #333;
    }

   

    tr:hover {
      background-color: #e6effa;
    }

    canvas {
      max-width: 320px;
      margin: 0 auto;
      display: block;
    }
    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0;
      top: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: #fff;
      padding: 20px 30px;
      border-radius: 10px;
      width: 400px;
      max-width: 90%;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
      font-size: 20px;
      font-weight: bold;
      color: #083068;
      margin-bottom: 10px;
    }

    .modal-close {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: #999;
    }

    .modal-close:hover {
      color: #000;
    }
  </style>
  <div class="card mb-2 " style="padding:10px;">
                
              
  <h2>Zone-wise Income Distribution</h2>

  <canvas id="zoneChart" width="360" height="360"></canvas>

  <div class="chart-legend" id="legendZone"></div>
</div>

<!-- Modal -->
<div id="zoneModal" class="modal">
  <div class="modal-content">
    <span class="modal-close" onclick="document.getElementById('zoneModal').style.display='none'">&times;</span>
    <div class="modal-header" id="zoneModalTitle"></div>
    <div id="zoneModalBody"></div>
  </div>
</div>


  <?php 
$dataValues = [];
$labels = [];

if ($Roll == 1) {
    $sql = "SELECT * FROM tbl_zone";
} else {
    if ($zoneids != '') {
        $sql = "SELECT * FROM tbl_zone WHERE id IN($zoneids)"; 
    } else {
        $sql = "SELECT * FROM tbl_zone";      
    }
}

//echo $sql;
$row = getList($sql);
foreach ($row as $result) {
    $zoneid = $result['id'];
    $zoneName = $result['Name'];
    $sql77 = "SELECT GROUP_CONCAT(id) AS FrId FROM tbl_users WHERE ZoneId='$zoneid' AND ZoneId!=11 AND Roll=5";
    $row77 = getRecord($sql77);
    $frids = $row77['FrId'];
// ✅ Skip this sub-zone if no FrId found
    if (empty($frids)) {
        continue;
    }
    $Calendar = $_REQUEST['calendar'];
    
    $sql88 = "SELECT SUM(NetAmount) AS NetAmount,SUM(TotInv) AS TotInv,SUM(cash) AS TotCash,SUM(upi) AS TotUpi FROM (SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,SUM(CASE WHEN PayType='Cash' THEN NetAmount ELSE 0 END) AS cash, 
                SUM(CASE WHEN PayType IN ('Phone Pay','Paytm','UPI','Other UPI') THEN NetAmount ELSE 0 END) AS upi  FROM tbl_customer_invoice WHERE FrId IN ($frids)";
    
    if ($Calendar == 'yesterday') {
        $Yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql88 .= " AND InvoiceDate='$Yesterday'";
    } else if ($Calendar == 'week') {
        $Week = date('Y-m-d', strtotime("-7 days"));
        $sql88 .= " AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
    } else if ($Calendar == 'month') {
        $Week = date('Y-m')."-01";
        $sql88 .= " AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
    } else if ($Calendar == 'Custom') {
        if ($_REQUEST['FromDate']) {
            $FromDate = $_REQUEST['FromDate'];
            $sql88 .= " AND InvoiceDate>='$FromDate'";
        }
        if ($_REQUEST['ToDate']) {
            $ToDate = $_REQUEST['ToDate'];
            $sql88 .= " AND InvoiceDate<='$ToDate'";
        }
    } else {
        $sql88 .= " AND InvoiceDate='".date('Y-m-d')."'"; 
    }
    
    $sql88.= " UNION ALL SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,SUM(CASE WHEN PayType='Cash' THEN NetAmount ELSE 0 END) AS cash, 
                SUM(CASE WHEN PayType IN ('Phone Pay','Paytm','UPI','Other UPI') THEN NetAmount ELSE 0 END) AS upi  FROM tbl_customer_invoice_2025 WHERE FrId IN ($frids)";
    
    if ($Calendar == 'yesterday') {
        $Yesterday = date('Y-m-d', strtotime("-1 days"));
        $sql88 .= " AND InvoiceDate='$Yesterday'";
    } else if ($Calendar == 'week') {
        $Week = date('Y-m-d', strtotime("-7 days"));
        $sql88 .= " AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
    } else if ($Calendar == 'month') {
        $Week = date('Y-m')."-01";
        $sql88 .= " AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
    } else if ($Calendar == 'Custom') {
        if ($_REQUEST['FromDate']) {
            $FromDate = $_REQUEST['FromDate'];
            $sql88 .= " AND InvoiceDate>='$FromDate'";
        }
        if ($_REQUEST['ToDate']) {
            $ToDate = $_REQUEST['ToDate'];
            $sql88 .= " AND InvoiceDate<='$ToDate'";
        }
    } else {
        $sql88 .= " AND InvoiceDate='".date('Y-m-d')."'"; 
    }
    $sql88.=") as a";
//echo $sql88;
    $row88 = getRecord($sql88);
    $NetAmount = $row88['NetAmount'] ?? 0;
    
      
        $sql3 = "SELECT count(*) AS TotFr FROM tbl_users_bill tu WHERE tu.ZoneId = '$zoneid' AND tu.Status=1 AND tu.Roll=5";
    $row3 = getRecord($sql3);
    
    $sql4 = "SELECT count(*) AS TotEmp, SUM(tu.MonthlySalary) AS MonthlySalary FROM tbl_users tu WHERE tu.ZoneId = '$zoneid' AND tu.Status=1";
    $row4 = getRecord($sql4);
    
     $avg = ($row88['TotInv'] > 0) ? ($row88['NetAmount'] / $row88['TotInv']) : 0;

   $dataValues[] = [
        'name' => $zoneName,
        'income' => round((float)($row88['NetAmount'] ?? 0), 2),
        'franchise' => (int)($row3['TotFr'] ?? 0),
        'employees' => (int)($row4['TotEmp'] ?? 0),
        'salary' => round((float)($row4['MonthlySalary'] ?? 0), 2),
        'invoice' => (int)($row88['TotInv'] ?? 0),
        'cash' => round((float)($row88['cash'] ?? 0), 2),
        'upi' => round((float)($row88['upi'] ?? 0), 2),
        'avg' => round($avg, 2)
    ];
   
}
?>

<script>
 const zoneDetails = <?php echo json_encode($dataValues); ?>;
console.log(zoneDetails);

const zoneLabels = zoneDetails.map(z => z.name);
const zoneData = zoneDetails.map(z => z.income);
const zoneColors = ['#42A5F5', '#66BB6A', '#AB47BC', '#FFB74D'];
const total = zoneData.reduce((a, b) => a + b, 0);
const activeOffsets = zoneData.map(() => 0);

// 🟦 Legend showing name + percent
const legendZone = document.getElementById('legendZone');
zoneDetails.forEach((z, i) => {
  const percent = ((z.income / total) * 100).toFixed(1);
  const item = document.createElement('div');
  item.className = 'legend-item';
  item.innerHTML = `
    <span class="legend-color" style="background:${zoneColors[i]}"></span>
    ${z.name} — ${percent}%`;
  legendZone.appendChild(item);
});

// 📊 Doughnut Chart
const ctx = document.getElementById('zoneChart').getContext('2d');
const zoneChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: zoneLabels,
    datasets: [{
      data: zoneData,
      backgroundColor: zoneColors,
      offset: activeOffsets
    }]
  },
  options: {
    cutout: '50%',
    onClick: (e, elements) => {
      if (elements.length > 0) {
        const i = elements[0].index;
        activeOffsets[i] = activeOffsets[i] === 0 ? 20 : 0;
        zoneChart.data.datasets[0].offset = activeOffsets;
        zoneChart.update();

        // 🪟 Modal details
        const z = zoneDetails[i];
        document.getElementById('zoneModalTitle').innerText = z.name + ' Zone';
        document.getElementById('zoneModalBody').innerHTML = `
          <p><strong>Total Income:</strong> ₹ ${z.income.toLocaleString('en-IN')}</p>
          <p><strong>Franchise:</strong> ${z.franchise}</p>
          <p><strong>Employee:</strong> ${z.employees} | <strong>Salary:</strong> ₹ ${z.salary.toLocaleString('en-IN')}</p>
          <p><strong>Total Invoice:</strong> ${z.invoice}</p>
          <p><strong>Cash:</strong> ₹ ${z.cash.toLocaleString('en-IN')}</p>
          <p><strong>UPI:</strong> ₹ ${z.upi.toLocaleString('en-IN')}</p>
          <p><strong>Avg:</strong> ${z.avg}</p>
        `;
        document.getElementById('zoneModal').style.display = 'flex';
      }
    },
    plugins: {
      legend: { display: false },
      tooltip: { enabled: false },
      datalabels: {
        formatter: () => '', // ❌ Don't show labels in chart
        display: false
      }
    }
  },
  plugins: [
    {
      id: 'centerText',
      beforeDraw(chart) {
        const { width, height } = chart;
        const ctx = chart.ctx;
        ctx.restore();
        ctx.font = 'bold 16px Segoe UI';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('Total Income', width / 2, height / 2 - 12);
        ctx.font = 'bold 20px Segoe UI';
        ctx.fillText(`₹ ${total.toLocaleString('en-IN')}`, width / 2, height / 2 + 12);
        ctx.save();
      }
    },
    ChartDataLabels
  ]
});

// 🧼 Close modal on outside click
window.onclick = function(e) {
  const modal = document.getElementById('zoneModal');
  if (e.target === modal) {
    modal.style.display = 'none';
  }
};

</script>
  
   
           
 <?php 
 function countval($val,$frid,$Calendar){
        global $conn; 
   $sql = "SELECT SUM(result) As result FROM (";
    if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId IN ($frid) AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId IN ($frid) AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    
   if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        
         $sql.=" UNION ALL ";
        if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId IN ($frid) AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId IN ($frid) AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    
   if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        $sql.=") as a";
        //echo $sql;
     $res2 = $conn->query($sql);
    $row2 = $res2->fetch_assoc();
    return $row2['result'];
    }
    
 $zoneids = $row['zone'];
 if($Roll == 1){
 $sql = "SELECT * FROM tbl_zone";
 }
 else{
     if($zoneids!=''){
     $sql = "SELECT * FROM tbl_zone WHERE id IN($zoneids)"; 
     }
     else{
    $sql = "SELECT * FROM tbl_zone";      
    //echo "<script>window.location.href='all-home.php';</script>";
     }
 }
        $row = getList($sql);
        foreach($row as $result){
            $zoneid = $result['id'];
  /*$sql77 = "SELECT * FROM tbl_assign_fr_to_zone WHERE zone='$zoneid'";
  $row77 = getRecord($sql77);
  if($row77['frids']!=''){
   $frids = $row77['frids'];
  }
  else{
      $frids = 0;
  }*/
     $sql77 = "SELECT GROUP_CONCAT(id) AS FrId FROM tbl_users WHERE ZoneId='$zoneid' AND Roll=5";
  $row77 = getRecord($sql77);
  $frids = $row77['FrId'];
  // ✅ Skip this sub-zone if no FrId found
    if (empty($frids)) {
        continue;
    }
   $Calendar = $_REQUEST['calendar'];
   $sql88 = "SELECT SUM(NetAmount) AS NetAmount,SUM(TotInv) AS TotInv FROM (SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv FROM tbl_customer_invoice WHERE FrId IN ($frids)";
    if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql88.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql88.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql88.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql88.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql88.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql88.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        
        $sql88.=" UNION ALL SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv FROM tbl_customer_invoice_2025 WHERE FrId IN ($frids)";
        if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql88.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql88.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql88.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql88.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql88.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql88.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        $sql88.=") as a";
        //echo $sql88;
   $row88 = getRecord($sql88);
   $rncnt224+=$row88['TotInv'];
        $NetAmount = $row88['NetAmount'];
        $TotNetAmount+=$NetAmount;
        $TotCashAmount+=countval('cash_payment',$frids,$Calendar);
   $TotUpiAmount+=countval('upi_payment',$frids,$Calendar);
        $sql3 = "SELECT count(*) AS TotFr FROM tbl_users_bill tu WHERE tu.ZoneId = '$zoneid' AND tu.Status=1 AND tu.Roll=5";
    $row3 = getRecord($sql3);
    
    $sql4 = "SELECT count(*) AS TotEmp, SUM(tu.MonthlySalary) AS MonthlySalary FROM tbl_users tu WHERE tu.ZoneId = '$zoneid' AND tu.Status=1";
    $row4 = getRecord($sql4);
    
   
   
   // MRP Product
$sql2 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
         FROM tbl_customer_invoice_details_2025 tc 
         INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
         WHERE tc.FrId IN ($frids) AND tp.ProdType2 = 1 AND tp.ProdType = 0 AND tc.MainProdId NOT IN (177,174,2220,1962,2598,2597,2596,2561,2559,2558,2557) ";
         
         if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql2.=" AND tc.CreatedDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql2.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql2.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND tc.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql2.= " AND tc.CreatedDate<='$ToDate'";
            }
        }
        else{
           $sql2.=" AND tc.CreatedDate='".date('Y-m-d')."'"; 
        }
$row2 = getRecord($sql2);

// Kitchen Product
$sql21 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId IN ($frids) AND tp.ProdType2 = 2 AND tp.ProdType = 0 AND tc.MainProdId NOT IN (177,174,2220,1962,2598,2597,2596,2561,2559,2558,2557) ";
          if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql21.=" AND tc.CreatedDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql21.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql21.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql21.= " AND tc.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql21.= " AND tc.CreatedDate<='$ToDate'";
            }
        }
        else{
           $sql21.=" AND tc.CreatedDate='".date('Y-m-d')."'"; 
        }
$row21 = getRecord($sql21);

// Cross Product
$sql22 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId IN ($frids) AND tc.MainProdId IN (177,174,2220,1962,2598,2597,2596,2561,2559,2558,2557) ";
          if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql22.=" AND tc.CreatedDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql22.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql22.=" AND tc.CreatedDate>='$Week' AND tc.CreatedDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql22.= " AND tc.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql22.= " AND tc.CreatedDate<='$ToDate'";
            }
        }
        else{
           $sql22.=" AND tc.CreatedDate='".date('Y-m-d')."'"; 
        }
$row22 = getRecord($sql22);
        ?>
        
        
        
         <div class="card mb-2">
                   <!-- <a href="product-lists.php?catid=<?php echo $result['id'];?>">-->
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                             <a href="sub-zone.php?zoneid=<?php echo $result['id'];?>">
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"><?php echo $result['Name'];?></h6>
                                          <span class="small text-secondary" style=""><?php echo $result['Address'];?></span>
                                          <p class="small " style="text-transform:capitalize;color:black">
                                               Franchise : <?php echo $row3['TotFr'];?><br>
                                          Employee : <?php echo $row4['TotEmp'];?> | Salary : <?php echo $row4['MonthlySalary'];?><br>
                                          Total Invoice : <?php echo $row88['TotInv'];?> <br>
                                          QSR KITCHEN SALES : <?= $row21['TotSell']; ?> | &#8377;<?= $row21['NetAmount']; ?> <br>
                                          PACK FOOD SALES : <?= $row2['TotSell']; ?> | &#8377;<?= $row2['NetAmount']; ?> <br>
                                          CROSS SALES : <?= $row22['TotSell']; ?> | &#8377;<?= $row22['NetAmount']; ?> <br></p>
                                       <!--<p class="small " style="text-transform:capitalize;color:#009eff;">Last Sync : <?php echo $sync_time;?></p>-->
                                       
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($NetAmount,2);?></strong>
                                          <br><span style="font-size:12px;">Cash : ₹<?php echo number_format(countval('cash_payment',$frids,$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">UPI : ₹<?php echo number_format(countval('upi_payment',$frids,$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">Avg : <?php echo number_format($NetAmount/$row88['TotInv'],2);?></span></p>
                                    </div>
                                </div>
                            </li>
                            </a>
                        </ul>
                    </div>
               <!-- </a>-->
                </div>
                
               
                <?php } ?>
               
         
               
<div class="card mb-2">
                
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Cash</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotCashAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total UPI</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotUpiAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"> Total Income</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotNetAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                             <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Avg</h6>
                                         
                                      
                                    </div> 
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong><?php echo number_format(($TotNetAmount)/$rncnt224,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    
                 
                    
                </div>
                
                <div align="center">
                                                                   
                 <a href="all-home.php" class="btn btn-sm btn-default rounded">View All</a>
            
                                
                                                                </div>
                
        
     
  
        </div>
    </main>


     <?php include_once 'footer.php';?>

    <!-- color settings style switcher -->
   <?php include 'inc-fr-lists.php';include 'inc-calendar-lists.php';?>



    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
 <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
  
    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
 <script type="text/javascript" src="js/toastr.min.js"></script>
   
</body>

</html>
