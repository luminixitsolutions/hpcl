<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Top-Sell-Dashboard";
$Page = "Daily-Sale-Report-2";
$filterFrId = $_REQUEST['FrId'] ?? 'all';
$filterZoneId = $_REQUEST['ZoneId'] ?? 'all';
$filterSubZoneId = $_REQUEST['SubZoneId'] ?? 'all';
$filterFromDate = $_REQUEST['FromDate'] ?? '';
$filterToDate = $_REQUEST['ToDate'] ?? '';
$filterQtyType = $_REQUEST['QtyType'] ?? '0';
$filterProdType2 = $_REQUEST['ProdType2'] ?? 'all';
$filterSearch = $_REQUEST['Search'] ?? '';
$adminRow = $_SESSION['Admin'] ?? [];
$shopAdminDealerIds = shopAdminDealerIds($adminRow);
$shopAdminDealerSql = '';
if (!empty($shopAdminDealerIds)) {
    $shopAdminDealerSql = ' AND id IN (' . implode(',', $shopAdminDealerIds) . ')';
}
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
     <!--<ul class="nav nav-tabs" id="reportTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="report-tab" data-toggle="tab" href="#report" role="tab">Report</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="graph-tab" data-toggle="tab" href="#graph" role="tab">Graph</a>
    </li>
  </ul>-->
  
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
                                                <?php $sql = "SELECT id,ShopName FROM tbl_users_bill WHERE Roll = 5 AND Status = 1" . $shopAdminDealerSql;
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($filterFrId == $result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['ShopName'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-2">
                                            <label class="form-label">Zone </label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" >
                                                <option selected=""  value="all">All</option>
                                                <?php
                                                    $zoneSql = "SELECT * FROM tbl_zone WHERE Status=1";
                                                    $allowedZones = shopAdminZoneIds($adminRow);
                                                    if (!empty($allowedZones)) {
                                                        $zoneSql = "SELECT * FROM tbl_zone WHERE Status=1 AND id IN (" . implode(',', $allowedZones) . ")";
                                                    }
                                                    $row = getList($zoneSql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($filterZoneId == $result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                              
 <div class="form-group col-md-2">
                                            <label class="form-label">Sub Zone </label>
                                            <select class="form-control" id="SubZoneId" name="SubZoneId" >
                                                <option selected=""  value="all">All</option>
                                                <?php
                                                    $subZoneSql = "SELECT * FROM tbl_sub_zone WHERE Status=1";
                                                    $allowedSubZones = shopAdminSubZoneIds($adminRow);
                                                    if (!empty($allowedSubZones)) {
                                                        $subZoneSql = "SELECT * FROM tbl_sub_zone WHERE Status=1 AND id IN (" . implode(',', $allowedSubZones) . ")";
                                                    }
                                                    $row = getList($subZoneSql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($filterSubZoneId == $result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">Show Qty</label>
<select class="form-control" name="QtyType">
    <option value="0" <?php if($filterQtyType == 0 || $filterQtyType === '0'){?> selected <?php } ?>>All</option>
    <option value="1" <?php if($filterQtyType == 1 || $filterQtyType === '1'){?> selected <?php } ?>>Zero</option>
    <option  value="2" <?php if($filterQtyType == 2 || $filterQtyType === '2'){?> selected <?php } ?>>Non-Zero</option>
</select>
</div>
<div class="form-group col-md-2">
<label class="form-label">Product Type</label>
<select class="form-control" name="ProdType2">
      <option value="all" selected>All</option>
    <option value="1" <?php if($filterProdType2 == 1 || $filterProdType2 === '1'){?> selected <?php } ?>>MRP</option>
    <option value="2" <?php if($filterProdType2 == 2 || $filterProdType2 === '2'){?> selected <?php } ?>>Making</option>
   
</select>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:25px;">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if($filterSearch !== '') {?>
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
   <?php if($filterSearch === 'Search') {?>
<div class="card-datatable table-responsive">


<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
       <tr>
            <th>Outlet Name</th>
            <th>Zone</th>
            <th>Sub Zone</th>
            <th>Product Name</th>
            <th>Purchase Price</th>
            <th>Sell Price</th>
            <th>Sale Month</th>
            <th>Total Qty</th>
            <th>Total Sale (₹)</th>
        </tr>
    </thead>
    <tbody>
        <?php
$FromDate = $filterFromDate;
$ToDate   = $filterToDate;
$FrId     = $filterFrId;
$ZoneId   = $filterZoneId;
$SubZoneId= $filterSubZoneId;
$ProdType2= $filterProdType2;
$QtyType  = (int)$filterQtyType;

// Build product + invoice join query
$sql = "
    SELECT 
        u.ShopName,
        z.Name AS Zone,
        sz.Name AS SubZone,
        p.ProductName,
        MAX(p.PurchasePrice) AS PurchasePrice,
        MAX(p.MinPrice) AS MinPrice,
        DATE_FORMAT(tci.InvoiceDate, '%b %Y') AS SaleMonth,
        SUM(tcid.Qty) AS TotalQty,
        SUM(tcid.Total) AS TotalSale
    FROM tbl_customer_invoice_details_2025 tcid
    INNER JOIN tbl_customer_invoice_2025 tci ON tci.id = tcid.InvId
    INNER JOIN tbl_cust_products_2025 p ON p.id = tcid.ProdId
    INNER JOIN tbl_users_bill u ON u.id = tci.FrId
    LEFT JOIN tbl_zone z ON u.ZoneId = z.id
    LEFT JOIN tbl_sub_zone sz ON u.SubZoneId = sz.id
    WHERE 1=1
";

// Filters
if ($FrId !== 'all')       $sql .= " AND u.id = '$FrId'";
if (!empty($shopAdminDealerIds)) $sql .= " AND u.id IN (" . implode(',', $shopAdminDealerIds) . ")";
if ($ZoneId !== 'all')     $sql .= " AND u.ZoneId = '$ZoneId'";
if ($SubZoneId !== 'all')  $sql .= " AND u.SubZoneId = '$SubZoneId'";
if ($ProdType2 !== 'all')  $sql .= " AND p.ProdType2 = '$ProdType2'";
if (!empty($FromDate))     $sql .= " AND tci.InvoiceDate >= '$FromDate'";
if (!empty($ToDate))       $sql .= " AND tci.InvoiceDate <= '$ToDate'";

// Grouping & Sorting
$sql .= "
    GROUP BY u.ShopName, z.Name, sz.Name, p.ProductName, DATE_FORMAT(tci.InvoiceDate, '%b %Y')
    ORDER BY p.ProductName, MIN(tci.InvoiceDate)
";

$rows = getList($sql);

     foreach($rows as $r) {
            if ($QtyType == 0 || ($QtyType == 1 && $r['TotalQty'] == 0) || ($QtyType == 2 && $r['TotalQty'] > 0)) {
        ?>
        <tr>
            <td><?= $r['ShopName'] ?></td>
            <td><?= $r['Zone'] ?></td>
            <td><?= $r['SubZone'] ?></td>
            <td><?= $r['ProductName'] ?></td>
            <td>₹<?= number_format((float)($r['PurchasePrice'] ?? 0), 2) ?></td>
            <td>₹<?= number_format((float)($r['MinPrice'] ?? 0), 2) ?></td>
            <td><?= $r['SaleMonth'] ?></td>
            <td><?= $r['TotalQty'] ?></td>
            <td>₹<?= number_format((float)($r['TotalSale'] ?? 0), 2) ?></td>
        </tr>
        <?php }} ?>
    </tbody>
</table>

</div>
<?php } ?>
    </div>

    <!-- Graph Tab -->
    
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
$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        "pageLength": 50,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,   // remove auto title
                filename: 'monthly_wise_sale_report',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    // Push table down by 7 rows
                    var downrows = 7;
                    var clRow = $('row', sheet);

                    clRow.each(function() {
                        var attr = $(this).attr('r');
                        var ind = parseInt(attr);
                        $(this).attr("r", ind + downrows);
                    });

                    $('row c', sheet).each(function() {
                        var attr = $(this).attr('r');
                        var pre = attr.substring(0, 1);
                        var ind = parseInt(attr.substring(1));
                        $(this).attr("r", pre + (ind + downrows));
                    });

                    // Helper to insert new rows
                    function AddRow(index, data) {
                        var row = '<row r="' + index + '">';
                        for (var i = 0; i < data.length; i++) {
                            row += '<c t="inlineStr" r="' + data[i].cell + index + '" s="2">';
                            row += '<is><t>' + data[i].text + '</t></is>';
                            row += '</c>';
                        }
                        row += '</row>';
                        return row;
                    }

                    // Custom headings
                    var title1    = AddRow(1, [{ cell: 'A', text: 'Maha Chai Pvt. Ltd.' }]);
                    var title2    = AddRow(2, [{ cell: 'A', text: 'Monthly Wise Sale Report' }]);
                    var zone      = AddRow(3, [{ cell: 'A', text: 'Zone: <?= ($filterZoneId == "all" ? "All" : $filterZoneId); ?>' }]);
                    var franchise = AddRow(4, [{ cell: 'A', text: 'Franchise: <?= ($filterFrId == "all" ? "All" : $filterFrId); ?>' }]);
                    var period    = AddRow(5, [{ cell: 'A', text: 'Period: <?= !empty($filterFromDate) ? date("d/m/Y", strtotime($filterFromDate)) : ""; ?> to <?= !empty($filterToDate) ? date("d/m/Y", strtotime($filterToDate)) : ""; ?>' }]);
                    var generated = AddRow(6, [{ cell: 'A', text: 'Generated on: <?= date("d/m/Y h:i A"); ?>' }]);
                    var blankRow  = AddRow(7, [{ cell: 'A', text: '' }]);

                    // Insert rows at top
                    sheet.childNodes[0].childNodes[1].innerHTML =
                        title1 + title2 + zone + franchise + period + generated + blankRow + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            }
        ]
    });
});
</script>

</body>
</html>
