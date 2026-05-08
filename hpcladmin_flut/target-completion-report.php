<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Target-Complete";
$Page = "Target-Completion-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Task List</title>
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
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_assets WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-assets.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Target Completion Report
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
                                            <select class="form-control" id="ZoneId" name="ZoneId" >
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
                                            <label class="form-label">Sub Zone </label>
                                            <select class="form-control" id="SubZoneId" name="SubZoneId" >
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_sub_zone WHERE Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["SubZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-3">
<label class="form-label">Month</label>
<select class="form-control" style="width: 100%" name="month" id="month" required>
<option <?php if($_POST['month'] == '01'){?> selected <?php } ?> value="01">Jan</option>
<option <?php if($_POST['month'] == '02'){?> selected <?php } ?> value="02">Feb</option>
<option <?php if($_POST['month'] == '03'){?> selected <?php } ?> value="03">Mar</option>
<option <?php if($_POST['month'] == '04'){?> selected <?php } ?> value="04">Apr</option>
<option <?php if($_POST['month'] == '05'){?> selected <?php } ?> value="05">May</option>
<option <?php if($_POST['month'] == '06'){?> selected <?php } ?> value="06">Jun</option>
<option <?php if($_POST['month'] == '07'){?> selected <?php } ?> value="07">Jul</option>
<option <?php if($_POST['month'] == '08'){?> selected <?php } ?> value="08">Aug</option>
<option <?php if($_POST['month'] == '09'){?> selected <?php } ?> value="09">Sep</option>
<option <?php if($_POST['month'] == '10'){?> selected <?php } ?> value="10">Oct</option>
<option <?php if($_POST['month'] == '11'){?> selected <?php } ?> value="11">Nov</option>
<option <?php if($_POST['month'] == '12'){?> selected <?php } ?> value="12">Dec</option>
  </select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Year</label>
<select class="form-control" style="width: 100%" name="year" id="year" required>
     <option <?php if($_POST['year'] == '2025'){?> selected <?php } ?> value="2025">2025</option>
    <option <?php if($_POST['year'] == '2024'){?> selected <?php } ?> value="2024">2024</option>
  </select>
<div class="clearfix"></div>
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
               <th>Sr No</th>
               
               <th>Franchise Name</th>
                <th>Zone</th>
                 <th>Sub Zone</th>
                 <th>Yesterday Sale (FTD)</th> 
                 <th>Yesterday Sale Discount</th> 
                 <th>Target</th> 
                <th>Total Amount</th> 
                <th>Percentage</th> 
                
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $Month = $_REQUEST['Month'];
          if($Month == 1){$MonthName = "Jan";}
          if($Month == 2){$MonthName = "Feb";}
          if($Month == 3){$MonthName = "Mar";}
          if($Month == 4){$MonthName = "Apr";}
          if($Month == 5){$MonthName = "May";}
          if($Month == 6){$MonthName = "Jun";}
          if($Month == 7){$MonthName = "Jul";}
          if($Month == 8){$MonthName = "Aug";}
          if($Month == 9){$MonthName = "Sep";}
          if($Month == 10){$MonthName = "Oct";}
          if($Month == 11){$MonthName = "Nov";}
          if($Month == 12){$MonthName = "Dec";}
          $Year = $_REQUEST['Year'];
          
            $i=1;
            $sql = "SELECT InvoiceDate,FrId,ShopName,ZoneId,SubZoneId FROM (";
                                            $sql.= "SELECT tp.InvoiceDate,tp.FrId,tu.ShopName,tu.ZoneId,tu.SubZoneId FROM tbl_customer_invoice tp INNER JOIN tbl_users tu ON tp.frid=tu.id WHERE tu.Status=1";
                                            if ($_POST['frid']) {
                                                $UserId = $_POST['frid'];
                                                if ($UserId == 'all') {
                                                    $sql .= " ";
                                                } else {
                                                    $sql .= " AND tp.frid='$UserId'";
                                                }
                                            }
                                            
                                              if($_POST['ZoneId']){
                $ZoneId = $_POST['ZoneId'];
                if($ZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.ZoneId='$ZoneId'";
                }
                }
                
                 if($_POST['SubZoneId']){
                $SubZoneId = $_POST['SubZoneId'];
                if($SubZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.SubZoneId='$SubZoneId'";
                }
                }
                
                                            if ($_POST['month']) {
                                                $month = $_POST['month'];
                                                $sql .= " AND month(tp.InvoiceDate)='$month'";
                                            }
                                            if ($_POST['year']) {
                                                $year = $_POST['year'];
                                                $sql .= " AND year(tp.InvoiceDate)='$year'";
                                            }

                                            $sql .= " 
                                                    UNION ALL 
                                                    SELECT tp.InvoiceDate,tp.FrId, tu.ShopName,tu.ZoneId,tu.SubZoneId FROM tbl_customer_invoice_2025 tp INNER JOIN tbl_users_bill tu ON tp.frid = tu.id WHERE 1";
                                            if ($_POST['frid']) {
                                                $UserId = $_POST['frid'];
                                                if ($UserId == 'all') {
                                                    $sql .= " ";
                                                } else {
                                                    $sql .= " AND tp.frid='$UserId'";
                                                }
                                            }
                                            
                                            if($_POST['ZoneId']){
                $ZoneId = $_POST['ZoneId'];
                if($ZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.ZoneId='$ZoneId'";
                }
                }
                
                 if($_POST['SubZoneId']){
                $SubZoneId = $_POST['SubZoneId'];
                if($SubZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.SubZoneId='$SubZoneId'";
                }
                }
                
                                            if ($_POST['month']) {
                                                $month = $_POST['month'];
                                                $sql .= " AND month(tp.InvoiceDate)='$month'";
                                            }
                                            if ($_POST['year']) {
                                                $year = $_POST['year'];
                                                $sql .= " AND year(tp.InvoiceDate)='$year'";
                                            }
                                            $sql .= " ORDER BY InvoiceDate ASC) as a GROUP BY FrId";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $InvoiceDate = $row['InvoiceDate'];
                $sql2 = "SELECT SUM(TotAmt) AS TotAmt FROM (";
                $sql2.= "SELECT SUM(NetAmount) AS TotAmt FROM tbl_customer_invoice WHERE FrId='".$row['FrId']."'";
                if($_POST['month']){
                $month = $_POST['month'];
                $sql2.= " AND month(InvoiceDate)='$month'";
                }
                if($_POST['year']){
                    $year = $_POST['year'];
                    $sql2.= " AND year(InvoiceDate)='$year'";
                }
                $sql2.=" UNION ALL ";
                $sql2.= "SELECT SUM(NetAmount) AS TotAmt FROM tbl_customer_invoice_2025 WHERE FrId='".$row['FrId']."'";
                if($_POST['month']){
                $month = $_POST['month'];
                $sql2.= " AND month(InvoiceDate)='$month'";
                }
                if($_POST['year']){
                    $year = $_POST['year'];
                    $sql2.= " AND year(InvoiceDate)='$year'";
                }
                $sql2.=") as a";
                //echo $sql2;
                $row2 = getRecord($sql2);
                
                $sql3 = "SELECT * FROM tbl_set_target WHERE frid='".$row['FrId']."'";
                if($_POST['month']){
                $month = $_POST['month'];
                $sql3.= " AND month='$month'";
                }
                if($_POST['year']){
                    $year = $_POST['year'];
                    $sql3.= " AND year='$year'";
                }
                $row3 = getRecord($sql3);
                $target = $row3['target'];
                $totamt =$row2['TotAmt'];
                //$totper+=round($target/$row2['TotAmt'],2);
                /*if($target > 0){*/
                    
                    $yesteday = date('Y-m-d',strtotime("-1 days"));
                    $sql26 = "SELECT 
            SUM(NetAmount) AS YestTotAmt,
            SUM(Discount) AS YestDiscount 
          FROM (
            SELECT NetAmount, Discount 
            FROM tbl_customer_invoice 
            WHERE FrId = '".$row['FrId']."' AND InvoiceDate = '".$yesteday."'
            UNION ALL 
            SELECT NetAmount, Discount 
            FROM tbl_customer_invoice_2025 
            WHERE FrId = '".$row['FrId']."' AND InvoiceDate = '".$yesteday."'
          ) AS a";

$row26 = getRecord($sql26);

                    
                    $sql24 = "SELECT * FROM tbl_zone WHERE id='".$row['ZoneId']."'";
                $row24 = getRecord($sql24);
                
                $sql21 = "SELECT * FROM tbl_sub_zone WHERE id='".$row['SubZoneId']."'";
                $row21 = getRecord($sql21);
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
             
              <td><?php echo $row['ShopName']; ?></td> 
               <td><?php echo $row24['Name']; ?></td>
             <td><?php echo $row21['Name']; ?></td>
                <td><?php echo round($row26['YestTotAmt'],2); ?></td> 
                  <td><?php echo round($row26['YestDiscount'],2); ?></td> 
               <td><?php echo $target; ?></td> 
              <td><?php echo round($totamt,2); ?></td> 
              <td><?php echo round(($totamt/$target)*100,2); ?>%</td> 
             
          
              
            </tr>
           <?php $i++; } ?>
          
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
 
    $(document).ready(function() {
    $('#example').DataTable({
         "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
