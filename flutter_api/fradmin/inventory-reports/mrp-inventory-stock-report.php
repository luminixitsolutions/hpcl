<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $FrId = $_REQUEST['user_id'] ?? '';
    $CatId = $_REQUEST['CatId'] ?? '';

    if (empty($FrId)) {
        throw new Exception("FrId is required");
    }

    $sql = "SELECT p.id AS ProdId, p.ProductName, tcc.Name AS CatName, 
                   COALESCE(p.MinQty, 0) AS MinQty, p.PurchasePrice
            FROM tbl_cust_products_2025 p
            INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id
            WHERE p.CreatedBy = '$FrId'
              AND p.ProdType = 0
              AND p.ProdType2 IN (1, 3)
              AND p.CatId != 28
              AND p.delete_flag = 0
              AND p.checkstatus = 1";

    if (!empty($CatId) && $CatId !== 'all') {
        $sql .= " AND p.CatId = '$CatId'";
    }

    $sql .= " GROUP BY p.id ORDER BY p.ProductName ASC";

    $res = $conn->query($sql);

    $records = [];
    $srNo = 1;
    $totalBalance = 0;

    while ($row = $res->fetch_assoc()) {
        // Current period stock
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
                   GROUP BY Status
                 ) AS a";
        $row2 = getRecord($sql2);

        // Carry forward stock
        $sql21 = "SELECT COALESCE(SUM(creditqty), 0) AS creditqty, 
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
                      AND StockDate < '2025-01-28'
                    GROUP BY Status
                  ) AS a";
        $row21 = getRecord($sql21);

        $finalBalance = $row21['balqty'] + $row2['balqty'];
        $amount = $finalBalance * $row['PurchasePrice'];
        $totalBalance += $finalBalance;

        $records[] = [
            "SrNo" => $srNo++,
            "ProdId" => $row['ProdId'],
            "ProductName" => $row['ProductName'],
            "CategoryName" => $row['CatName'],
            "PurchasePrice" => (float) $row['PurchasePrice'],
            "MinQty" => (float) $row['MinQty'],
            "CarryForward" => (float) $row21['balqty'],
            "Credit" => (float) $row2['creditqty'],
            "Debit" => (float) $row2['debitqty'],
            "Balance" => (float) $finalBalance,
            "Amount" => (float) $amount
        ];
    }

    echo json_encode([
        "status" => "success",
        "records" => $records,
        "totalBalance" => $totalBalance
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch records: " . $e->getMessage()
    ]);
}
?>
