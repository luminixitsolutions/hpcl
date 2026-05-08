<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {

    $FrId = $_REQUEST['user_id'] ?? '';

    if (empty($FrId)) {
        throw new Exception("user_id is required");
    }

    // Main Product Query
    $sql = "SELECT 
                p.ProdId AS MainId,
                p.BarcodeNo,
                p.CreatedBy AS FrId,
                p.id AS ProdId,
                p.ProductName,
                COALESCE(p.MinQty, 0) AS MinQty,
                p.PurchasePrice,
                p.MinPrice
            FROM tbl_cust_products_2025 p
            INNER JOIN tbl_cust_category_2025 tcc 
                    ON p.CatId = tcc.id
            WHERE p.CreatedBy = '$FrId'
              AND p.ProdType = 0
              AND p.ProdType2 IN (1)
              AND p.delete_flag = 0
              AND p.checkstatus = 1
            GROUP BY p.id
            ORDER BY p.ProductName ASC";

    $res = $conn->query($sql);

    $records = [];
    $sr = 1;

    while ($row = $res->fetch_assoc()) {

        // Stock Calculation Query
        $sql2 = "SELECT 
                    SUM(creditqty) AS creditqty,
                    SUM(debitqty) AS debitqty,
                    SUM(creditqty) - SUM(debitqty) AS balqty
                 FROM (
                     SELECT 
                        (CASE WHEN Status='Dr' THEN SUM(Qty) ELSE 0 END) AS debitqty,
                        (CASE WHEN Status='Cr' THEN SUM(Qty) ELSE 0 END) AS creditqty
                     FROM tbl_cust_prod_stock_2025
                     WHERE FrId = '$FrId'
                       AND ProdId = '".$row['ProdId']."'
                       AND ProdType = 0
                     GROUP BY Status
                 ) AS a";

        $row2 = getRecord($sql2);

        $records[] = [
            "SrNo"           => $sr++,
            "MainProductId"  => $row['MainId'],
            "ProductId"      => $row['ProdId'],
            "ProductName"    => $row['ProductName'],
            "BarcodeNo"      => $row['BarcodeNo'],
            "PurchasePrice"  => (float) $row['PurchasePrice'],
            "SellPrice"      => (float) $row['MinPrice'],
            "CreditQty"      => (float) ($row2['creditqty'] ?? 0),
            "DebitQty"       => (float) ($row2['debitqty'] ?? 0),
            "BalanceQty"     => (float) ($row2['balqty'] ?? 0),
            "MinQty"         => (int) $row['MinQty'],
            "Qty"         => ''
        ];
    }

    echo json_encode([
        "status" => "success",
        "records" => $records
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "API Error: " . $e->getMessage()
    ]);
}
?>
