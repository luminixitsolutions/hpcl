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
/*function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;


$sql = "SELECT * FROM tbl_cust_products WHERE code=''";
$row = getList($sql);
foreach($row as $result){
    $Code = RandomStringGenerator($n); 
    $Code2 = $Code."".$result['id'];
    $sql2 = "UPDATE tbl_cust_products SET code='$Code2' WHERE id='".$result['id']."'";
    $conn->query($sql2);
}*/
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
</head>
<body>
<style type="text/css">
    .mr_5{
        margin-right: 3rem !important;
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

<style>
    .text-secondary {
    color: #000 !important;
}
</style>
<h3><?php echo $_GET['zonename'];?> - <?php echo $_GET['subzonename'];?></h3>  
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
//$_SESSION['Calendar']=$_POST['calendar']; 
function countval($val,$frid,$Calendar){
        global $conn; 
   $sql = "SELECT SUM(result) As result FROM (";
    if($val == 'cash_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    if($val == 'zomato'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Swiggy','Zomato')";
    }
    if($val == 'credit'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Borrowing')";
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
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    if($val == 'zomato'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Swiggy','Zomato')";
    }
    if($val == 'credit'){
        $sql.= "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Borrowing')";
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
    
  $zoneid = $_REQUEST['zoneid'];
  $subzoneid = $_REQUEST['subzoneid'];
  $sql77 = "SELECT GROUP_CONCAT(id) AS frids FROM tbl_users WHERE SubZoneId='$subzoneid' AND ZoneId='$zoneid'";
  $row77 = getRecord($sql77);
   $frids = $row77['frids'];
    
$Calendar = $_SESSION['Calendar'];
 $sql = "SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,tc.FrId,tu.ShopName,tu.Address FROM tbl_customer_invoice tc INNER JOIN tbl_users_bill tu ON tu.id=tc.FrId WHERE tu.Roll='5'  AND tu.id IN($frids)";
if($Roll != 1){
    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
    $sql.=" AND tu.id IN ($CocoFranchiseAccess)";
    }
}

if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND tc.InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tc.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tc.InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND tc.InvoiceDate='".date('Y-m-d')."'"; 
        }
        
$sql.=" GROUP BY tc.FrId ";

$sql.= " UNION ALL SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,tc.FrId,tu.ShopName,tu.Address FROM tbl_customer_invoice_2025 tc INNER JOIN tbl_users_bill tu ON tu.id=tc.FrId WHERE tu.Roll='5'  AND tu.id IN($frids)";
if($Roll != 1){
    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
    $sql.=" AND tu.id IN ($CocoFranchiseAccess)";
    }
}

if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND tc.InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tc.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tc.InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND tc.InvoiceDate='".date('Y-m-d')."'"; 
        }
        
$sql.=" GROUP BY tc.FrId ";

