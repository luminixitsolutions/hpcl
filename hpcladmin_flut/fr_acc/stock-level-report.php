<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report-2025";
$Page = "Product-Stock-Report-2025";
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
<h4 class="font-weight-bold py-3 mb-0">Stock Level Report
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Category Name</th>
            <th>Purchase Price</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Balance</th>
            <th>Yesterday Sell</th>
            <th>Sell In Days</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        $productList = [];

        // Change this value to limit the number of records (0 means show all)
        $limitRecords = 0; // Change to 6 to show only top 6, 0 to show all

        $sql = "SELECT p.BarcodeNo, p.CreatedBy AS FrId, p.id AS ProdId, p.ProductName, 
                       tcc.Name AS CatName, COALESCE(p.MinQty, 0) AS MinQty, 
                       p.PurchasePrice, p.MinPrice 
                FROM tbl_cust_products_2025 p 
                INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                WHERE p.CreatedBy = '$BillSoftFrId' 
                AND p.ProdType = 0 
                AND p.ProdType2 IN (1,3) 
                AND p.CatId != 28 
                AND p.delete_flag = 0 
                AND p.checkstatus = 1 
                GROUP BY p.id 
                ORDER BY p.ProductName ASC";

        $res = $conn->query($sql);
        
        while ($row = $res->fetch_assoc()) {
            $sql2 = "SELECT COALESCE(SUM(creditqty), 0) AS creditqty, 
                            COALESCE(SUM(debitqty), 0) AS debitqty, 
                            COALESCE(SUM(creditqty) - SUM(debitqty), 0) AS balqty 
                     FROM (
                        SELECT 
                            (CASE WHEN Status='Dr' THEN SUM(Qty) ELSE '0' END) as debitqty, 
                            (CASE WHEN Status='Cr' THEN SUM(Qty) ELSE '0' END) as creditqty 
                        FROM `tbl_cust_prod_stock_2025` 
                        WHERE ProdId='" . $row['ProdId'] . "' 
                        AND ProdType=0 
                        AND FrId='$BillSoftFrId' 
                        AND StockDate >= '2025-01-28' 
                        AND StockDate <= '" . date('Y-m-d') . "' 
                        GROUP BY Status
                    ) as a";
            $row2 = getRecord($sql2);

            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $sql3 = "SELECT COALESCE(SUM(Qty), 0) AS sellqty 
                     FROM tbl_customer_invoice_details_2025 
                     WHERE FrId='$BillSoftFrId' 
                     AND ProdId='" . $row['ProdId'] . "' 
                     AND CreatedDate='$yesterday'";
            $row3 = getRecord($sql3);

            $dailySale = $row3['sellqty'];
            
            // Prevent division by zero
            if ($dailySale > 0) {
                $daysLeft = ceil($row2['balqty'] / $dailySale);
            } else {
                $daysLeft = INF; // If no sales, set infinite days
            }

            if ($row2['balqty'] > 0 && $daysLeft != INF) {
                $productList[] = [
                    'ProdId' => $row['ProdId'],
                    'ProductName' => $row['ProductName'],
                    'CatName' => $row['CatName'],
                    'PurchasePrice' => $row['PurchasePrice'],
                    'creditqty' => $row2['creditqty'],
                    'debitqty' => $row2['debitqty'],
                    'balqty' => $row2['balqty'],
                    'dailySale' => $dailySale,
                    'daysLeft' => $daysLeft
                ];
            }
        }

        // Sort by lowest "daysLeft"
        usort($productList, function($a, $b) {
            return $a['daysLeft'] <=> $b['daysLeft'];
        });

        // Apply limit (0 means show all)
        if ($limitRecords > 0) {
            $productList = array_slice($productList, 0, $limitRecords);
        }

        foreach ($productList as $index => $product) {
        ?>
        <tr>
            <td><?php echo ($index + 1); ?></td>
            <td><?php echo $product['ProdId']; ?></td>
            <td><?php echo $product['ProductName']; ?></td>
            <td><?php echo $product['CatName']; ?></td>
            <td><?php echo $product['PurchasePrice']; ?></td>
            <td><?php echo $product['creditqty']; ?></td>
            <td><?php echo $product['debitqty']; ?></td>
            <td><?php echo $product['balqty']; ?></td>
            <td><?php echo $product['dailySale']; ?></td>
            <td><?php echo $product['daysLeft']; ?></td>
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
 
    	$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        order: [[9, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
