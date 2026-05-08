<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $FrId = $_REQUEST['user_id'] ?? '';

    if (empty($FrId)) {
        throw new Exception("FrId is required");
    }

    $limitRecords = $_REQUEST['limit'] ?? 0; // optional limit (0 means no limit)

    $sql = "SELECT p.id AS ProdId, p.ProductName, tcc.Name AS CatName, 
                   COALESCE(p.MinQty, 0) AS MinQty, p.PurchasePrice
            FROM tbl_cust_products_2025 p
            INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id
            WHERE p.CreatedBy = '$FrId'
              AND p.ProdType = 0
              AND p.ProdType2 IN (1, 3)
              AND p.CatId != 28
              AND p.delete_flag = 0
              AND p.checkstatus = 1
            GROUP BY p.id
            ORDER BY p.ProductName ASC";

    $res = $conn->query($sql);
    $productList = [];

    while ($row = $res->fetch_assoc()) {
        // Get stock summary
        $sql2 = "SELECT COALESCE(SUM(creditqty), 0) AS creditqty, 
                        COALESCE(SUM(debitqty), 0) AS debitqty, 
                        COALESCE(SUM(creditqty) - SUM(debitqty), 0) AS balqty
                 FROM (
                    SELECT 
                        (CASE WHEN Status = 'Dr' THEN SUM(Qty) ELSE 0 END) AS debitqty,
                        (CASE WHEN Status = 'Cr' THEN SUM(Qty) ELSE 0 END) AS creditqty
                    FROM tbl_cust_prod_stock_2025
                    WHERE ProdId = '{$row['ProdId']}'
                      AND ProdType = 0
                      AND FrId = '$FrId'
                      AND StockDate >= '2025-01-28'
                      AND StockDate <= '" . date('Y-m-d') . "'
                    GROUP BY Status
                 ) AS a";
        $row2 = getRecord($sql2);

        // Get yesterday's sale
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $sql3 = "SELECT COALESCE(SUM(Qty), 0) AS sellqty
                 FROM tbl_customer_invoice_details_2025
                 WHERE FrId = '$FrId'
                   AND ProdId = '{$row['ProdId']}'
                   AND CreatedDate = '$yesterday'";
        $row3 = getRecord($sql3);

        $dailySale = $row3['sellqty'];
        $daysLeft = ($dailySale > 0) ? ceil($row2['balqty'] / $dailySale) : INF;

        if ($row2['balqty'] > 0 && $daysLeft !== INF) {
            $productList[] = [
                "ProdId" => $row['ProdId'],
                "ProductName" => $row['ProductName'],
                "CategoryName" => $row['CatName'],
                "PurchasePrice" => (float)$row['PurchasePrice'],
                "Credit" => (float)$row2['creditqty'],
                "Debit" => (float)$row2['debitqty'],
                "Balance" => (float)$row2['balqty'],
                "YesterdaySell" => (float)$dailySale,
                "SellInDays" => (int)$daysLeft
            ];
        }
    }

    // Sort by lowest SellInDays
    usort($productList, function($a, $b) {
        return $a['SellInDays'] <=> $b['SellInDays'];
    });

    if ($limitRecords > 0) {
        $productList = array_slice($productList, 0, $limitRecords);
    }

    echo json_encode([
        "status" => "success",
        "records" => $productList
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch records: " . $e->getMessage()
    ]);
}
?>
