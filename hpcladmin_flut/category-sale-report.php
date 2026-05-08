<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Franchise-Report-2025";
$Page = "Sell-category-Report-2025";
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







<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Category Wise Sell Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

<div class="form-group col-md-2">
                                            <label class="form-label">Zone </label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" onchange="getSubZone(this.value)" >
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
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="FrId" id="FrId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["FrId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
            <?php } ?>
        </select>
    </div>


<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<!--<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<button type="button" id="print" class="btn btn-success btn-finish" onClick=printReport('<?php echo $invoice_data;?>')>Print</button>
</div>-->
<?php if(isset($_REQUEST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
   <?php if(isset($_REQUEST['Search'])) {?>
<div class="card-datatable table-responsive">


<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Franchise Name</th>
             <th>Zone</th>
                 <th>Sub Zone</th>
            <th>Category</th> 
            <th>Total Sell</th>
            <th>Amount</th>
            <th>Percentage</th>
            <th> APC</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $sql = "SELECT tu.ShopName,tu.id,tz.Name AS ZoneName,tsz.Name AS SubZoneName FROM tbl_users_bill tu 
        INNER JOIN tbl_zone tz ON tu.ZoneId=tz.id 
            INNER JOIN tbl_sub_zone tsz ON tu.SubZoneId=tsz.id 
            WHERE tu.ShopName!='' ";
        if($_POST['FrId']){
                $FrId = $_POST['FrId'];
                if($FrId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.id='$FrId'";
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
                
$row = getList($sql);
foreach($row as $result){
    $FrId = $result['id'];
$FromDate = $_REQUEST['FromDate'] ?? '';
$ToDate = $_REQUEST['ToDate'] ?? '';

// Fetch Franchise Name


// Common date filter
$dateFilter = "";
if ($FromDate) {
    $dateFilter .= " AND tc.CreatedDate >= '$FromDate'";
}
if ($ToDate) {
    $dateFilter .= " AND tc.CreatedDate <= '$ToDate'";
}
 
// MRP Product
$sql2 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
         FROM tbl_customer_invoice_details_2025 tc 
         INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
         WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 1 AND tp.ProdType = 0 AND tp.CrossSell!=1 
         $dateFilter";
$row2 = getRecord($sql2);

// Kitchen Product
$sql21 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId = '$FrId' AND tp.ProdType2 = 2 AND tp.ProdType = 0 AND tp.CrossSell!=1 
          $dateFilter";
$row21 = getRecord($sql21);

// Cross Product
$sql22 = "SELECT IFNULL(SUM(tc.Total),0) AS NetAmount, IFNULL(SUM(tc.Qty),0) AS TotSell 
          FROM tbl_customer_invoice_details_2025 tc 
          INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id 
          WHERE tc.FrId = '$FrId' AND tp.CrossSell=1 
          $dateFilter";
$row22 = getRecord($sql22);

$totalsell = $row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount'];


?>
        <tr>
            <td><?php echo $i;?></td>
            <td><?= $result['ShopName']; ?></td>
            <td><?= $result['ZoneName']; ?></td>
            <td><?= $result['SubZoneName']; ?></td>
            <td>QSR KITCHEN SALES</td> 
            <td><?= $row21['TotSell']; ?></td>
            <td><?= $row21['NetAmount']; ?></td>
            <td><?php if($row21['NetAmount'] > 0){ echo number_format(($row21['NetAmount']/$totalsell)*100,2); ?>% <?php } else { echo "0%";} ?></td>
            <td><?= number_format(($row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount'])/($row2['TotSell']+$row21['TotSell']+$row22['TotSell']),2); ?>%</td>
        </tr>
        <tr>
            <td><?php echo $i;?></td>
            <td><?= $result['ShopName']; ?></td>
            <td><?= $result['ZoneName']; ?></td>
            <td><?= $result['SubZoneName']; ?></td>
            <td>PACK FOOD SALES</td> 
            <td><?= $row2['TotSell']; ?></td>
            <td><?= $row2['NetAmount']; ?></td>
            <td><?php if($row2['NetAmount'] > 0){ echo number_format(($row2['NetAmount']/$totalsell)*100,2); ?>% <?php } else { echo "0%";} ?></td>
            <td><?= number_format(($row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount'])/($row2['TotSell']+$row21['TotSell']+$row22['TotSell']),2); ?>%</td>
        </tr>
        <tr>
            <td><?php echo $i;?></td>
            <td><?= $result['ShopName']; ?></td>
            <td><?= $result['ZoneName']; ?></td>
            <td><?= $result['SubZoneName']; ?></td>
            <td>CROSS SALES</td> 
            <td><?= $row22['TotSell']; ?></td>
            <td><?= $row22['NetAmount']; ?></td>
             <td><?php if($row22['NetAmount'] > 0){ echo number_format(($row22['NetAmount']/$totalsell)*100,2); ?>% <?php } else { echo "0%";} ?></td>
              <td><?= number_format(($row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount'])/($row2['TotSell']+$row21['TotSell']+$row22['TotSell']),2); ?>%</td>
        </tr>
         <tr>
            <td><?php echo $i;?></td>
            <td><?= $result['ShopName']; ?></td>
            <td><?= $result['ZoneName']; ?></td>
            <td><?= $result['SubZoneName']; ?></td>
            <th style="font-weight: bolder;color: black;">TOTAL SALES</th> 
            <th style="font-weight: bolder;color: black;"><?= $row2['TotSell']+$row21['TotSell']+$row22['TotSell']; ?></th>
            <th style="font-weight: bolder;color: black;"><?= $row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount']; ?></th>
            <th></th>
             <th style="font-weight: bolder;color: black;"><?= number_format(($row2['NetAmount']+$row21['NetAmount']+$row22['NetAmount'])/($row2['TotSell']+$row21['TotSell']+$row22['TotSell']),2); ?>%</th>
        </tr>
        <?php $i++;} ?>
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
function getSubZone(zoneid){

            var action = "getSubZone";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: zoneid
                },
                success: function(data) {
                    console.log(data);
                    $('#SubZoneId').html(data);
                }
            });
        }
  function printReport(invdata){
     console.log(invdata);
      Android.printReport(''+invdata+'');
 }
	$(document).ready(function() {
    $('#example').DataTable({
        "pageLength":100,
      "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

    /*$(document).on("change", "#FrId", function(event) {
                var val = this.value;
                var action = "getCustProdCategory";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#CatId').html(data);
                       
                    }
                });

            });*/
});
</script>
</body>
</html>
