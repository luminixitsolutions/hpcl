<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-New";
$Page = "Weekly-Sale-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
    if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }
    $sql.=" UNION ALL SELECT COUNT(*) AS TotalInv FROM tbl_customer_invoice_2025 WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }
    $sql.=") as a";
    //echo $sql;
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['TotalInv'];
}

function ftdRevenueAmt($frid,$fromdate,$todate,$paymode){
    global $conn;
    $sql = "SELECT SUM(NetAmount) AS NetAmount FROM (SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }
    
    $sql.= " UNION ALL SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }
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
  
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Outlet ID</th>
                <th>Outlet Name</th>
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
            $cal = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));  
                $yesterday = date('Y-m-d',strtotime("-1 days"));
                $lastmonth = date('Y-m',strtotime("-1 month"));
                $lastmonth_startdate = $lastmonth."-01";
                $lastmonth_enddate = $lastmonth."-".date('d',strtotime("-1 days"));
                $sql = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND ShowFrStatus=1";
                $row = getList($sql);
                foreach($row as $result){
              
            ?>
            <tr>
                <td><?php echo $result['CustomerId'];?></td>
                <td><?php echo $result['ShopName'];?></td>
               <td><?php echo $result['Location'];?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['SellDate']))); ?></td>
                <td><?php echo calMonth($result['SellDate'],date('Y-m-d'));?></td>
                <td></td>
                <td><?php echo ftdRevenue($result['id'],date('Y-m')."-01",date('Y-m')."-07",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],date('Y-m')."-01",date('Y-m')."-07",'');?></td>
                <td><?php echo ftdRevenue($result['id'],date('Y-m')."-08",date('Y-m')."-14",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],date('Y-m')."-08",date('Y-m')."-14",'');?></td>
                <td><?php echo ftdRevenue($result['id'],date('Y-m')."-15",date('Y-m')."-21",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],date('Y-m')."-15",date('Y-m')."-21",'');?></td>
                <td><?php echo ftdRevenue($result['id'],date('Y-m')."-22",date('Y-m')."-28",'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],date('Y-m')."-22",date('Y-m')."-28",'');?></td>
                <?php if($cal > 28){?>
                <td><?php echo ftdRevenue($result['id'],date('Y-m')."-29",date('Y-m')."-".$cal,'');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],date('Y-m')."-29",date('Y-m')."-".$cal,'');?></td>
                <?php } else {?>
                <td>0</td>
                <td>0</td>
                <?php } ?>
              
            </tr>
          <?php } ?>
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
