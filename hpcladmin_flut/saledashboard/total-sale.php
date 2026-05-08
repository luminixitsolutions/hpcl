<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Employee";
$Page = "View-Employee";
$user_id = $_SESSION['Admin']['id'] ?? 0;
if (!$user_id) die('Invalid session');

// Get user's assigned zones & subzones
$row = getRecord("SELECT zone, subzone FROM tbl_users_bill WHERE id = '$user_id'");
$zoneids = trim($row['zone'] ?? '');
$subzoneids = trim($row['subzone'] ?? '');

// Sanitize
$zoneids = implode(',', array_filter(array_map('intval', explode(',', $zoneids))));
$subzoneids = implode(',', array_filter(array_map('intval', explode(',', $subzoneids))));

// ✅ Build WHERE conditions dynamically
$where = ["Roll=5"];
if (!empty($zoneids)) $where[] = "ZoneId IN($zoneids)";
if (!empty($subzoneids)) $where[] = "SubZoneId IN($subzoneids)";
$whereSql = implode(' AND ', $where);

// ✅ Final SQL
$sql = "SELECT GROUP_CONCAT(id) AS FrId FROM tbl_users WHERE $whereSql";

// ✅ Fetch data
$row77 = getRecord($sql);
$frids = $row77['FrId'] ?? '';
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
<link rel="stylesheet" href="../assets/fonts/fontawesome.css">
<link rel="stylesheet" href="../assets/fonts/ionicons.css">
<link rel="stylesheet" href="../assets/fonts/linearicons.css">
<link rel="stylesheet" href="../assets/fonts/open-iconic.css">
<link rel="stylesheet" href="../assets/fonts/pe-icon-7-stroke.css">
<link rel="stylesheet" href="../assets/fonts/feather.css">
<link rel="stylesheet" href="../assets/css/bootstrap-material.css">
<link rel="stylesheet" href="../assets/css/shreerang-material.css">
<link rel="stylesheet" href="../assets/css/uikit.css">
<link rel="stylesheet" href="../assets/libs/datatables/datatables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../assets/libs/select2/select2.css">
<link rel="stylesheet" href="../assets/libs/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="../assets/libs/bootstrap-select/bootstrap-select.css">
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

 <?php //include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Total Orders
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
                                                <?php $sql = "SELECT * FROM tbl_zone WHERE Status=1 AND id IN($zoneids)";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_REQUEST["ZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Sub Zone </label>
                                            <select class="form-control" id="SubZoneId" name="SubZoneId" required="">
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_sub_zone WHERE Status=1 AND id IN($subzoneids)";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_REQUEST["SubZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-4">
                                            <label class="form-label">Franchise</label>
                                            <select class="select2-demo form-control" name="UserId" id="UserId">
                                                <option selected="" value="all">All</option>
                                                <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(5) AND ShopName!='' AND id IN($frids)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
                                                <option <?php if($_REQUEST['UserId']==$result['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
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
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
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
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
         <tr>
            <th>Outlet Name</th>
            <th>Zone</th>
            <th>Sub Zone</th>
            <!--<th>Franchise</th>-->
            <th>Total Sales (₹)</th>
            <th>Discount (₹)</th>
            <th>Invoices</th>
            <th>QSR Sales</th>
            <th>Pack Food Sales</th>
            <th>Cross Sales</th>
            <th>Cash (₹)</th>
            <th>UPI/Online (₹)</th>
            <th>Credit (₹)</th>
            <th>Zomato/Swiggy (₹)</th>
        </tr>
    </thead>

    <tbody>
        <?php
// Initialize filters
$extraFilter  = "";
$extraFilter2 = "";
$from = $_REQUEST['FromDate'];
$to = $_REQUEST['ToDate'];

// Apply User (Franchise) filter
if (!empty($_REQUEST['UserId']) && $_REQUEST['UserId'] != 'all') {
    $frId = $_REQUEST['UserId'];
    $extraFilter  .= " AND ci.FrId = '$frId' ";
    $extraFilter2 .= " AND tc.FrId = '$frId' ";
}

// Apply Zone filter
if (!empty($_REQUEST['ZoneId']) && $_REQUEST['ZoneId'] != 'all') {
    $zoneId = $_REQUEST['ZoneId'];
    $extraFilter  .= " AND tu.ZoneId = '$zoneId' ";
    $extraFilter2 .= " AND tu.ZoneId = '$zoneId' ";
}

// Apply Sub-Zone filter
if (!empty($_REQUEST['SubZoneId']) && $_REQUEST['SubZoneId'] != 'all') {
    $subZoneId = $_REQUEST['SubZoneId'];
    $extraFilter  .= " AND tu.SubZoneId = '$subZoneId' ";
    $extraFilter2 .= " AND tu.SubZoneId = '$subZoneId' ";
}

// Date filters
$dateFilter     = " AND ci.InvoiceDate BETWEEN '$from' AND '$to' ";
$prodDateFilter = " AND tc.CreatedDate BETWEEN '$from' AND '$to' ";

// ----------------------------------------------------------
// Main Query
// ----------------------------------------------------------
$sqlTable = "
SELECT 
    tu.ShopName,
    (SELECT Name FROM tbl_zone z WHERE z.id = tu.ZoneId) AS ZoneName,
    (SELECT Name FROM tbl_sub_zone sz WHERE sz.id = tu.SubZoneId) AS SubZoneName,
    tu.id AS FrId,

    -- Total Sales, Discount & Invoice Count
    COALESCE(SUM(ci.NetAmount), 0) AS Sales,
    COALESCE(SUM(ci.Discount), 0) AS Discount,
    COUNT(ci.Unqid) AS Invoices,

    -- QSR Sales
    (
        SELECT COALESCE(SUM(tc.Qty), 0)
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id
        WHERE tp.ProdType2 = 2 
          AND tp.ProdType = 0 
          AND tp.CrossSell != 1 
          AND tc.FrId = tu.id AND tc.FrId IN($frids)
          $prodDateFilter
          $extraFilter2
    ) AS QsrSales,

    -- Pack Food Sales
    (
        SELECT COALESCE(SUM(tc.Qty), 0)
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id
        WHERE tp.ProdType2 = 1 
          AND tp.ProdType = 0 
          AND tp.CrossSell != 1 
          AND tc.FrId = tu.id AND tc.FrId IN($frids)
          $prodDateFilter
          $extraFilter2
    ) AS PackSales,

    -- Cross Sales
    (
        SELECT COALESCE(SUM(tc.Qty), 0)
        FROM tbl_customer_invoice_details_2025 tc
        INNER JOIN tbl_cust_products_2025 tp ON tc.ProdId = tp.id
        WHERE tp.CrossSell = 1 
          AND tc.FrId = tu.id AND tc.FrId IN($frids)
          $prodDateFilter
          $extraFilter2
    ) AS CrossSales,

    -- Cash Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id 
          AND ci.PayType = 'Cash' AND ci.FrId IN($frids)
          $dateFilter
          $extraFilter
    ) AS CashSales,

    -- UPI / Online Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id AND ci.FrId IN($frids)
          AND ci.PayType IN ('Online', 'UPI', 'Paytm', 'Phone Pay', 'Website online')
          $dateFilter
          $extraFilter
    ) AS UpiSales,

    -- Credit Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id AND ci.FrId IN($frids)
          AND ci.PayType = 'Borrowing'
          $dateFilter
          $extraFilter
    ) AS CreditSales,

    -- Zomato / Swiggy Sales
    (
        SELECT COALESCE(SUM(NetAmount), 0)
        FROM (
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice
            UNION ALL
            SELECT FrId, NetAmount, PayType, InvoiceDate FROM tbl_customer_invoice_2025
        ) ci
        WHERE ci.FrId = tu.id AND ci.FrId IN($frids)
          AND ci.PayType IN ('Zomato', 'Swiggy')
          $dateFilter
          $extraFilter
    ) AS ZomatoSales

