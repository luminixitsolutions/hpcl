<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Franchise-Report-2025";
$Page = "Product-Stock-Report-2-2025";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Stock List</title>
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
<h4 class="font-weight-bold py-3 mb-0">Product Stock Report
</h4>

<div class="card" style="padding: 10px;">
       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    
 
    <div class="form-group col-md-4">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="FrId" id="FrId">
           <option selected=""  value="all">All</option>
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
                                            <label class="form-label">Zone </label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" onchange="getSubZone(this.value)">
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
                                                <?php $sql = "SELECT * FROM tbl_sub_zone WHERE CatId='".$_POST["ZoneId"]."' AND Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["SubZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off" required>
</div>

<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
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
                <th>#</th>
                <th>Franchise Name</th>
                <th>Zone</th>
                <th>Sub Zone</th>
                <th>Product Name</th>
                <th>Category Name</th>

                <th></th>
                <th>Opening Stock</th>
                <th></th>

                <th></th>
                <th>Purchase</th>
                <th></th>

                <th></th>
                <th>Sale</th>
                <th></th>

                <th></th>
                <th>Closing Stock</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th></th><th></th><th></th><th></th><th></th><th></th>
                <th>Qty</th><th>Rate</th><th>Amount</th>
                <th>Qty</th><th>Rate</th><th>Amount</th>
                <th>Qty</th><th>Rate</th><th>Amount</th>
                <th>Qty</th><th>Rate</th><th>Amount</th>
            </tr>

<?php 
$i = 1;
//$BillSoftFrId = $_REQUEST["FrId"] ?? '';
$zoneFilter   = $_REQUEST['ZoneId'] ?? '';
$subZoneFilter= $_REQUEST['SubZoneId'] ?? '';
$fridFilter   = $_POST['FrId'] ?? '';
$fromDate     = $_REQUEST['FromDate'] ?? '';
$toDate       = $_REQUEST['ToDate'] ?? '';

// =============================
// Common WHERE filters function
// =============================
function buildFilters($alias = 'tc') {
    global $fridFilter, $zoneFilter, $subZoneFilter;
    $filters = "";
    
    if ($fridFilter && $fridFilter != 'all') {
        $filters .= " AND $alias.FrId = '$fridFilter' ";
    }
    if ($zoneFilter && $zoneFilter != 'all') {
        $filters .= " AND tu.ZoneId = '$zoneFilter' ";
    }
    if ($subZoneFilter && $subZoneFilter != 'all') {
        $filters .= " AND tu.SubZoneId = '$subZoneFilter' ";
    }
    return $filters;
}

// =============================
// MAIN PRODUCTS (ProdType=0)
// =============================
$sql = "SELECT tu.ShopName,tu.ZoneId,tu.SubZoneId,
               p.id AS ProdId,p.ProductName,tcc.Name As CatName,
               p.MinPrice,p.PurchasePrice,tcc2.PurchasePrice AS MainPurchasePrice
        FROM tbl_cust_products_2025 p
        INNER JOIN tbl_cust_category_2025 tcc ON p.CatId=tcc.id 
        INNER JOIN tbl_cust_products2 tcc2 ON tcc2.id=p.ProdId
        INNER JOIN tbl_users tu ON p.CreatedBy=tu.id
        WHERE p.ProdType=0 
          AND p.ProdType2 IN (1,3)
          AND p.delete_flag=0 
          AND p.checkstatus=1";
if ($fridFilter && $fridFilter != 'all') {
        $sql .= " AND p.CreatedBy = '$fridFilter' ";
    } 
     if ($zoneFilter && $zoneFilter != 'all') {
        $sql .= " AND tu.ZoneId = '$zoneFilter' ";
    }
    if ($subZoneFilter && $subZoneFilter != 'all') {
        $sql .= " AND tu.SubZoneId = '$subZoneFilter' ";
    }
//$sql .= buildFilters('p');
//echo $sql;
$products = getList($sql);

