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
<h4 class="font-weight-bold py-3 mb-0">Top Selling Product Of <?php echo $_GET['zonename'];?> - <?php echo $_GET['subzonename'];?>
  
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
   <?php if($_REQUEST['Search']=='Search') {
    $SubZoneId = $_GET['SubZoneId'];?>
<div class="card-datatable table-responsive">


<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            
            <th>Zone</th>
            <?php if($SubZoneId!=''){?>
           <th>Sub Zone</th>
           <?php } ?>
            <th>Product Name</th>
            <th>Total Sale Qty</th>
            <th>Purchase Amount</th>
                <th>Sell Amount</th>
                <!--<th>Profit Amount</th>-->
           
        </tr>
    </thead>
    <tbody>
        
            <?php 
            $ZoneId = $_GET['ZoneId'];
            $SubZoneId = $_GET['SubZoneId'];
            if($SubZoneId!=''){
            $sql = "SELECT ts.*,tz.Name As ZoneName FROM tbl_sub_zone ts INNER JOIN tbl_zone tz ON ts.CatId=tz.id WHERE tz.id='$ZoneId' AND ts.id='$SubZoneId'";
            }
            else{
             $sql = "SELECT tz.id,tz.Name As ZoneName FROM tbl_zone tz WHERE tz.id='$ZoneId'";   
            }
            $row = getList($sql);
            foreach($row as $result){
                if($SubZoneId!=''){
            $sql2 = "SELECT GROUP_CONCAT(id) AS id FROM `tbl_users_bill` WHERE ZoneId='$ZoneId' AND SubZoneId='$SubZoneId' AND Roll=5 AND Status=1";
                }
            else{
             $sql2 = "SELECT GROUP_CONCAT(id) AS id FROM `tbl_users_bill` WHERE ZoneId='$ZoneId' AND Roll=5 AND Status=1";
            }
            $row2 = getRecord($sql2);
            $frids = $row2['id'];
            
            $sql21 = "SELECT ProdId,ProductName,PurchasePrice,MinPrice FROM tbl_cust_products_2025 WHERE CreatedBy IN($frids) AND checkstatus=1 AND delete_flag=0 GROUP BY ProdId";
     $row21 = getList($sql21);
     foreach($row21 as $result2){
         
         $sql22 = "SELECT IFNULL(SUM(tcid.Total), 0) AS Total,
            IFNULL(SUM(tcid.Qty), 0) AS TotProd FROM tbl_customer_invoice_details_2025 tcid INNER JOIN tbl_customer_invoice_2025 tci ON tci.id=tcid.InvId WHERE tcid.MainProdId='".$result2['ProdId']."' AND tci.FrId IN ($frids)";
                
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql22.= " AND tci.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql22.= " AND tci.InvoiceDate<='$ToDate'";
            }
            $row22 = getRecord($sql22);
            if($_REQUEST['QtyType']==0){?>
            <tr>
              <td><?php echo $result['ZoneName'];?></td>
               <?php if($SubZoneId!=''){?>
              <td><?php echo $result['Name'];?></td>
              <?php } ?>
             <td><?php echo $result2['ProductName'];?></td>
              <td><?php echo $row22['TotProd']; ?></td>
            <td><?php echo $result2['PurchasePrice'];?></td>
             <td><?php echo $result2['MinPrice'];?></td>
             <!-- <td></td>-->
             </tr>
             <?php } else if($_REQUEST['QtyType']==2) {
                 if($row22['TotProd'] > 0){
            ?>
            <tr>
              <td><?php echo $result['ZoneName'];?></td>
               <?php if($SubZoneId!=''){?>
              <td><?php echo $result['Name'];?></td>
              <?php } ?>
             <td><?php echo $result2['ProductName'];?></td>
              <td><?php echo $row22['TotProd']; ?></td>
            <td><?php echo $result2['PurchasePrice'];?></td>
             <td><?php echo $result2['MinPrice'];?></td>
             <!-- <td></td>-->
             </tr>
             <?php }} else {
                 if($row22['TotProd'] <= 0){
            ?>
            <tr>
              <td><?php echo $result['ZoneName'];?></td>
               <?php if($SubZoneId!=''){?>
              <td><?php echo $result['Name'];?></td>
              <?php } ?>
             <td><?php echo $result2['ProductName'];?></td>
              <td><?php echo $row22['TotProd']; ?></td>
            <td><?php echo $result2['PurchasePrice'];?></td>
             <td><?php echo $result2['MinPrice'];?></td>
             <!-- <td></td>-->
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
            <canvas id="productBarChart" width="100%" height="40" style="margin-top:20px;"></canvas>
        </div>
        <div class="col-lg-6">
            <canvas id="productDoughnutChart" width="100%" height="40" style="margin-top:20px;"></canvas>
        </div>
    </div>
    
    <?php
$ZoneId = $_GET['ZoneId'] ?? '';
$SubZoneId = $_GET['SubZoneId'] ?? '';

$productData = [];

if (!empty($SubZoneId)) {
    $sql = "SELECT ts.*, tz.Name AS ZoneName 
            FROM tbl_sub_zone ts 
            INNER JOIN tbl_zone tz ON ts.CatId = tz.id 
            WHERE tz.id = '$ZoneId' AND ts.id = '$SubZoneId'";
} else {
    $sql = "SELECT tz.id, tz.Name AS ZoneName 
            FROM tbl_zone tz 
            WHERE tz.id = '$ZoneId'";
}

$zoneRows = getList($sql);

foreach ($zoneRows as $zone) {
    if (!empty($SubZoneId)) {
        $sql2 = "SELECT GROUP_CONCAT(id) AS id 
                 FROM tbl_users_bill 
                 WHERE ZoneId = '$ZoneId' AND SubZoneId = '$SubZoneId' AND Roll = 5 AND Status = 1";
    } else {
        $sql2 = "SELECT GROUP_CONCAT(id) AS id 
                 FROM tbl_users_bill 
                 WHERE ZoneId = '$ZoneId' AND Roll = 5 AND Status = 1";
    }

    $frData = getRecord($sql2);
    $frids = $frData['id'];

    if (!$frids) continue;

    $sqlProd = "SELECT ProdId, ProductName 
                FROM tbl_cust_products_2025 
                WHERE CreatedBy IN ($frids) AND checkstatus = 1 AND delete_flag = 0 
                GROUP BY ProdId";

    $productRows = getList($sqlProd);

    foreach ($productRows as $prod) {
        $sqlSales = "SELECT 
                        IFNULL(SUM(tcid.Qty), 0) AS TotProd 
                     FROM tbl_customer_invoice_details_2025 tcid 
                     INNER JOIN tbl_customer_invoice_2025 tci ON tci.id = tcid.InvId 
                     WHERE tcid.MainProdId = '{$prod['ProdId']}' AND tci.FrId IN ($frids)";

        if (!empty($_REQUEST['FromDate'])) {
            $FromDate = $_REQUEST['FromDate'];
            $sqlSales .= " AND tci.InvoiceDate >= '$FromDate'";
        }
        if (!empty($_REQUEST['ToDate'])) {
            $ToDate = $_REQUEST['ToDate'];
            $sqlSales .= " AND tci.InvoiceDate <= '$ToDate'";
        }

        $salesData = getRecord($sqlSales);

        if ($salesData['TotProd'] > 0) {
            $productData[] = [
                'name' => $prod['ProductName'],
                'qty' => (int)$salesData['TotProd']
            ];
        }
    }
}

// Sort and take top 10
usort($productData, function($a, $b) {
    return $b['qty'] <=> $a['qty'];
});
$topProducts = array_slice($productData, 0, 10);

$graphLabels = array_column($topProducts, 'name');
$graphValues = array_column($topProducts, 'qty');
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const prodLabels = <?= json_encode($graphLabels); ?>;
const prodValues = <?= json_encode($graphValues); ?>;

// Bar Chart
const barCtx = document.getElementById('productBarChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: prodLabels,
        datasets: [{
            label: 'Total Sale Qty',
            data: prodValues,
            backgroundColor: prodLabels.map((_, i) => `hsl(${i * 36}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Selling Products (Bar Chart)'
            },
            tooltip: {
                callbacks: {
                    label: context => `${context.label}: ${context.raw} pcs`
                }
            },
            legend: { display: false }
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

// Doughnut Chart
const doughnutCtx = document.getElementById('productDoughnutChart').getContext('2d');
new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: prodLabels,
        datasets: [{
            data: prodValues,
            backgroundColor: prodLabels.map((_, i) => `hsl(${i * 36}, 70%, 60%)`),
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Top 10 Selling Products (Doughnut Chart)'
            },
            tooltip: {
                callbacks: {
                    label: context => `${context.label}: ${context.raw} pcs`
                }
            },
            legend: {
                position: 'bottom'
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
         <?php if($_GET['SubZoneId']!=''){?>
         order: [[3, 'desc']],
         <?php } else {?>
      order: [[2, 'desc']],
      <?php } ?>
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
