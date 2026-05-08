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

    $sql = "SELECT id, ProductName, PurchasePrice 
            FROM tbl_cust_products_2025 
            WHERE CreatedBy = '$FrId' 
              AND checkstatus = 1 
              AND delete_flag = 0";

    if (!empty($CatId) && $CatId !== 'all') {
        $sql .= " AND id = '$CatId'";
    }

    $sql .= " ORDER BY srno ASC";

    $res = $conn->query($sql);

    $records = [];
    $srNo = 1;

    while ($row = $res->fetch_assoc()) {
        $prodId = $row['id'];

        $sql2 = "SELECT SUM(tcid.Total) AS Total, SUM(tcid.Qty) AS TotProd
                 FROM tbl_customer_invoice_details_2025 tcid
                 INNER JOIN tbl_customer_invoice_2025 tci ON tci.id = tcid.InvId
                 WHERE tcid.ProdId = '$prodId'
                   AND tci.Roll = 2
                   AND tci.FrId = '$FrId'";

        if (!empty($FromDate)) {
            $sql2 .= " AND tci.InvoiceDate >= '$FromDate'";
        }

        if (!empty($ToDate)) {
            $sql2 .= " AND tci.InvoiceDate <= '$ToDate'";
        }

        $row2 = getRecord($sql2);

        if ($row2['TotProd'] > 0) {
            $purchaseAmt = $row['PurchasePrice'] * $row2['TotProd'];
            $sellAmt = $row2['Total'];
            $profitAmt = $sellAmt - $purchaseAmt;

            $records[] = [
                "SrNo" => $srNo++,
                "ProductName" => $row['ProductName'],
                "TotalSellQty" => (float) $row2['TotProd'],
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
