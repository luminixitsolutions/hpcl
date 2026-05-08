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
    $sql = "SELECT s.*, p.ProductName 
            FROM tbl_cust_prod_stock_2025 s 
            LEFT JOIN tbl_cust_products_2025 p ON s.ProdId = p.id
            WHERE s.FrId = '$FrId' 
              AND s.Status = 'Dr' AND s.Wastage='1'
            ORDER BY s.id DESC";

    $res = $conn->query($sql);

    $records = [];
    $sr = 1;

    if ($res && $res->num_rows > 0) {

        while ($row = $res->fetch_assoc()) {

            $ProductName   = $row['ProductName'] ?? '-';
            $Qty           = (float)$row['Qty'];
            $PurchasePrice = (float)$row['PurchasePrice'];
            $SellPrice     = (float)$row['SellPrice'];
            $TotalValue    = $Qty * $PurchasePrice;
            $Date          = date("d-M-Y h:i A", strtotime($row['CreatedDate']));
            $Narration     = $row['Narration'] ?? '-';

            $records[] = [
                "SrNo"          => $sr++,
                "ProductName"   => $ProductName,
                "Qty"           => $Qty,
                "PurchasePrice" => $PurchasePrice,
                "SellPrice"     => $SellPrice,
                "TotalValue"    => $TotalValue,
                "Date"          => $Date,
                "Narration"     => $Narration
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
