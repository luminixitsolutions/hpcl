<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {

    $FrId       = $_REQUEST['user_id'] ?? '';
    $ReportType = $_REQUEST['ReportType'] ?? '';
    $FromDate   = $_REQUEST['FromDate'] ?? '';
    $ToDate     = $_REQUEST['ToDate'] ?? '';
    $PayType    = $_REQUEST['PayType'] ?? '';

    if (empty($FrId)) {
        throw new Exception("user_id (FrId) is required");
    }

    $sql = "SELECT * 
            FROM tbl_customer_invoice_2025 
            WHERE Roll = 2 
              AND Status = 1 
              AND delete_flag = 0
              AND Discount > 0
              AND FrId = '$FrId'";

    /* ===============================
        REPORT TYPE FILTER
    =================================*/
    if (!empty($ReportType)) {

        if ($ReportType == "Today") {
            $today = date('Y-m-d');
            $sql .= " AND InvoiceDate = '$today'";
        }
        elseif ($ReportType == "Yesterday") {
            $yesterday = date('Y-m-d', strtotime("-1 days"));
            $sql .= " AND InvoiceDate = '$yesterday'";
        }
        elseif ($ReportType == "Week") {
            $dateWeek = date('Y-m-d', strtotime("-7 days"));
            $today    = date('Y-m-d');
            $sql .= " AND InvoiceDate >= '$dateWeek' AND InvoiceDate <= '$today'";
        }
        elseif ($ReportType == "Month") {
            $dateMonth = date('Y-m-d', strtotime("-30 days"));
            $today     = date('Y-m-d');
            $sql .= " AND InvoiceDate >= '$dateMonth' AND InvoiceDate <= '$today'";
        }
        elseif ($ReportType == "Custom") {
            // No extra condition
        }
    }

    /* ===============================
        DATE RANGE FILTER
    =================================*/
    if (!empty($FromDate)) {
        $sql .= " AND InvoiceDate >= '$FromDate'";
    }

    if (!empty($ToDate)) {
        $sql .= " AND InvoiceDate <= '$ToDate'";
    }

    /* ===============================
        PAYMENT TYPE FILTER
    =================================*/
    if (!empty($PayType) && $PayType !== 'all') {
        $sql .= " AND PayType = '$PayType'";
    }

    $sql .= " ORDER BY InvoiceDate DESC";

    $res = $conn->query($sql);

    $records = [];
    $TotalSubTotal = 0;
    $TotalDiscount = 0;
    $TotalAmount   = 0;

    while ($row = $res->fetch_assoc()) {

        $TotalSubTotal += $row['SubTotal'];
        $TotalDiscount += $row['Discount'];
        $TotalAmount   += $row['NetAmount'];

        $records[] = [
            "InvoiceNo"     => $row['InvoiceNo'],
            "InvoiceDate"   => date("d/m/Y", strtotime($row['InvoiceDate'])),
            "CustomerName"  => $row['CustName'],
            "ContactNo"     => $row['CellNo'],
            "SubTotal"      => round($row['SubTotal'], 2),
            "Discount"      => round($row['Discount'], 2),
            "TotalAmount"   => round($row['NetAmount'], 2),
            "PaymentMode"   => $row['PayType']
        ];
    }

    echo json_encode([
        "status" => "success",
        "records" => $records,
        "totals" => [
            "TotalSubTotal" => round($TotalSubTotal, 2),
            "TotalDiscount" => round($TotalDiscount, 2),
            "TotalAmount"   => round($TotalAmount, 2)
        ]
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch records: " . $e->getMessage()
    ]);
}
?>
