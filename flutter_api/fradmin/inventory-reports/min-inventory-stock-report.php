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

    $sql = "SELECT *
            FROM (
                SELECT 
                    p.id AS ProdId,
                    COALESCE(p.MinQty, 0) AS MinQty,
                    COALESCE(SUM(CASE WHEN s.Status = 'Cr' THEN s.Qty ELSE 0 END), 0) AS creditqty,
                    COALESCE(SUM(CASE WHEN s.Status = 'Dr' THEN s.Qty ELSE 0 END), 0) AS debitqty,
                    COALESCE(SUM(CASE WHEN s.Status = 'Cr' THEN s.Qty ELSE 0 END) - SUM(CASE WHEN s.Status = 'Dr' THEN s.Qty ELSE 0 END), 0) AS balqty,
                    p.PurchasePrice,
                    tcc.Name AS CatName,
                    p.ProductName
                FROM tbl_cust_products_2025 p
                INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id
                LEFT JOIN tbl_cust_prod_stock_2025 s 
                    ON p.id = s.ProdId 
                    AND s.ProdType = 0 
                    AND s.FrId = '$FrId' 
                    AND s.StockDate BETWEEN '2025-01-28' AND '".date('Y-m-d')."'
                WHERE p.CreatedBy = '$FrId'
                  AND p.ProdType = 0
                  AND p.ProdType2 IN (1,3)
                  AND p.CatId != 28
                  AND p.delete_flag = 0
                  AND p.checkstatus = 1
                GROUP BY p.id
                ORDER BY p.ProductName ASC
            ) AS a
            WHERE balqty < MinQty";

    $res = $conn->query($sql);

    $records = [];
    $srNo = 1;
    while ($row = $res->fetch_assoc()) {
        // Carry forward calculation
        $sql21 = "SELECT SUM(creditqty) AS creditqty, SUM(debitqty) AS debitqty,
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

        $totalBalance = $row21['balqty'] + $row['balqty'];
        $amount = $totalBalance * $row['PurchasePrice'];

        $records[] = [
            "SrNo" => $srNo++,
            "ProductName" => $row['ProductName'],
            "CategoryName" => $row['CatName'],
            "PurchasePrice" => (float) $row['PurchasePrice'],
            "MinQty" => (float) $row['MinQty'],
            "CarryForward" => (float) $row21['balqty'],
            "Credit" => (float) $row['creditqty'],
            "Debit" => (float) $row['debitqty'],
            "Balance" => (float) $totalBalance,
            "Amount" => (float) $amount
        ];
    }

    echo json_encode([
        "status" => "success",
        "records" => $records
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch stock records: " . $e->getMessage()
    ]);
}
?>