FROM (
    SELECT FrId, NetAmount, Discount, Unqid, InvoiceDate 
    FROM tbl_customer_invoice
    UNION ALL
    SELECT FrId, NetAmount, Discount, Unqid, InvoiceDate 
    FROM tbl_customer_invoice_2025
) ci
INNER JOIN tbl_users tu ON ci.FrId = tu.id
WHERE 1 = 1 AND ci.FrId IN($frids)
  $dateFilter
  $extraFilter
GROUP BY tu.id
ORDER BY Sales DESC
";


// Uncomment to debug
//echo "<pre>$sqlTable</pre>";

// Fetch data
$rows = getList($sqlTable);

// Output rows
foreach ($rows as $row) {
?>


        <tr>
            <!-- Photo -->
           <td><?php echo $row['ShopName'];?></td>
                <td><?php echo $row['ZoneName']; ?></td>
                
              <td><?php echo $row['SubZoneName']; ?></td>
               
                <!--<td><?php echo $row['FrId']; ?></td>-->
                <td><?php echo number_format($row['Sales'],2); ?></td>
                <td><?php echo number_format($row['Discount'],2); ?></td>
                <td><?php echo $row['Invoices']; ?></td>
                <td><?php echo $row['QsrSales']; ?></td>
                <td><?php echo $row['PackSales']; ?></td>
                <td><?php echo $row['CrossSales']; ?></td>
                <td><?php echo number_format($row['CashSales'],2); ?></td>
                <td><?php echo number_format($row['UpiSales'],2); ?></td>
                <td><?php echo number_format($row['CreditSales'],2); ?></td>
                <td><?php echo number_format($row['ZomatoSales'],2); ?></td>
        </tr>

        <?php } ?>
        
        
       
    </tbody>
