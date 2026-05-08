<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Daily-Sale-Report-2";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>Daily Sale Report - <?php echo $Proj_Title; ?> </title>
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
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI','Borrowing','Swiggy','Zomato')";
    }
    if($paymode=='Cash'){
        $sql.=" AND PayType IN ('Cash')";
    }
    }
    $sql.=" UNION ALL SELECT COUNT(*) AS TotalInv FROM tbl_customer_invoice_2025 WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";
    if($paymode!=''){
    if($paymode!='Cash'){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI','Borrowing','Swiggy','Zomato')";
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
<h4 class="font-weight-bold py-3 mb-0">Daily Sale Report
  
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
                                        
 <div class="form-group col-md-3">
    <label class="form-label">State <span class="text-danger">*</span></label>
<select class="form-control" id="StateId" name="StateId" required="">
<option selected="" value="all">All</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>

<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1">
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
                <th>State</th>
                <th>Location</th>
                <th>Outlet Opening Date</th>
                <th>Outlet Vintage</th>
                <th>Outlet Manager</th>
               
               
             <th>MTD No of Invoices</th>
                <th>MTD Amount</th>
                <th>PMSD No of Invoices </th>
                <th>PMSD Amount</th>
                <th>% Growth No of Invoices</th>
                <th>% Growth Amount</th>
                <th>Cash No of Invoices</th>
                <th>Cash Value</th>
                <th>UPI No of Invoices</th>
                <th>UPI Value</th>
               
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
                $yesterday = date('Y-m-d',strtotime("-1 days"));
                $lastmonth2 = date('m',strtotime("-1 month"));
                if($lastmonth2 == date('m')){
                    $m = date('m')-1;
                    $lastmonth = date('Y')."-".$m;
                }
                else{
                $lastmonth = date('Y-m',strtotime("-1 month"));
                }
                $lastmonth_startdate = $lastmonth."-01";
                $lastmonth_enddate = $lastmonth."-".date('d',strtotime("-1 days"));
                
                $FromDate = $_POST['FromDate'];
                $ToDate = $_POST['ToDate'];


$previousFromDate = date("Y-m-d", strtotime($FromDate . " -1 month"));
$previousToDate = date("Y-m-d", strtotime($ToDate . " -1 month"));


$earlier = new DateTime("2024-09-01");
$later = new DateTime("2024-10-31");

$abs_diff = $later->diff($earlier)->format("%a");
 
 $newDateMinus = date('Y-m-d', strtotime($FromDate . ' - '.$abs_diff.' days'));
 $lastnewDateMinus = date('Y-m-d', strtotime($FromDate . ' - 1 days'));
 
                $sql = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND ShowFrStatus=1";
                if($_POST['StateId']){
                $StateId = $_POST['StateId'];
                if($StateId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND StateId='$StateId'";
                }
            }
            
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
                    
                    $sql4 = "SELECT Name FROM tbl_state WHERE id='".$result['StateId']."'";
                    $row4 = getRecord($sql4);
                    
                    $mtd_inv = ftdRevenue($result['id'],$FromDate,$ToDate,'');
                    $mtd_inv_amt = ftdRevenueAmt($result['id'],$FromDate,$ToDate,'');

                    $pmsd_inv = ftdRevenue($result['id'],$previousFromDate,$previousToDate,'');
                    $pmsd_inv_amt = ftdRevenueAmt($result['id'],$previousFromDate,$previousToDate,'');

                    $growth_inv2 = ($mtd_inv - $pmsd_inv);
                    if($growth_inv2 == 0 && $pmsd_inv == 0){
                        $growth_inv = 0;
                    }
                    else if($pmsd_inv == 0){
                        $growth_inv = 0;
                    }
                    else{
                        $growth_inv = $growth_inv2 / $pmsd_inv;
                    }

                    $growth_inv_amt2 = ($mtd_inv_amt - $pmsd_inv_amt);
                    if($growth_inv_amt2 == 0 && $pmsd_inv_amt == 0){
                        $growth_inv_amt = 0;
                    }
                    else if($pmsd_inv_amt == 0){
                        $growth_inv_amt = 0;
                    }
                    else{
                        $growth_inv_amt = $growth_inv_amt2 / $pmsd_inv_amt;
                    }
            ?>
            <tr>
                <td><?php echo $result['CustomerId'];?></td>
                <td><?php echo $result['ShopName'];?></td>
                 <td><?php echo $row2['Name']; ?></td>
                <td><?php echo $row4['Name']; ?></td>
                <td><?php echo $result['Location'];?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['SellDate']))); ?></td>
                <td><?php echo calMonth($result['SellDate'],date('Y-m-d'));?></td>
                <td><?php echo $row3['Fname']; ?></td>
               
                <td><?php echo $mtd_inv;?></td>
                <td><?php echo $mtd_inv_amt;?></td> 
               <td><?php echo $pmsd_inv;?></td>
                <td><?php echo $pmsd_inv_amt;?></td>
                <td><?php echo round($growth_inv,2);?></td>
                <td><?php echo round($growth_inv_amt,2);?></td>
                <td><?php echo ftdRevenue($result['id'],$FromDate,$ToDate,'Cash');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$FromDate,$ToDate,'Cash');?></td>
                <td><?php echo ftdRevenue($result['id'],$FromDate,$ToDate,'UPI');?></td>
                <td><?php echo ftdRevenueAmt($result['id'],$FromDate,$ToDate,'UPI');?></td>
                
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
