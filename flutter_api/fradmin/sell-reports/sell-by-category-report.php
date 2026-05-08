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
        throw new Exception("user_id (FrId) is required");
    }

    // MAIN CATEGORY QUERY
    $sql = "SELECT id, Name 
            FROM tbl_cust_category_2025 
            WHERE ProdType = 0";

    if (!empty($CatId) && $CatId !== 'all') {
        $sql .= " AND id = '$CatId'";
    }

    $sql .= " ORDER BY srno ASC";

    $res = $conn->query($sql);

    $records = [];
    $srNo = 1;

    while ($row = $res->fetch_assoc()) {

        // SECOND QUERY → TOTAL, COUNT
        $sql2 = "SELECT 
                    SUM(tcid.Total) AS TotalAmount,
                    COUNT(tcid.id) AS TotalSell
                 FROM tbl_customer_invoice_details_2025 tcid
                 INNER JOIN tbl_customer_invoice_2025 tci 
                        ON tci.id = tcid.InvId
                 WHERE tcid.CatId = '" . $row['id'] . "'
                   AND tci.Roll = 2
                   AND tci.FrId = '$FrId'";

        if (!empty($FromDate)) {
            $sql2 .= " AND tci.InvoiceDate >= '$FromDate'";
        }
        if (!empty($ToDate)) {
            $sql2 .= " AND tci.InvoiceDate <= '$ToDate'";
        }

        $row2 = getRecord($sql2);

        // ONLY PUSH IF SELL > 0
        if ($row2['TotalSell'] > 0) {
            $records[] = [
                "SrNo" => $srNo++,
                "Category" => $row['Name'],
                "TotalSell" => (int)$row2['TotalSell'],
                "Amount" => round($row2['TotalAmount'], 2)
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
