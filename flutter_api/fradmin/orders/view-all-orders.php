<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $FrId = $_REQUEST['user_id'] ?? '';
    $ReportType = $_REQUEST['ReportType'] ?? 'Month';
    $FromDate = $_REQUEST['FromDate'] ?? '';
    $ToDate = $_REQUEST['ToDate'] ?? '';
    $PayType = $_REQUEST['PayType'] ?? '';

    if (empty($FrId)) {
        throw new Exception("FrId is required");
    }

    // Build common where condition
    $where = "Roll = 2 AND Status = 1 AND FrId = '$FrId' AND NetAmount > 0 AND delete_flag = 0";

    if (!empty($ReportType)) {
        if ($ReportType == 'Today') {
            $where .= " AND InvoiceDate = '" . date('Y-m-d') . "'";
        } elseif ($ReportType == 'Yesterday') {
            $where .= " AND InvoiceDate = '" . date('Y-m-d', strtotime('-1 day')) . "'";
        } elseif ($ReportType == 'Week') {
            $where .= " AND InvoiceDate >= '" . date('Y-m-d', strtotime('-7 days')) . "' AND InvoiceDate <= '" . date('Y-m-d') . "'";
        } elseif ($ReportType == 'Month') {
            $where .= " AND InvoiceDate >= '" . date('Y-m-d', strtotime('-30 days')) . "' AND InvoiceDate <= '" . date('Y-m-d') . "'";
        }
        // Custom: skip as FromDate / ToDate will handle
    }

    if (!empty($FromDate)) {
        $where .= " AND InvoiceDate >= '$FromDate'";
    }

    if (!empty($ToDate)) {
        $where .= " AND InvoiceDate <= '$ToDate'";
    }

    if (!empty($PayType) && $PayType !== 'all') {
        $where .= " AND PayType = '$PayType'";
    }

    $sql = "(SELECT *, '2024' AS Year FROM tbl_customer_invoice WHERE $where)
            UNION ALL
            (SELECT *, '2025' AS Year FROM tbl_customer_invoice_2025 WHERE $where)
            ORDER BY InvoiceDate DESC";
//echo $sql;
    $res = $conn->query($sql);
    $records = [];
    $totalAmount = 0;

    while ($row = $res->fetch_assoc()) {
        $records[] = [
            "OrderNo" => $row['OrderNo'],
            "InvoiceNo" => $row['InvoiceNo'],
            "InvoiceDate" => date("d/m/Y", strtotime($row['InvoiceDate'])),
            "CustomerName" => $row['CustName'],
            "NetAmount" => round($row['NetAmount'], 2),
            "PayType" => $row['PayType'],
            // "ViewLink" => "view-order-items.php?id={$row['Unqid']}&year={$row['Year']}"
        ];
        $totalAmount += $row['NetAmount'];
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
