<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
$user_id = $_SESSION['Admin']['id'];
$uid = $_REQUEST['uid']; 
if($_REQUEST['uid'] == ''){
$sql11 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}   
else{
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['Admin'] = $row;
}
//echo $sql11;
$Roll = $row['Roll'];

if($Roll == 63){
    echo "<script>window.location.href='emp-dashboard.php';</script>";
    exit();
}
if($Roll == 64){
    echo "<script>window.location.href='preparing-order.php';</script>";
    exit();
}
else{
    
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
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
  <!-- swiper CSS -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
</head>
<body>
<style type="text/css">
    .mr_5{
        margin-right: 3rem !important;
    }
    
 </style>

 <style>
   

     

    h2 {
      text-align: center;
      color: #083068;
      margin-bottom: 24px;
      font-size:13px;
    }

    .chart-legend {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-top: 10px;
      font-size: 14px;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .legend-color {
      width: 12px;
      height: 12px;
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
    
    .text-secondary {
    color: #000000 !important;
}
  </style>
  
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
    
   
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php //include_once 'top_header.php'; ?>


<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
</div>
<?php  $_SESSION['Calendar']=$_POST['calendar'];?>
 <h3>Welcome To KwickBill</h3>       
<div class="row">
     <div class="col-lg-12">
      <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
<div class="form-group col-lg-2">
                                            <label class="form-label">Select Report</label>
                                            <select class="form-control" id="ReportType" name="calendar" onchange="showdate(this.value)">
                                               
                                                <option value="Today" <?php if ($_SESSION['Calendar'] == 'Today') { ?> selected <?php } ?>>Today</option>
                                                <option <?php if ($_SESSION['Calendar'] == 'yesterday') { ?> selected <?php } ?> value="yesterday">Yesterday</option>
                                                <option <?php if ($_SESSION['Calendar'] == 'week') { ?> selected <?php } ?> value="week">This Week</option>
                                                <option <?php if ($_SESSION['Calendar'] == 'month') { ?> selected <?php } ?> value="month">This Month</option>
                                                 <option <?php if ($_SESSION['Calendar'] == 'custom') { ?> selected <?php } ?> value="custom">Custom</option>
                                               
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-2 customfmdt" <?php if ($_SESSION['Calendar'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2 customtodt" <?php if ($_SESSION['Calendar'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>

<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top: 20px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
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
           </div>
                   <?php 
                   // ✅ Common reusable date filter function
function buildDateCondition($column, $calendar, $from, $to) {
    $condition = "";
    switch ($calendar) {
        case 'yesterday':
            $yesterday = date('Y-m-d', strtotime('-1 days'));
            $condition = " AND $column = '$yesterday'";
            break;
        case 'week':
            $weekStart = date('Y-m-d', strtotime('-7 days'));
            $today = date('Y-m-d');
            $condition = " AND $column >= '$weekStart' AND $column <= '$today'";
            break;
        case 'month':
            $monthStart = date('Y-m') . "-01";
            $today = date('Y-m-d');
            $condition = " AND $column >= '$monthStart' AND $column <= '$today'";
            break;
        case 'Custom':
            if (!empty($from)) $condition .= " AND $column >= '$from'";
            if (!empty($to)) $condition .= " AND $column <= '$to'";
            break;
        default:
            $today = date('Y-m-d');
            $condition = " AND $column = '$today'";
    }
    return $condition;
}
                  
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
    ?>
                  <?php 
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
  
  // ✅ Skip this sub-zone if no FrId found
    if (empty($frids)) {
        continue;
    }
    
   $Calendar = $_SESSION['Calendar'];
   $sql88 = "SELECT SUM(NetAmount) AS NetAmount,SUM(TotInv) AS TotInv FROM (SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv  FROM tbl_customer_invoice WHERE FrId IN ($frids)";
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
        
        $sql88.=" UNION ALL SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv  FROM tbl_customer_invoice_2025 WHERE FrId IN ($frids)";
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
    
    
   $FromDate  = $_REQUEST['FromDate'] ?? date('Y-m-d');
$ToDate    = $_REQUEST['ToDate'] ?? date('Y-m-d');



/*// ✅ Expense
$sql88 = "SELECT SUM(Amount) AS TotalExp FROM tbl_expense_request WHERE FrId IN ($frids) AND AdminStatus != 2" .
         buildDateCondition("ExpenseDate", $Calendar, $FromDate, $ToDate);
$emp = getRecord($sql88);

// ✅ Vendor Expenses
$sql881 = "SELECT SUM(Amount) AS TotalVedExp FROM tbl_vendor_expenses WHERE Locations IN ($frids) AND AdminStatus != 2" .
          buildDateCondition("ExpenseDate", $Calendar, $FromDate, $ToDate);
$vendor = getRecord($sql881);

// ✅ NSO Vendor Expenses
$sql882 = "SELECT SUM(Amount) AS TotalNsoVedExp FROM tbl_nso_vendor_expenses WHERE Locations IN ($frids) AND AdminStatus != 2" .
          buildDateCondition("ExpenseDate", $Calendar, $FromDate, $ToDate);
$nso = getRecord($sql882);

// ✅ Sales (from both old and 2025)
$invoiceDateCond = buildDateCondition("InvoiceDate", $Calendar, $FromDate, $ToDate);
$sql5 = "SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (
    SELECT SUM(NetAmount) AS NetAmount 
    FROM tbl_customer_invoice 
    WHERE FrId IN ($frids) AND Status = 1 AND delete_flag = 0 $invoiceDateCond
    UNION ALL
    SELECT SUM(NetAmount) AS NetAmount 
    FROM tbl_customer_invoice_2025 
    WHERE FrId IN ($frids) AND Status = 1 AND delete_flag = 0 $invoiceDateCond
) AS a";
$row5 = getRecord($sql5);

// ✅ Petty Cash (wallet table)
$walletDateCond = buildDateCondition("w.CreatedDate", $Calendar, $FromDate, $ToDate);
$sql2 = "SELECT COALESCE(SUM(TotAmt), 0) AS TotPetty FROM (
    SELECT SUM(w.Amount) AS TotAmt
    FROM wallet w
    LEFT JOIN tbl_users tu ON tu.id = w.UserId
    LEFT JOIN tbl_users_bill tub ON tub.id = tu.UnderFrId
    WHERE tub.id IN ($frids)
      AND w.Status = 'Cr'
      $walletDateCond
      AND (w.Narration LIKE '%Pretty Cash%' OR w.Narration LIKE '%Petty Cash%')
    GROUP BY w.UserId
) AS a";
$row2 = getRecord($sql2);

// ✅ Salary Calculation
$sql31 = "SELECT COALESCE(SUM(MonthlySalary), 0) AS MonthlySalary FROM (
    SELECT tu.id, tu.PerDaySalary, tu.PerDaySalary * (DATEDIFF('$ToDate', '$FromDate') + 1) AS MonthlySalary
    FROM tbl_users tu WHERE tu.UnderFrId IN ($frids) AND tu.SalaryType = 1 AND tu.Status = 1
    UNION ALL
    SELECT tu.id, tu.PerDaySalary, tu.PerDaySalary AS MonthlySalary
    FROM tbl_users tu WHERE tu.UnderFrId IN ($frids) AND tu.SalaryType = 2 AND tu.Status = 1
) AS a";
$row31 = getRecord($sql31);

// ✅ Rent
$sql41 = "SELECT COALESCE(SUM(MonthlyRent), 0) AS MonthlyRent FROM tbl_users WHERE id IN ($frids)";
$row41 = getRecord($sql41);

// ✅ Final Calculations
$netAmount   = $row5['NetAmount'];
$prodcost    = ($netAmount * 40) / 100;
$invsharecost = ($netAmount * 12) / 100;
$gstcost     = ($netAmount * 5) / 100;
$hocost      = ($netAmount * 5) / 100;

$balance = $netAmount
           - $row31['MonthlySalary']
           - $row41['MonthlyRent']
           - $row2['TotPetty']
           - $invsharecost
           - $gstcost
           - $hocost;*/
   
        ?>
        <div class="col-lg-4">
                <div class="card mb-2">
                   <!-- <a href="product-lists.php?catid=<?php echo $result['id'];?>">-->
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            <?php if($_GET['value']=='zone'){?>
                            <a href="fr-expense-sale-report.php?Search=Search&FromDate=<?php echo date('Y-m-');?>01&ToDate=<?php echo date('Y-m-d');?>&ZoneId=<?php echo $result['id'];?>">
                            <?php } else {?>
                           <a href="sub-zone2.php?zoneid=<?php echo $result['id'];?>&name=<?php echo $result['Name'];?>"> 
                            <?php } ?>
                             
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"><?php echo $result['Name'];?></h6>
                                          <span class="small text-secondary" style=""><?php echo $result['Address'];?></span>
                                          <p class="small " style="text-transform:capitalize;color:black">
                                               Franchise : <?php echo $row3['TotFr'];?><br>
                                          Employee : <?php echo $row4['TotEmp'];?> | Salary : <?php echo $row4['MonthlySalary'];?><br>
                                          Total Invoice : <?php echo $row88['TotInv'];?> <br>
                                         
                                          <!--Employee Expenses : <?= number_format($emp['TotalExp'] ?? 0, 2) ?><br>
                                          Vendor Expenses : <?= number_format($vendor['TotalVedExp'] ?? 0, 2) ?><br>
                                          NSO Vendor Expenses : <?= number_format($nso['TotalNsoVedExp'] ?? 0, 2) ?><br>
                                          Salary : <?= number_format($row3['MonthlySalary'], 2) ?><br>
                                          Rent & Electricity : <?= number_format($row4['MonthlyRent'], 2) ?><br>
                                          Misc Expenses : <?= number_format($row2['TotPetty'], 2) ?><br>
                                          Investor Share Cost (12%) : <?= number_format($invsharecost, 2) ?><br>
                                          GST (5%): <?= number_format($gstcost, 2) ?><br>
                                          HO Cost (5%): <?= number_format($hocost, 2) ?> <br>
                                          Balance : <?= number_format($balance, 2) ?>-->
                                          </p>
                                     
                                       
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
                </div>
                                <?php } ?>
                                 <div class="col-lg-4">
                                <div class="card mb-2">
                
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            
                            
                            <li class="list-group-item" style="border-radius: 0px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Cash</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotCashAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 0px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total UPI</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotUpiAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 0px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"> Total Income</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotNetAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                             <li class="list-group-item" style="border-radius: 0px;">
                                
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
                    
                 
                    
                </div></div>
                                
                                 <!--<div class="col-lg-2">
                                     <a href="#">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Total Income</h6>
                                                        <div class="text-large">&#8377;<?php echo number_format($TotNetAmount,2);?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   </a>
                                </div>-->
                                
                                 <div class="col-lg-12">
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

$row = getList($sql);
foreach ($row as $result) {
    $zoneid = $result['id'];
    $zoneName = $result['Name'];
   $sql77 = "SELECT GROUP_CONCAT(id) AS FrId FROM tbl_users WHERE ZoneId='$zoneid' AND Roll=5";
    $row77 = getRecord($sql77);
    $frids = $row77['FrId'];

// ✅ Skip this sub-zone if no FrId found
    if (empty($frids)) {
        continue;
    }
    
    
    $Calendar = $_SESSION['Calendar'];
    
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



</div>




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
<script type="text/javascript">
function showdate(val){
    if(val == 'custom'){
        $('.customfmdt').show();
        $('.customtodt').show();
    }
    else{
        $('.customfmdt').hide();
        $('.customtodt').hide();
        $('#FromDate').val('');
        $('ToDate').val('');
    }
}
function printReceipt(invdata){
    console.log(invdata);
     //Android.printReceipt(''+invdata+'');
}
    $(document).ready(function() {
    $('#example').DataTable( {
      "lengthMenu": [[5, 10, 15, 20, 25, 50, -1], [5, 10, 15, 20, 25, 50, "All"]]
    } );

} );
</script>
</body>
</html>