foreach($products as $result){
    $ZoneName    = getRecord("SELECT Name FROM tbl_zone WHERE id='".$result['ZoneId']."'")['Name'] ?? '';
    $SubZoneName = getRecord("SELECT Name FROM tbl_sub_zone WHERE id='".$result['SubZoneId']."'")['Name'] ?? '';

    // Opening Stock
    $sql3 = "SELECT IFNULL(SUM(CASE WHEN tc.Status='Cr' THEN tc.Qty ELSE 0 END),0) -
                    IFNULL(SUM(CASE WHEN tc.Status='Dr' THEN tc.Qty ELSE 0 END),0) AS balqty
             FROM tbl_cust_prod_stock_2025 tc
             INNER JOIN tbl_users tu ON tc.FrId=tu.id
             WHERE tc.ProdId='".$result['ProdId']."' AND tc.ProdType=0";

    $sql3 .= buildFilters('tc');
    if (!empty($fromDate)) $sql3 .= " AND tc.StockDate < '$fromDate' ";
    $row3 = getRecord($sql3);

    // Transactions in Date Range
    $sql2 = "SELECT IFNULL(SUM(CASE WHEN tc.Status='Cr' THEN tc.Qty ELSE 0 END),0) -
                    IFNULL(SUM(CASE WHEN tc.Status='Dr' THEN tc.Qty ELSE 0 END),0) AS balqty
             FROM tbl_cust_prod_stock_2025 tc
             INNER JOIN tbl_users tu ON tc.FrId=tu.id
             WHERE tc.ProdId='".$result['ProdId']."' AND tc.ProdType=0";

    $sql2 .= buildFilters('tc');
    if (!empty($fromDate)) $sql2 .= " AND tc.StockDate >= '$fromDate' ";
    if (!empty($toDate))   $sql2 .= " AND tc.StockDate <= '$toDate' ";
    $row2 = getRecord($sql2);

    // Purchase (Cr)
    $sql4 = "SELECT IFNULL(SUM(tc.Qty),0) AS PurchaseQty
             FROM tbl_cust_prod_stock_2025 tc
             INNER JOIN tbl_users tu ON tc.FrId=tu.id
             WHERE tc.ProdId='".$result['ProdId']."' AND tc.Status='Cr' AND tc.ProdType=0";

    $sql4 .= buildFilters('tc');
    if (!empty($fromDate)) $sql4 .= " AND tc.StockDate >= '$fromDate' ";
    if (!empty($toDate))   $sql4 .= " AND tc.StockDate <= '$toDate' ";
    $row4 = getRecord($sql4);

    // Sale (Dr)
    $sql5 = "SELECT IFNULL(SUM(tc.Qty),0) AS SaleQty
             FROM tbl_cust_prod_stock_2025 tc
             INNER JOIN tbl_users tu ON tc.FrId=tu.id
             WHERE tc.ProdId='".$result['ProdId']."' AND tc.Status='Dr' AND tc.ProdType=0";

    $sql5 .= buildFilters('tc');
    if (!empty($fromDate)) $sql5 .= " AND tc.StockDate >= '$fromDate' ";
    if (!empty($toDate))   $sql5 .= " AND tc.StockDate <= '$toDate' ";
    $row5 = getRecord($sql5);

    $opening = $row3['balqty'];
    $purchase= $row4['PurchaseQty'];
    $sale    = $row5['SaleQty'];
    $closing = $opening + $purchase - $sale;
?>
    <tr>
        <td><?= $i; ?></td>
        <td><?= $result['ShopName']; ?></td>
        <td><?= $ZoneName; ?></td>
        <td><?= $SubZoneName; ?></td>
        <td><?= $result['ProductName']; ?></td>
        <td><?= $result['CatName']; ?></td>

        <td><?= $opening; ?></td>
        <td><?= $result['MinPrice']; ?></td>
        <td><?= $opening * $result['MinPrice']; ?></td>

        <td><?= $purchase; ?></td>
        <td><?= $result['MainPurchasePrice']; ?></td>
        <td><?= $purchase * $result['MainPurchasePrice']; ?></td>

        <td><?= $sale; ?></td>
        <td><?= $result['MinPrice']; ?></td>
        <td><?= $sale * $result['MinPrice']; ?></td>

        <td><?= $closing; ?></td>
        <td><?= $result['MainPurchasePrice']; ?></td>
        <td><?= $closing * $result['MainPurchasePrice']; ?></td>
    </tr>
<?php $i++; } ?>

<!-- =========================
     RAW PRODUCTS (ProdType=1)
     ========================= -->
<?php 
$sql33 = "SELECT AllocateRawProd FROM tbl_users_bill WHERE id='$BillSoftFrId'";
$AllocateRawProd = getRecord($sql33)['AllocateRawProd'] ?? '';

