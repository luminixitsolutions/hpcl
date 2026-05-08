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


    // MAIN QUERY
    $sql = "
        SELECT 
            p.ProductName,
            COALESCE(SUM(CASE WHEN t.Status = 'Cr' THEN t.Qty ELSE 0 END), 0) AS TotalCredit,
            COALESCE(SUM(CASE WHEN t.Status = 'Dr' THEN t.Qty ELSE 0 END), 0) AS TotalDebit
        FROM tbl_cust_prod_stock_2025 t
        LEFT JOIN tbl_cust_products_2025 p ON t.ProdId = p.id
        WHERE t.FrId = '$FrId' AND p.ProductName != ''
        GROUP BY t.ProdId
        ORDER BY p.ProductName ASC
    ";

    $res = $conn->query($sql);

    $records = [];
    $sr = 1;

    if ($res && $res->num_rows > 0) {

        while ($row = $res->fetch_assoc()) {

            $ProductName = $row['ProductName'] ?? '-';
            $credit      = (float)$row['TotalCredit'];
            $debit       = (float)$row['TotalDebit'];
            $balance     = $credit - $debit;

            $records[] = [
                "SrNo"        => $sr++,
                "ProductName" => $ProductName,
                "TotalCredit" => $credit,
                "TotalDebit"  => $debit,
                "Balance"     => $balance
            ];
        }
    }

    echo json_encode([
        "status" => "success",
        "records" => $records
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
