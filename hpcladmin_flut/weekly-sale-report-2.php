<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Weekly-Sale-Report-2";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>Weekly Sale Report - <?php echo $Proj_Title; ?> </title>
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




<?php 
function calMonth($fromdate,$todate){
$date1 = $fromdate;
$date2 = $todate;
$ts1 = strtotime($date1);
$ts2 = strtotime($date2);
$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);
$month1 = date('m', $ts1);
$month2 = date('m', $ts2);
$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
if($diff > 0){
    $totmonth = $diff;
}
else{
    $totmonth = 0;
}
return $totmonth;
}

function ftdRevenue($frid,$fromdate,$todate,$paymode){
    global $conn;
    $sql = "SELECT SUM(TotalInv) AS TotalInv FROM (SELECT COUNT(*) AS TotalInv FROM tbl_customer_invoice WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    /*if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }*/
    $sql.=" UNION ALL SELECT COUNT(*) AS TotalInv FROM tbl_customer_invoice_2025 WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    /*if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }*/
    $sql.=") as a";
    //echo $sql;
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['TotalInv'];
}

function ftdRevenueAmt($frid,$fromdate,$todate,$paymode){
    global $conn;
    $sql = "SELECT SUM(NetAmount) AS NetAmount FROM (SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    /*if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }*/
    
    $sql.= " UNION ALL SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    /*if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }*/
    $sql.=") as a";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    if($row['NetAmount'] == ''){
        $NetAmount = 0;
    }
    else{
       $NetAmount = $row['NetAmount'];
    }
    return $NetAmount;
}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Weekly Sale Report
  
</h4>

<div class="card" style="padding: 10px;">
   <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    <div class="form-group col-md-2">
                                            <label class="form-label">Zone </label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" required="">
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_zone WHERE Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["ZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
<div class="form-group col-md-2">
<label class="form-label">Month </label>
<select class="form-control" name="month" id="month">
    <option value="01" <?php if($_REQUEST['month'] == '01'){?> selected <?php } ?>>Jan</option>
    <option value="02" <?php if($_REQUEST['month'] == '02'){?> selected <?php } ?>>Feb</option>
    <option value="03" <?php if($_REQUEST['month'] == '03'){?> selected <?php } ?>>Mar</option>
    <option value="04" <?php if($_REQUEST['month'] == '04'){?> selected <?php } ?>>Apr</option>
    <option value="05" <?php if($_REQUEST['month'] == '05'){?> selected <?php } ?>>May</option>
    <option value="06" <?php if($_REQUEST['month'] == '06'){?> selected <?php } ?>>Jun</option>
    <option value="07" <?php if($_REQUEST['month'] == '07'){?> selected <?php } ?>>Jul</option>
    <option value="08" <?php if($_REQUEST['month'] == '08'){?> selected <?php } ?>>Aug</option>
    <option value="09" <?php if($_REQUEST['month'] == '09'){?> selected <?php } ?>>Sep</option>
    <option value="10" <?php if($_REQUEST['month'] == '10'){?> selected <?php } ?>>Oct</option>
    <option value="11" <?php if($_REQUEST['month'] == '11'){?> selected <?php } ?>>Nov</option>
    <option value="12" <?php if($_REQUEST['month'] == '12'){?> selected <?php } ?>>Dec</option>
</select>
</div>

<div class="form-group col-md-2">
<label class="form-label">Year </label>
<select class="form-control" name="year" id="year">
<option <?php if($_REQUEST['year'] == '2024'){?> selected <?php } ?> value="2024">2024</option>
<option <?php if($_REQUEST['year'] == '2025'){?> selected <?php } ?> value="2025">2025</option>
<option <?php if($_REQUEST['year'] == '2023'){?> selected <?php } ?> value="2023">2023</option>
  </select>
</select>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
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
   <?php if(isset($_POST['Search'])) {?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Outlet ID</th>
                <th>Outlet Name</th>
                <th>Zone</th>
                <th>Location</th>
                <th>Outlet Opening Date</th>
                <th>Outlet Vintage in Months</th>
                <th>Outlet Manager</th>
                <th>Week 1 No of Invoices </th>
                <th>Week 1 Amount</th>
                <th>Week 2 No of Invoices</th>
                <th>Week 2 Amount</th>
                <th>Week 3 No of Invoices </th>
                <th>Week 3 Amount</th>
                <th>Week 4 No of Invoices</th>
                <th>Week 4 Amount</th>
                <th>Week 5 No of Invoices</th>
                <th>Week 5 Amount</th>
               
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $month = $_REQUEST['month'];
            $year = $_REQUEST['year'];
            $yearmonth = $year."-".$month;
            $cal = cal_days_in_month(CAL_GREGORIAN, $month, $year);  
                $yesterday = date('Y-m-d',strtotime("-1 days"));
                $lastmonth = date('Y-m',strtotime("-1 month"));
                $lastmonth_startdate = $lastmonth."-01";
                $lastmonth_enddate = $lastmonth."-".date('d',strtotime("-1 days"));
                
                
                $sql = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND ShowFrStatus=1";
                if($_POST['ZoneId']){
                $ZoneId = $_POST['ZoneId'];
                if($ZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ZoneId='$ZoneId'";
                }
                }
                $row = getList($sql);
                foreach($row as $result){
                     $sql2 = "SELECT * FROM tbl_zone WHERE id='".$result['ZoneId']."'";
                    $row2 = getRecord($sql2);
                    
                    $sql3 = "SELECT tu.Fname,tub.ShopName FROM `tbl_users` tu INNER JOIN tbl_users_bill tub ON tub.id=tu.UnderFrId WHERE tu.Roll=29 AND tu.UnderFrId='".$result['id']."'";
                    $row3 = getRecord($sql3);
              
            ?>
            <tr>
                <td><?php echo $result['CustomerId'];?></td>
                <td><?php echo $result['ShopName'];?></td>
                 <td><?php echo $row2['Name']; ?></td>
               <td><?php echo $result['Location'];?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['SellDate']))); ?></td>
                <td><?php echo calMonth($result['SellDate'],date('Y-m-d'));?></td> 
                  <td><?php echo $row3['Fname']; ?></td>
                <td><?php echo ftdRevenue($result['id'],$yearmonth."-"."-01",$yearmonth."-"."-07",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$yearmonth."-"."-01",$yearmonth."-"."-07",'');?></td>
                <td><?php echo ftdRevenue($result['id'],$yearmonth."-"."-08",$yearmonth."-"."-14",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$yearmonth."-"."-08",$yearmonth."-"."-14",'');?></td>
                <td><?php echo ftdRevenue($result['id'],$yearmonth."-"."-15",$yearmonth."-"."-21",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$yearmonth."-"."-15",$yearmonth."-"."-21",'');?></td>
                <td><?php echo ftdRevenue($result['id'],$yearmonth."-"."-22",$yearmonth."-"."-28",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$yearmonth."-"."-22",$yearmonth."-"."-28",'');?></td>
                <?php if($cal > 28){?>
                <td><?php echo ftdRevenue($result['id'],$yearmonth."-"."-29",$yearmonth."-"."-".$cal,'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$yearmonth."-"."-29",$yearmonth."-"."-".$cal,'');?></td>
                <?php } else {?>
                <td>0</td>
                <td>0</td>
                <?php } ?>
              
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
 function changeStatus(id,val){
     window.location.href='expense-request.php?action=changestatus&id='+id+'&status='+val;
 }
    $(document).ready(function() {
    $('#example').DataTable({
         "scrollX": true,
         "pageLength":50,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
