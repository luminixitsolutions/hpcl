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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">




<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Top Selling Product
  
</h4>

<div class="card" style="padding: 10px;">
     <ul class="nav nav-tabs" id="reportTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="report-tab" data-toggle="tab" href="#report" role="tab">Report</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="graph-tab" data-toggle="tab" href="#graph" role="tab">Graph</a>
    </li>
  </ul>
  
  <div class="tab-content" id="reportTabContent">
    <!-- Report Tab -->
    <div class="tab-pane fade show active" id="report" role="tabpanel">
        <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

           
 <div class="form-group col-md-4">
                                            <label class="form-label">Franchise </label>
                                            <select class="select2-demo form-control" id="FrId" name="FrId" required="">
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT id,ShopName FROM tbl_users_bill WHERE Roll = 5 AND Status = 1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["FrId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['ShopName'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div> 

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
                                        
<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">Show Qty</label>
<select class="form-control" name="QtyType">
    <option value="0" <?php if($_REQUEST['QtyType'] == 0){?> selected <?php } ?>>All</option>
    <option value="1" <?php if($_REQUEST['QtyType'] == 1){?> selected <?php } ?>>Zero</option>
    <option  value="2" <?php if($_REQUEST['QtyType'] == 2){?> selected <?php } ?>>Non-Zero</option>
</select>
</div>
<div class="form-group col-md-2">
<label class="form-label">Product Type</label>
<select class="form-control" name="ProdType2">
      <option value="all" selected>All</option>
    <option value="1" <?php if($_REQUEST['ProdType2'] == 1){?> selected <?php } ?>>MRP</option>
    <option value="2" <?php if($_REQUEST['ProdType2'] == 2){?> selected <?php } ?>>Making</option>
   
</select>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:25px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
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
   <?php if($_REQUEST['Search']=='Search') {?>
<div class="card-datatable table-responsive">


<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Outlet Name</th>
            <th>Zone</th>
            <th>Sub Zone</th>
            <th>Product Name</th>
           
           <th>Purchase Amount</th>
                <th>Sell Amount</th>
                <!-- <th>Profit Amount</th>-->
                 <th>Total Sale Qty</th>
           
        </tr>
    </thead>
    <tbody>
        <?php 
        $ZoneId = $_REQUEST['ZoneId'];
        $SubZoneId = $_REQUEST['SubZoneId'];
        $ProdType2 = $_REQUEST['ProdType2'];
        $FrId = $_POST['FrId'] ?? '';
            $sql = "SELECT id,ZoneId,SubZoneId,ShopName FROM tbl_users_bill WHERE Status=1";
            if (!empty($FrId) && $FrId !== 'all') $sql .= " AND id = '$FrId'";
            if (!empty($ZoneId) && $ZoneId !== 'all') $sql .= " AND ZoneId = '$ZoneId'";
            if (!empty($SubZoneId) && $SubZoneId !== 'all') $sql .= " AND SubZoneId = '$SubZoneId'";
            
            $row = getList($sql);
            foreach($row as $result){
                // Zone & Sub-Zone
    $zone = getRecord("SELECT Name FROM tbl_zone WHERE id = '{$result['ZoneId']}'");
    $subzone = getRecord("SELECT Name FROM tbl_sub_zone WHERE id = '{$result['SubZoneId']}'");
    
    $BillSoftFrId = $result['id'];

     $sql21 = "SELECT id,ProductName,MinPrice,PurchasePrice FROM tbl_cust_products_2025 WHERE CreatedBy=$BillSoftFrId AND checkstatus=1 AND delete_flag=0";
     if (!empty($ProdType2) && $ProdType2 !== 'all') $sql21 .= " AND ProdType2 = '$ProdType2'";
     //echo $sql21;
     $row21 = getList($sql21);
     foreach($row21 as $result2){
         
         $sql2 = "SELECT IFNULL(SUM(tcid.Total), 0) AS Total,
            IFNULL(SUM(tcid.Qty), 0) AS TotProd FROM tbl_customer_invoice_details_2025 tcid INNER JOIN tbl_customer_invoice_2025 tci ON tci.id=tcid.InvId WHERE tcid.ProdId='".$result2['id']."' AND tci.FrId='$BillSoftFrId'";
                
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND tci.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql2.= " AND tci.InvoiceDate<='$ToDate'";
            }
            $row2 = getRecord($sql2);
            if($_REQUEST['QtyType']==0){?>
            <tr>
                <td><?php echo $result['ShopName'];?></td>
                <td><?php echo $zone['Name'];?></td>
                <td><?php echo $subzone['Name'];?></td>
                <td><?php echo $result2['ProductName'];?></td>
                 
                  <td>&#8377;<?php echo number_format($result2['PurchasePrice'],2); ?></td>
                <td>&#8377;<?php echo number_format($result2['MinPrice'],2); ?></td>
              
                <td><?php echo $row2['TotProd']; ?></td>
            </tr>
            <?php } else if($_REQUEST['QtyType']==2) {
                 if($row2['TotProd'] > 0){?>
                 <tr>
                <td><?php echo $result['ShopName'];?></td>
                <td><?php echo $zone['Name'];?></td>
                <td><?php echo $subzone['Name'];?></td>
                <td><?php echo $result2['ProductName'];?></td>
               
                  <td>&#8377;<?php echo number_format($result2['PurchasePrice'],2); ?></td>
                <td>&#8377;<?php echo number_format($result2['MinPrice'],2); ?></td>
               
                 <td><?php echo $row2['TotProd']; ?></td>
            </tr>
            <?php }} else {
                 if($row2['TotProd'] <= 0){
                 ?>
                 <tr>
                <td><?php echo $result['ShopName'];?></td>
                <td><?php echo $zone['Name'];?></td>
                <td><?php echo $subzone['Name'];?></td>
                <td><?php echo $result2['ProductName'];?></td>
                
                   <td>&#8377;<?php echo number_format($result2['PurchasePrice'],2); ?></td>
                <td>&#8377;<?php echo number_format($result2['MinPrice'],2); ?></td>
                 
                   <td><?php echo $row2['TotProd']; ?></td>
            </tr>
                 
                 
                <?php }}}} ?>
    </tbody>
</table>

</div>
<?php } ?>
    </div>

    <!-- Graph Tab -->
    <div class="tab-pane fade" id="graph" role="tabpanel">
    <div class="row">
        <div class="col-lg-6">
            <canvas id="financeChart" width="100%" height="40" style="margin-top:20px;"></canvas>
        </div>
        <div class="col-lg-6">
            <canvas id="balanceChart" width="100%" height="40" style="margin-top:20px;"></canvas>
        </div>
    </div>
    
    <?php
