<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage = "Dashboard";
$Page = "Dashboard";
if ($_REQUEST['id'] == '') {
    $_SESSION['fr_admin'] = $_SESSION['fr_admin'];
} else {
    $_SESSION['fr_admin'] = $_REQUEST['id'];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
      <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/morris/morris.css">
</head>

<body>
    <style type="text/css">
        .mr_5 {
            margin-right: 3rem !important;
        }
    </style>

    <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php';
            include_once 'sidebar.php'; ?>


            <div class="layout-container">




                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

                        <?php
                        function countval($val, $BillSoftFrId)
                        {
                            global $conn;
                            if ($val == 'raw') {
                                $sql = "SELECT count(*) As result FROM tbl_raw_products";
                            }
                            if ($val == 'vendor') {
                                $sql = "SELECT count(*) As result FROM tbl_users WHERE Roll=3";
                            }
                            if ($val == 'vendor_inv') {
                                $sql = "SELECT count(*) As result FROM tbl_invoice";
                            }
                            if ($val == 'use_raw') {
                                $sql = "SELECT count(*) As result FROM tbl_use_raw_stock";
                            }
                            if ($val == 'fr_prod') {
                                $sql = "SELECT count(*) As result FROM products WHERE ProdFor IN(1,3)";
                            }
                            if ($val == 'prod_stock') {
                                $sql = "SELECT count(*) As result FROM tbl_product_stocks";
                            }
                            if ($val == 'prod_cat') {
                                $sql = "SELECT count(*) As result FROM tbl_cust_category WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0";
                            }
                            if ($val == 'prod_cust') {
                                $sql = "SELECT count(*) As result FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND checkstatus=1 AND delete_flag=0";
                            }
                            if ($val == 'fr_inv') {
                                $sql = "SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=1";
                            }
                            if ($val == 'cust_inv') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$BillSoftFrId' AND delete_flag=0 UNION ALL SELECT count(*) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$BillSoftFrId' AND delete_flag=0) as a";
                            }
                            if ($val == 'today_cust_inv') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT count(*) As result FROM tbl_customer_invoice WHERE Roll=2 AND InvoiceDate='" . date('Y-m-d') . "' AND FrId='$BillSoftFrId' AND delete_flag=0 UNION ALL SELECT count(*) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND InvoiceDate='" . date('Y-m-d') . "' AND FrId='$BillSoftFrId' AND delete_flag=0) as a";
                            }
                            if ($val == 'expenses') {
                                $sql = "SELECT count(*) As result FROM tbl_expenses";
                            }
                            if ($val == 'ved_payment') {
                                $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PW' AND CrDr='dr' AND Flag='Invoice' AND AccRoll='Vendor'";
                            }
                            if ($val == 'fr_payment') {
                                $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PR' AND CrDr='cr' AND Flag='Franchise-Invoice' AND AccRoll='Franchise'";
                            }
                            if ($val == 'cust_payment') {
                                $sql = "SELECT SUM(Amount) As result FROM tbl_general_ledger WHERE Type='PR' AND CrDr='cr' AND Flag='Customer-Invoice' AND AccRoll='Customer' AND BillSoftFrId='$BillSoftFrId'";
                            }
                            if ($val == 'cash_payment') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$BillSoftFrId' AND PayType='Cash' AND InvoiceDate='" . date('Y-m-d') . "' UNION ALL SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND FrId='$BillSoftFrId' AND PayType='Cash' AND InvoiceDate='" . date('Y-m-d') . "') as a";
                            }
                            if ($val == 'phonepay_payment') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND PayType='Phone Pay' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "' UNION ALL SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND PayType='Phone Pay' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "') as a";
                            }
                            if ($val == 'paytm_payment') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND PayType='Paytm' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "' UNION ALL SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND PayType='Paytm' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "') as a";
                            }
                            if ($val == 'googlepay_payment') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND PayType='Google Pay' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "' UNION ALL SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND PayType='Google Pay' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "') as a";
                            }
                            if ($val == 'otherupi_payment') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND PayType='Other UPI' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "' UNION ALL SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND PayType='Other UPI' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "') as a";
                            }
                            if ($val == 'borrow_payment') {
                                $sql = "SELECT SUM(result) As result FROM (SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND PayType='Borrowing' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "' UNION ALL SELECT SUM(NetAmount) As result FROM tbl_customer_invoice_2025 WHERE Roll=2 AND PayType='Other UPI' AND FrId='$BillSoftFrId' AND InvoiceDate='" . date('Y-m-d') . "') as a";
                            }
                            $res2 = $conn->query($sql);
                            $row2 = $res2->fetch_assoc();
                            return $row2['result'];
                        }


                        $bmi_range = $_REQUEST['uid'];
                        ?>



                        <div class="row">
                            <div class="col-lg-8">
                                 <div class="row">
                                <div class="col-lg-3">
                                    <a href="view-today-orders.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Today Sell</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT * FROM tbl_customer_invoice_2025 WHERE InvoiceDate='" . date('Y-m-d') . "' AND FrId='$BillSoftFrId'";
                                                                                echo $rncnt4 = getRow($sql4);
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="view-today-orders.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Today Sell Amount</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE InvoiceDate='" . date('Y-m-d') . "' AND FrId='$BillSoftFrId'";
                                                                                $row4 = getRecord($sql4);
                                                                                echo "₹".$row4['NetAmount'];
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="view-today-orders.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Today Cash Amount</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE InvoiceDate='" . date('Y-m-d') . "' AND FrId='$BillSoftFrId' AND PayType='Cash'";
                                                                                $row4 = getRecord($sql4);
                                                                                echo "₹".$row4['NetAmount'];
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="view-today-orders.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Today UPI Amount</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE InvoiceDate='" . date('Y-m-d') . "' AND FrId='$BillSoftFrId' AND PayType!='Cash'";
                                                                                $row4 = getRecord($sql4);
                                                                                echo "₹".$row4['NetAmount'];
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="view-customer-products-2025.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Selling Product</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT * FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0 AND checkstatus=1 AND ProdType=0 AND ProdType2!=3";
                                                                                echo $rncnt4 = getRow($sql4);
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="view-raw-products-2025.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Raw/Making Product</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT * FROM tbl_cust_products2 WHERE id IN ($AllocateRawProd) AND ProdType='1'";
                                                                                echo $rncnt4 = getRow($sql4);
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="view-other-products-2025.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Other Product</h6>
                                                        <div class="text-large"><?php
                                                                                $sql4 = "SELECT * FROM tbl_cust_products_2025 WHERE CreatedBy='$BillSoftFrId' AND delete_flag=0 AND checkstatus=1 AND ProdType=0 AND ProdType2=3";
                                                                                echo $rncnt4 = getRow($sql4);
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-3">
                                    <a href="min-inventory-stock-report.php">
                                        <div class="card mb-4 bg-pattern-3-dark">
                                            <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0" style="color: black;">Min Qty MRP Products</h6>
                                                        <div class="text-large"><?php
                                                                        $sql = "SELECT COUNT(*) AS Low_Stock_Count FROM 
                                                                        ( SELECT COALESCE(p.MinQty, 0) AS MinQty, 
                                                                        COALESCE(SUM(CASE WHEN s.Status = 'Cr' THEN s.Qty ELSE 0 END) - SUM(CASE WHEN s.Status = 'Dr' THEN s.Qty ELSE 0 END), 0) AS balqty 
                                                                        FROM tbl_cust_products_2025 p INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                                                                        LEFT JOIN tbl_cust_prod_stock_2025 s ON p.id = s.ProdId AND s.ProdType = 0 AND s.FrId = '$BillSoftFrId' 
                                                                        AND s.StockDate BETWEEN '2025-01-28' AND '".date('Y-m-d')."' WHERE p.CreatedBy = '$BillSoftFrId' AND p.ProdType = 0 AND p.ProdType2 IN (1,3) 
                                                                        AND p.CatId != 28 AND p.delete_flag = 0 AND p.checkstatus = 1 GROUP BY p.id ORDER BY p.ProductName ASC) as a WHERE balqty < MinQty";
                                                                        $row = getRecord($sql);
                                                                       
                                                                        echo $row['Low_Stock_Count'];
                                                                                ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                
                                <div class="col-lg-6">
                                <div class="card mb-4">
                                    <h5 class="card-header">Stock Level <a href="stock-level-report.php" class="btn btn-default btn-xs md-btn-flat" style="float:right;">View All</a></h5>
                                    <div class="card-body">
                                        <?php 
        $i = 1;
        $productList = [];

        // Change this value to limit the number of records (0 means show all)
        $limitRecords = 6; // Change to 6 to show only top 6, 0 to show all

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
            if($product['daysLeft'] <=10){
                $width="20";
                $color = "danger";
            }
            if($product['daysLeft'] > 10 && $product['daysLeft'] <= 20){
                $width="40";
                $color = "warning";
            }
            if($product['daysLeft'] > 20 && $product['daysLeft'] <= 30){
                $width="60";
                $color = "info";
            }
            if($product['daysLeft'] > 30 && $product['daysLeft'] <= 40){
                $width="80";
                $color = "primary";
            }
            if($product['daysLeft'] > 40 && $product['daysLeft'] <= 60){
                $width="90";
                $color = "success";
            }
        ?>
                                        <h6 class="mb-2 mt-4"><?php echo $product['ProductName']; ?> <span class="float-right text-muted"><?php echo $product['daysLeft']; ?> Days</span></h6>
                                        <div class="progress">
                                            <div class="progress-bar bg-<?php echo $color?>" style="width:<?php echo $width?>%"></div>
                                        </div>
                                        <?php } ?>
                                       
                                    </div>
                                </div>
                                </div>
                                
                                <?php 
                            $sql = "SELECT p.id, p.ProductName, COALESCE(SUM(tcid.Qty), 0) AS Total_Sell, p.PurchasePrice, 
                            COALESCE(SUM(tcid.Total), 0) AS Sell_Amount, 
                            COALESCE(SUM(tcid.Total) - (SUM(tcid.Qty) * p.PurchasePrice), 0) AS Profit_Amount
                            FROM tbl_cust_products_2025 p
                            INNER JOIN tbl_customer_invoice_details_2025 tcid ON p.id = tcid.ProdId
                            INNER JOIN tbl_customer_invoice_2025 tci ON tci.id = tcid.InvId
                            WHERE p.CreatedBy = $BillSoftFrId 
                            AND p.checkstatus = 1 
                            AND p.delete_flag = 0 
                            AND tci.Roll = 2 
                            AND tci.FrId = '$BillSoftFrId' AND month(tci.InvoiceDate)='".date('m')."' AND year(tci.InvoiceDate)='".date('Y')."' 
                            GROUP BY p.id, p.ProductName, p.PurchasePrice
                            ORDER BY Total_Sell DESC
                            LIMIT 6";
                            $res = $conn->query($sql);
                            $i = 1;
                            ?>
                                <div class="col-lg-6">
                                 <div class="card mb-4">
                                    <h5 class="card-header">Top Selling Product In This Month 
                                    <a href="top-selling-product-report.php" class="btn btn-default btn-xs md-btn-flat" style="float:right;">Show more</a></h5>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                   <th>#</th>
                                                   <th>Product</th> 
                                                    <th>Total Sell</th>
                                                    <th>Purchase Amount</th>
                                                    <th>Sell Amount</th>
                                                    <th>Profit Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $res->fetch_assoc()) { ?>
            <tr>
               <td><?php echo $i++; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row['Total_Sell']; ?></td>
                <td>&#8377;<?php echo number_format($row['PurchasePrice'] * $row['Total_Sell'], 2); ?></td>
                <td>&#8377;<?php echo number_format($row['Sell_Amount'], 2); ?></td>
                <td>&#8377;<?php echo number_format($row['Profit_Amount'], 2); ?></td>
          
        
              
            </tr>
           <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                                
                                
                                </div>
                                
                            </div>
                            
                            <div class="col-lg-4">
                               
                                </div>
                                
                        </div>
                        
                        
                        
                        <div class="row">
                                <?php $sql = "SELECT tc.FrId, LEFT(tu.ShopName, 10) AS ShopName, COALESCE(SUM(tc.NetAmount), 0) AS TotalIncome, 
                                COALESCE(SUM(CASE WHEN tc.InvoiceDate = '".date('Y-m-d')."' THEN tc.NetAmount ELSE 0 END), 0) AS TodayIncome FROM tbl_customer_invoice_2025 AS tc 
                                INNER JOIN tbl_users_bill AS tu ON tu.id = tc.FrId WHERE tc.FrId = '$BillSoftFrId' GROUP BY tc.FrId";
                                $row = getRecord($sql);
                                $TotalIncome = $row['TotalIncome'];
                                $TodayIncome = $row['TodayIncome'];
                                ?>
                                
                                
                            <!--<div class="col-md-4">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="card-header-title mb-0">Income</h6>
                                            <div id="morrisjs-bars" style="height: 300px"></div>
                                        </div>
                                    </div>
                                </div>-->
                                
                                <?php 
                                function monthlyIncome($BillSoftFrId, $month, $year){
                                    global $conn;
                                    $sql = "SELECT COALESCE(SUM(NetAmount), 0) AS TotalNetAmount 
                                    FROM tbl_customer_invoice_2025 
                                    WHERE MONTH(InvoiceDate) = '$month' 
                                    AND YEAR(InvoiceDate) = '$year' 
                                    AND FrId = '$BillSoftFrId'";
                                    $res2 = $conn->query($sql);
	                                $row2 = $res2->fetch_assoc();
	                                return $row2['TotalNetAmount'];
                                }
                                
                       
                                  function getWeeksInMonth($year, $month) {
    $weeks = [];
    $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
    $startOfWeek = 1; // Start from the 1st day of the month
    
    while ($startOfWeek <= $totalDays) {
        $from_date = date('Y-m-d', strtotime("$year-$month-$startOfWeek"));
        
        // Find the Sunday of the week OR the last day of the month
        $endOfWeek = $startOfWeek + (7 - date('N', strtotime($from_date)));
        if ($endOfWeek > $totalDays) {
            $endOfWeek = $totalDays;
        }
        
        $to_date = date('Y-m-d', strtotime("$year-$month-$endOfWeek"));
        
        // Store the week range
        $weeks[] = ['from_date' => $from_date, 'to_date' => $to_date];

        // Move to the next week's Monday
        $startOfWeek = $endOfWeek + 1;
    }
    
    return $weeks;
}
                                
                                function weeklyIncome($BillSoftFrId, $fromdate, $todate){
                                    global $conn;
                                    
                                    $sql = "SELECT SUM(NetAmount) as total 
                                            FROM tbl_customer_invoice_2025 
                                            WHERE FrId = '$BillSoftFrId' 
                                            AND InvoiceDate BETWEEN '$fromdate' AND '$todate'";
                                            
                                    $res2 = $conn->query($sql);
                                    $row2 = $res2->fetch_assoc();
                                    
                                    return $row2['total'] ?? 0; // Return 0 if NULL
                                }
                                
                                // Example Usage:
                                $year = date('Y');
                                $month = date('m'); // March
                                
                                
                                $weeks = getWeeksInMonth($year, $month);
                                
                                $weekNumber = 1;
                                foreach ($weeks as $week) {
                                    $from_date = $week['from_date'];
                                    $to_date = $week['to_date'];
                                    
                                    // Dynamically assign to variables like $week1income, $week2income, etc.
                                    ${"week" . $weekNumber . "income"} = weeklyIncome($BillSoftFrId, $from_date, $to_date);
                                
                                    $weekNumber++;
                                }
                                
                                // Now you can access the variables as $week1income, $week2income, etc.
                                
                                // Example Output
                                $week1income;$week2income;$week3income;$week4income;$week5income;
                                if (isset($week6income)) { $week6income; } // Only if exists
                                ?>
                                
                                
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="card-header-title mb-0">Monthly Income Of <?php echo date('M-Y');?></h6>
                                            <div id="morrisjs-bars2" style="height: 300px"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="card-header-title mb-0">Weekly Income Of <?php echo date('M-Y');?></h6>
                                            <div id="morrisjs-bars3" style="height: 300px"></div>
                                        </div>
                                    </div>
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


  <!-- Core scripts -->
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/eve/eve.js"></script>
    <script src="assets/libs/raphael/raphael.js"></script>
    <script src="assets/libs/morris/morris.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
   <script>
        $(function() {
            var gridBorder = '#eeeeee';
           /* new Morris.Bar({
                element: 'morrisjs-bars',
                data: [
                    {
                        device: 'Total Sell',
                        geekbench: <?php echo round($TotalIncome,2);?>
                    },
                    {
                        device: 'Today Sell',
                        geekbench: <?php echo round($TodayIncome,2);?>
                    },
                    
                   
                    
                ],
                xkey: 'device',
                ykeys: ['geekbench'],
                labels: ['Income'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: ['#ff4a00'],
                gridLineColor: gridBorder,
                resize: true
            });*/


            new Morris.Bar({
                element: 'morrisjs-bars2',
                data: [
               
                    {
                        device: 'Jan',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,1,date('Y')),2);?>
                    },
                    {
                        device: 'Feb',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,2,date('Y')),2);?>
                    },
                    {
                        device: 'Mar',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,3,date('Y')),2);?>
                    },
                    {
                        device: 'Apr',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,4,date('Y')),2);?>
                    },
                    {
                        device: 'May',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,5,date('Y')),2);?>
                    },
                    {
                        device: 'Jun',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,6,date('Y')),2);?>
                    },
                    {
                        device: 'Jul',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,7,date('Y')),2);?>
                    },
                    {
                        device: 'Aug',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,8,date('Y')),2);?>
                    },
                    {
                        device: 'Sep',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,9,date('Y')),2);?>
                    },
                    {
                        device: 'Oct',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,10,date('Y')),2);?>
                    },
                    {
                        device: 'Nov',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,11,date('Y')),2);?>
                    },
                    {
                        device: 'Dec',
                        geekbench: <?php echo round(monthlyIncome($BillSoftFrId,12,date('Y')),2);?>
                    },
                    
                    
                ],
                xkey: 'device',
                ykeys: ['geekbench'],
                labels: ['Total Income'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: ['#ff4a00'],
                gridLineColor: gridBorder,
                resize: true
            });
            
            new Morris.Bar({
                element: 'morrisjs-bars3',
                data: [
               
                    {
                        device: 'Week 1',
                        geekbench: <?php echo round($week1income,2);?>
                    },
                    {
                        device: 'Week 2',
                        geekbench: <?php echo round($week2income,2);?>
                    },
                    {
                        device: 'Week 3',
                        geekbench: <?php echo round($week3income,2);?>
                    },
                    {
                        device: 'Week 4',
                        geekbench: <?php echo round($week4income,2);?>
                    },
                    {
                        device: 'Week 5',
                        geekbench: <?php echo round($week5income,2);?>
                    },
                    {
                        device: 'Week 6',
                        geekbench: <?php echo round($week6income,2);?>
                    },
                    
                ],
                xkey: 'device',
                ykeys: ['geekbench'],
                labels: ['Total Income'],
                barRatio: 0.4,
                xLabelAngle: 35,
                hideHover: 'auto',
                barColors: ['#ff4a00'],
                gridLineColor: gridBorder,
                resize: true
            });
          
        });
    </script>
</body>

</html>