$sql.=" ORDER BY NetAmount DESC";
//echo $sql;
$row = getList($sql);
 foreach($row as $result){
     
    $NetAmount2 = $result['NetAmount'];
   if($NetAmount2 > 0){
       $NetAmount = $result['NetAmount'];
   }
   else{
       $NetAmount = 0;
   }
   
   $rncnt224+=$result['TotInv'];
    $TotNetAmount+=$NetAmount;
   $TotCashAmount+=countval('cash_payment',$result['FrId'],$Calendar);
   $TotUpiAmount+=countval('upi_payment',$result['FrId'],$Calendar);
    if($NetAmount > 0){
        
        $sql = "SELECT MAX(LastTime) AS LastTime FROM (SELECT Max(modified_time) AS LastTime FROM `tbl_customer_invoice` WHERE FrId='".$result['FrId']."' UNION ALL SELECT Max(modified_time) AS LastTime FROM `tbl_customer_invoice_2025` WHERE FrId='".$result['FrId']."') as a";
$row = getRecord($sql);
$LastTime = $row['LastTime'];
$date = $LastTime;
$currtime = gmdate("Y-m-d H:i:s");
// Create two DateTime objects
$dateTime1 = new DateTime($currtime); // Current date and time
$dateTime2 = new DateTime($date); // 1 hour 45 minutes ago

// Calculate the difference between the two dates
$interval = $dateTime1->diff($dateTime2);

// Format the difference
$hours = $interval->h;
$minutes = $interval->i;

if ($hours > 0 && $minutes > 0) {
    $timeDifference = "$hours hour" . ($hours > 1 ? 's' : '') . " $minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
} elseif ($hours > 0) {
    $timeDifference = "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
} elseif ($minutes > 0) {
    $timeDifference = "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
} else {
    $timeDifference = "just now";
}


$sync_time = $timeDifference;

$FrId = $result['FrId'];
$sql3 = "SELECT count(*) AS TotEmp, SUM(tu.MonthlySalary) AS MonthlySalary FROM tbl_users tu WHERE tu.UnderFrId = '$FrId' AND tu.Status=1";
    $row3 = getRecord($sql3);
    
    $month = date('m');
    $year = date('Y');
    $sql = "SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE FrId='$FrId' AND month(InvoiceDate)='$month' AND year(InvoiceDate)='$year' UNION ALL 
    SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId='$FrId' AND month(InvoiceDate)='$month' AND year(InvoiceDate)='$year') as a";
    $row = getRecord($sql);
    
    
    // MRP Product
$sql2 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
         FROM tbl_customer_invoice_details_2025 tc 
         INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
         WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 1 AND tp.ProdType = 0 AND tc.MainProdId NOT IN (177,174,2220,1962,2598,2597,2596,2561,2559,2558,2557) ";
         
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
          WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 2 AND tp.ProdType = 0 AND tc.MainProdId NOT IN (177,174,2220,1962,2598,2597,2596,2561,2559,2558,2557) ";
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
          WHERE tc.FrId = '$FrId' AND tc.MainProdId IN (177,174,2220,1962,2598,2597,2596,2561,2559,2558,2557) ";
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

$zomato = countval('zomato',$result['FrId'],$Calendar);
$credit = countval('credit',$result['FrId'],$Calendar);
    ?>
    <div class="col-lg-6">
 <div class="card mb-2">
                   <!-- <a href="product-lists.php?catid=<?php echo $result['id'];?>">-->
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            <a href="https://kwickfoods.in/billsoftappadmin2025/fr_acc/dashboard.php?id=<?php echo $result['FrId']; ?>" target="_new">
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"><?php echo $result['ShopName'];?></h6>
                                          <span class="small text-secondary" style=""><?php echo $result['Address'];?></span>
                                          <p class="small " style="text-transform:capitalize;color:black">Employees : <?php echo $row3['TotEmp'];?> | Salary : <?php echo $row3['MonthlySalary'];?> <br>
                                          QSR KITCHEN SALES : <?= $row21['TotSell']; ?> | &#8377;<?= $row21['NetAmount']; ?> <br>
                                          PACK FOOD SALES : <?= $row2['TotSell']; ?> | &#8377;<?= $row2['NetAmount']; ?> <br>
                                          CROSS SALES : <?= $row22['TotSell']; ?> | &#8377;<?= $row22['NetAmount']; ?> <br></p>
                                       <p class="small " style="text-transform:capitalize;color:#009eff;">Last Sync : <?php echo $sync_time;?></p>
                                       
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($NetAmount,2);?></strong>
                                         <br><span style="font-size:12px;">Cash : ₹<?php echo number_format(countval('cash_payment',$result['FrId'],$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">UPI : ₹<?php echo number_format(countval('upi_payment',$result['FrId'],$Calendar),2);?></span>
                                         <?php if($zomato > 0){?>
                                         <br><span style="font-size:12px;">Swiggy/Zomato : ₹<?php echo number_format($zomato,2);?></span>
                                         <?php } ?>
                                         <?php if($credit > 0){?>
                                         <br><span style="font-size:12px;">Credit : ₹<?php echo number_format($credit,2);?></span>
                                         <?php } ?>
                                         <br><span style="font-size:12px;">Avg : <?php echo number_format($NetAmount/$result['TotInv'],2);?></span></p>
                                    </div>
                                </div>
                            </li>
                            </a>
                        </ul>
                    </div>
               <!-- </a>-->
                </div>
                </div>
                 <?php } } ?>
                 
                  <?php 
   $rncnt225 = $rncnt224;
   ?>
                 
                 <div class="col-lg-6">
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
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotNetAmount+$NetAmount3,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    <?php if($TotNetAmount+$NetAmount3 == 0){?>
                     <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Avg</h6>
                                         
                                      
                                    </div> 
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>0</strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    <?php } else {?>
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Avg</h6>
                                         
                                      
                                    </div> 
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong><?php echo number_format(($TotNetAmount+$NetAmount3)/$rncnt225,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    <?php } ?>
                </div>
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
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
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