</table>

</div>
</div>
</div>


<?php include_once '../footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="../assets/js/pace.js"></script>
<script src="../assets/libs/popper/popper.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/sidenav.js"></script>
<script src="../assets/js/layout-helpers.js"></script>
<script src="../assets/js/material-ripple.js"></script>
<script src="../assets/libs/datatables/datatables.js"></script>
<script src="../assets/js/pages/tables_datatables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="../assets/libs/bootstrap-select/bootstrap-select.js"></script>
<script src="../assets/libs/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="../assets/js/pages/forms_selects.js"></script>
<script src="../assets/libs/select2/select2.js"></script>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        order: [[4, 'desc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null, // disable default title
                filename: 'total_order_list',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var downrows = 5;

                    // shift existing rows down
                    var clRow = $('row', sheet);
                    clRow.each(function() {
                        var ind = parseInt($(this).attr('r'));
                        $(this).attr("r", ind + downrows);
                    });

                    $('row c', sheet).each(function() {
                        var pre = $(this).attr('r').substring(0, 1);
                        var ind = parseInt($(this).attr('r').substring(1));
                        $(this).attr("r", pre + (ind + downrows));
                    });

                    // helper function to add rows
                    function AddRow(index, data) {
                        var row = '<row r="' + index + '">';
                        for (var i = 0; i < data.length; i++) {
                            row += '<c t="inlineStr" r="' + data[i].cell + index + '" s="2">';
                            row += '<is><t>' + data[i].text + '</t></is></c>';
                        }
                        row += '</row>';
                        return row;
                    }

                    // PHP variables or fallback defaults
                    var companyName = '<?php echo isset($companyName) ? $companyName : "Maha Chai Pvt. Ltd."; ?>';
                    var reportTitle = '<?php echo isset($reportTitle) ? $reportTitle : "Category Wise Employee List"; ?>';
                    var period = '<?php echo isset($period) ? $period : "As on " . date("d M Y"); ?>';
                    var generated = '<?php echo isset($generated) ? $generated : "Generated on: " . date("d M Y h:i A"); ?>';

                    // add header rows
                    var title1   = AddRow(1, [{ cell: 'A', text: companyName }]);
                    var title2   = AddRow(2, [{ cell: 'A', text: reportTitle }]);
                    var periodR  = AddRow(3, [{ cell: 'A', text: period }]);
                    var genRow   = AddRow(4, [{ cell: 'A', text: generated }]);
                    var blankRow = AddRow(5, [{ cell: 'A', text: '' }]);

                    // inject new rows at top
                    sheet.childNodes[0].childNodes[1].innerHTML =
                        title1 + title2 + periodR + genRow + blankRow + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            }
        ]
    });
});

</script>
</body>
</html>
