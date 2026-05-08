<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $user_id = $_REQUEST['user_id'] ?? '';
    $sql77 = "SELECT Roll, BillSoftFrId FROM tbl_users_bill WHERE id = '$user_id' LIMIT 1";
    $result77 = mysqli_query($conn, $sql77);

    if ($result77 && mysqli_num_rows($result77) > 0) {
        $row77 = mysqli_fetch_assoc($result77);
        $Roll = $row77['Roll'];

        // Determine BillSoftFrId
        if ($Roll == 5) {
            $FrId = $user_id;
        } else {
            $FrId = $row77['BillSoftFrId'];
        }
    }
    $FromDate = date('Y-m-d');
    $ToDate = date('Y-m-d');
    $PayType = $_REQUEST['PayType'] ?? '';

    if (empty($FrId)) {
        throw new Exception("FrId is required");
    }

    // Build common WHERE conditions
    $whereCommon = "Roll = 2 AND Status = 1 AND FrId = '$FrId' AND delete_flag = 0";

    if (!empty($FromDate)) {
        $whereCommon .= " AND InvoiceDate >= '$FromDate'";
    }
    if (!empty($ToDate)) {
        $whereCommon .= " AND InvoiceDate <= '$ToDate'";
    }
    if (!empty($PayType) && $PayType !== 'all') {
        $whereCommon .= " AND PayType = '$PayType'";
    }

    $srno = 1;
    $sql = "(SELECT *, '2024' AS Year FROM tbl_customer_invoice WHERE $whereCommon)
            UNION ALL
            (SELECT *, '2025' AS Year FROM tbl_customer_invoice_2025 WHERE $whereCommon)
            ORDER BY InvoiceDate DESC";

    $res = $conn->query($sql);

    $records = [];
    $totalAmount = 0;

    while ($row = $res->fetch_assoc()) {
        $invoiceNo = $row['InvoiceNo'];
        $serverInvId = $row['Unqid'];
        $invId = $row['id'];
        $year = $row['Year'];

        // Choose correct details table
        $detailsTable = ($year === '2024') ? 'tbl_customer_invoice_details' : 'tbl_customer_invoice_details_2025';
        $productTable = ($year === '2024') ? 'tbl_cust_products' : 'tbl_cust_products_2025';

        // Fetch items
        $itemSql = "SELECT d.*, p.ProductName
                    FROM $detailsTable d
                    INNER JOIN $productTable p ON d.ProdId = p.id
                    WHERE d.ServerInvId = '$serverInvId' AND d.InvId = '$invId'";

        $itemRes = $conn->query($itemSql);

        $items = [];
        while ($itemRow = $itemRes->fetch_assoc()) {
            $items[] = [
                "ItemName" => $itemRow['ProductName'],
                "Qty" => $itemRow['Qty'],
                "Rate" => $itemRow['Price'],
                "Amount" => $itemRow['Total']
            ];
        }

        $records[] = [
            "OrderNo" => $srno,
            "InvoiceNo" => $invoiceNo,
            "InvoiceDate" => date("d/m/Y", strtotime($row['InvoiceDate'])),
            "InvoiceTime" => date("h:i:s", strtotime($row['CreatedTime'])),
            "CustomerName" => $row['CustName'],
            "NetAmount" => round($row['NetAmount'], 2),
            "PayType" => $row['PayType']
            
        ];

        $totalAmount += $row['NetAmount'];
        $srno++;
    }

    echo json_encode([
        "status" => "success",
        "totalAmount" => round($totalAmount, 2),
        "records" => $records
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch records: " . $e->getMessage()
    ]);
}
?>
