<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $FrId = $_REQUEST['user_id'] ?? '';
    $ProdId = $_REQUEST['ProdId'] ?? '';
    $FromDate = $_REQUEST['FromDate'] ?? '';
    $ToDate = $_REQUEST['ToDate'] ?? '';

    if (empty($FrId)) {
        throw new Exception("user_id (FrId) is required");
    }

    // MAIN PRODUCT QUERY
    $sql = "SELECT id, ProductName, MinPrice, PurchasePrice 
            FROM tbl_cust_products_2025 
            WHERE CreatedBy = '$FrId'
              AND ProdType = 0
              AND checkstatus = 1
              AND delete_flag = 0";

    if (!empty($ProdId) && $ProdId !== 'all') {
        $sql .= " AND id = '$ProdId'";
    }

    $sql .= " ORDER BY srno ASC";

    $res = $conn->query($sql);

    $records = [];
    $srNo = 1;

    while ($row = $res->fetch_assoc()) {

        // SECOND QUERY → TOTAL SALE & QTY
        $sql2 = "SELECT 
                    SUM(tcid.Total) AS TotalAmount,
                    SUM(tcid.Qty) AS TotalQty
                 FROM tbl_customer_invoice_details_2025 tcid
                 INNER JOIN tbl_customer_invoice_2025 tci 
                        ON tci.id = tcid.InvId
                 WHERE tcid.ProdId = '" . $row['id'] . "'
                   AND tci.FrId = '$FrId'";

        if (!empty($FromDate)) {
            $sql2 .= " AND tcid.CreatedDate >= '$FromDate'";
        }
        if (!empty($ToDate)) {
            $sql2 .= " AND tcid.CreatedDate <= '$ToDate'";
        }

        $row2 = getRecord($sql2);

        if ($row2['TotalQty'] > 0) {

            $purchaseAmt = $row['PurchasePrice'] * $row2['TotalQty'];
            $sellAmt = $row2['TotalAmount'];
            $profitAmt = $sellAmt - $purchaseAmt;

            $records[] = [
                "SrNo" => $srNo++,
                "ProductName" => $row['ProductName'],
                "MRP" => (float)$row['MinPrice'],
                "TotalSell" => (float)$row2['TotalQty'],
                "PurchaseAmount" => round($purchaseAmt, 2),
                "SellAmount" => round($sellAmt, 2),
                "ProfitAmount" => round($profitAmt, 2)
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
        "message" => "Failed to fetch records: " . $e->getMessage()
    ]);
}
?>
