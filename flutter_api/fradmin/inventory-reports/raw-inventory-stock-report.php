<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $FrId = $_REQUEST['user_id'] ?? '';
    $CatId = $_REQUEST['CatId'] ?? '';
    $FromDate = $_REQUEST['FromDate'] ?? '';
    $ToDate = $_REQUEST['ToDate'] ?? '';

    if (empty($FrId)) {
        throw new Exception("FrId is required");
    }

    $sql = "SELECT ts.FrId, ts.ProdId, ts.Unit, ts.Unit2, p.ProductName, tcc.Name AS CatName, p.MinQty
            FROM tbl_cust_prod_stock_2025 ts
            INNER JOIN tbl_cust_products2 p ON ts.ProdId = p.id
            INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id
            WHERE ts.Status = 'Cr' AND ts.FrId = '$FrId' AND ts.ProdType = 1";

    if (!empty($CatId) && $CatId !== 'all') {
        $sql .= " AND p.CatId = '$CatId'";
    }

    if (!empty($FromDate)) {
        $sql .= " AND ts.StockDate >= '$FromDate'";
    }

    if (!empty($ToDate)) {
        $sql .= " AND ts.StockDate <= '$ToDate'";
    }

    $sql .= " GROUP BY p.id ORDER BY ts.id DESC";

    $res = $conn->query($sql);

    $records = [];
    $srNo = 1;

    while ($row = $res->fetch_assoc()) {
        // Fetch stock summary
        $sql2 = "SELECT SUM(creditqty) AS creditqty, SUM(debitqty) AS debitqty, 
                        SUM(creditqty) - SUM(debitqty) AS balqty
                 FROM (
                   SELECT 
                     (CASE WHEN Status = 'Dr' THEN SUM(Qty) ELSE 0 END) AS debitqty,
                     (CASE WHEN Status = 'Cr' THEN SUM(Qty) ELSE 0 END) AS creditqty
                   FROM tbl_cust_prod_stock_2025
                   WHERE FrId = '{$row['FrId']}' 
                     AND ProdId = '{$row['ProdId']}'
                     AND ProdType = 1";

        if (!empty($FromDate)) {
            $sql2 .= " AND StockDate >= '$FromDate'";
        }
        if (!empty($ToDate)) {
            $sql2 .= " AND StockDate <= '$ToDate'";
        }

        $sql2 .= " GROUP BY Status ) AS a";

        $row2 = getRecord($sql2);

        // Calculate adjusted quantities
        if ($row['Unit'] !== 'Pieces') {
            $creditqty = round(($row2['creditqty'] / 1000), 3);
            $debitqty = round(($row2['debitqty'] / 1000), 3);
            $balqty = round(($row2['balqty'] / 1000), 3);
        } else {
            $creditqty = $row2['creditqty'];
            $debitqty = $row2['debitqty'];
            $balqty = $row2['balqty'];
        }

        $records[] = [
            "SrNo" => $srNo++,
            "ProductName" => $row['ProductName'],
            "Credit" => (float)$creditqty,
            "Debit" => (float)$debitqty,
            "Balance" => (float)$balqty,
            "Unit" => $row['Unit2']
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
