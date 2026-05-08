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
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
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
    //echo "<script>window.location.href='zone-home.php?ownfr=1';</script>";
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
                                          Total Invoice : <?php echo $row88['TotInv'];?> </p>
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
                
        
       
        <style>
   

    .chart-container {
      width: 100%;
      height: 100%;
      background: #fff;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #083068;
      margin-bottom: 20px;
    }
  </style> 
  <div class="card mb-2 mt-2">
            <div class="chart-container" align="center">
    <strong>Zone Wise Breakdown</strong>
    <canvas id="cashUpiChart"></canvas>
  </div>

  <script>
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

$row = getList($sql);
foreach ($row as $result) {
    $zoneid = $result['id'];
    $sql77 = "SELECT GROUP_CONCAT(id) AS FrId FROM tbl_users WHERE ZoneId='$zoneid' AND Roll=5";
    $row77 = getRecord($sql77);
    $frids = $row77['FrId'];

    $Calendar = $_REQUEST['calendar'];
    $sql88 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId IN ($frids)";
    
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

    $row88 = getRecord($sql88);
    $NetAmount = $row88['NetAmount'] ?? 0;

    $dataValues[] = (float)$NetAmount;
    $labels[] = $result['Name'];
}
?>
    const dataValues = <?php echo json_encode($dataValues); ?>;
    const labels = <?php echo json_encode($labels); ?>;
    const total = dataValues.reduce((a, b) => a + b, 0);

    const ctx = document.getElementById('cashUpiChart').getContext('2d');

    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          data: dataValues,
          backgroundColor: [
  '#4FC3F7', // Sky Blue
  '#81C784', // Mint Green
  '#BA68C8', // Soft Purple
  '#FFB74D', // Amber Orange
  '#4DB6AC', // Teal
  '#F48FB1'  // Soft Pink
],
          borderWidth: 1
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: false
          },
          datalabels: {
            color: '#fff',
            font: {
              size: 14,
              weight: 'bold'
            },
            formatter: (value, context) => {
              const percent = ((value / total) * 100).toFixed(1);
              return `${context.chart.data.labels[context.dataIndex]}\n${percent}%`;
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  </script>
   </div>
   
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