$topProducts = []; 
$BillSoftFrId = $_POST['FrId'] ?? '';
$sqlTop = "
    SELECT p.ProductName, SUM(tcid.Qty) AS TotalQty
    FROM tbl_customer_invoice_details_2025 tcid
    INNER JOIN tbl_customer_invoice_2025 tci ON tci.id = tcid.InvId
    INNER JOIN tbl_cust_products_2025 p ON p.id = tcid.ProdId
    WHERE tci.FrId = '$BillSoftFrId'
";

if ($_REQUEST['FromDate']) {
    $FromDate = $_REQUEST['FromDate'];
    $sqlTop .= " AND tci.InvoiceDate >= '$FromDate'";
}
if ($_REQUEST['ToDate']) {
    $ToDate = $_REQUEST['ToDate'];
    $sqlTop .= " AND tci.InvoiceDate <= '$ToDate'";
}

$sqlTop .= " GROUP BY p.ProductName ORDER BY TotalQty DESC LIMIT 10";
//echo $sqlTop;
$topProductRows = getList($sqlTop);

$graphLabels = [];
$graphValues = [];

foreach ($topProductRows as $row) {
    $graphLabels[] = $row['ProductName'];
    $graphValues[] = (int)$row['TotalQty'];
}
?>


  
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
<script>
const productLabels = <?= json_encode($graphLabels); ?>;
const productValues = <?= json_encode($graphValues); ?>;

// Top 10 Selling Products - Bar Chart
const barCtx = document.getElementById('financeChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: productLabels,
        datasets: [{
            label: 'Total Sold Qty',
            data: productValues,
            backgroundColor: productLabels.map((_, i) => `hsl(${i * 36}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Selling Products (Bar)'
            },
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: context => `${context.label}: ${context.raw} pcs`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Quantity'
                }
            }
        }
    }
});

// Top 10 Selling Products - Doughnut Chart
const doughnutCtx = document.getElementById('balanceChart').getContext('2d');
new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: productLabels,
        datasets: [{
            label: 'Total Sold Qty',
            data: productValues,
            backgroundColor: productLabels.map((_, i) => `hsl(${i * 36}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Selling Products (Doughnut)'
            },
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: context => `${context.label}: ${context.raw} pcs`
                }
            }
        }
    }
});
</script>

<script type="text/javascript">
 function changeStatus(id,val){
     window.location.href='expense-request.php?action=changestatus&id='+id+'&status='+val;
 }
    $(document).ready(function() {
    $('#example').DataTable({
         "scrollX": true,
         "pageLength":50,
      order: [[6, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