if (!empty($AllocateRawProd)) {
    $sql = "SELECT tu.ShopName,tu.ZoneId,tu.SubZoneId,
                   p.id AS ProdId,p.ProductName,tcc.Name As CatName
            FROM tbl_cust_products2 p
            INNER JOIN tbl_users tu ON p.CreatedBy=tu.id
            INNER JOIN tbl_cust_category_2025 tcc ON p.CatId=tcc.id
            WHERE p.ProdType=1 AND p.id IN ($AllocateRawProd)";

    $rawProducts = getList($sql);

    foreach($rawProducts as $result){
        $ZoneName    = getRecord("SELECT Name FROM tbl_zone WHERE id='".$result['ZoneId']."'")['Name'] ?? '';
        $SubZoneName = getRecord("SELECT Name FROM tbl_sub_zone WHERE id='".$result['SubZoneId']."'")['Name'] ?? '';

        $unitRow = getRecord("SELECT tc.Unit2 FROM tbl_cust_prod_stock_2025 tc 
                              INNER JOIN tbl_users tu ON tc.FrId=tu.id
                              WHERE tc.ProdId='".$result['ProdId']."' AND tc.ProdType=1 AND tc.Unit2!=''
                              ".buildFilters('tc')." LIMIT 1");
        $unit = $unitRow['Unit2'] ?? '';

        // Opening
        $sql3 = "SELECT IFNULL(SUM(CASE WHEN tc.Status='Cr' THEN tc.Qty2 ELSE 0 END),0) -
                        IFNULL(SUM(CASE WHEN tc.Status='Dr' THEN tc.Qty2 ELSE 0 END),0) AS balqty
                 FROM tbl_cust_prod_stock_2025 tc
                 INNER JOIN tbl_users tu ON tc.FrId=tu.id
                 WHERE tc.ProdId='".$result['ProdId']."' AND tc.ProdType=1";
        $sql3 .= buildFilters('tc');
        if (!empty($fromDate)) $sql3 .= " AND tc.StockDate < '$fromDate' ";
        $row3 = getRecord($sql3);

        // Purchase
        $sql4 = "SELECT IFNULL(SUM(tc.Qty2),0) AS PurchaseQty
                 FROM tbl_cust_prod_stock_2025 tc
                 INNER JOIN tbl_users tu ON tc.FrId=tu.id
                 WHERE tc.ProdId='".$result['ProdId']."' AND tc.Status='Cr' AND tc.ProdType=1";
        $sql4 .= buildFilters('tc');
        if (!empty($fromDate)) $sql4 .= " AND tc.StockDate >= '$fromDate' ";
        if (!empty($toDate))   $sql4 .= " AND tc.StockDate <= '$toDate' ";
        $row4 = getRecord($sql4);

        // Sale
        $sql5 = "SELECT IFNULL(SUM(tc.Qty2),0) AS SaleQty
                 FROM tbl_cust_prod_stock_2025 tc
                 INNER JOIN tbl_users tu ON tc.FrId=tu.id
                 WHERE tc.ProdId='".$result['ProdId']."' AND tc.Status='Dr' AND tc.ProdType=1";
        $sql5 .= buildFilters('tc');
        if (!empty($fromDate)) $sql5 .= " AND tc.StockDate >= '$fromDate' ";
        if (!empty($toDate))   $sql5 .= " AND tc.StockDate <= '$toDate' ";
        $row5 = getRecord($sql5);

        $opening = $row3['balqty'];
        $purchase= $row4['PurchaseQty'];
        $sale    = $row5['SaleQty'];
        $closing = $opening + $purchase - $sale;
?>
    <tr>
        <td><?= $i; ?></td>
        <td><?= $result['ShopName']; ?></td>
        <td><?= $ZoneName; ?></td>
        <td><?= $SubZoneName; ?></td>
        <td><?= $result['ProductName']; ?></td>
        <td><?= $result['CatName']; ?></td>

        <td><?= $opening." ".$unit; ?></td>
        <td></td><td></td>

        <td><?= $purchase." ".$unit; ?></td>
        <td></td><td></td>

        <td><?= $sale." ".$unit; ?></td>
        <td></td><td></td>

        <td><?= $closing." ".$unit; ?></td>
        <td></td><td></td>
    </tr>
<?php $i++; } } ?>
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
</script>
</body>
</html>
