<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {

    $ReportType = $_REQUEST['ReportType'] ?? '';
    $FromDate   = $_REQUEST['FromDate'] ?? '';
    $ToDate     = $_REQUEST['ToDate'] ?? '';
    $PayType    = $_REQUEST['PayType'] ?? '';
    $FrId       = $_REQUEST['user_id'] ?? '';

    $sql = "SELECT *, '2024' AS Year 
            FROM tbl_customer_invoice_temp 
            WHERE Roll = 2 
              AND Status = 1 
              AND NetAmount > 0 
              AND delete_flag = 0";

    /* ===========================================
       REPORT TYPE FILTERS
    ============================================ */
    if (!empty($ReportType)) {

        if ($ReportType == "Today") {
            $today = date('Y-m-d');
            $sql .= " AND InvoiceDate = '$today'";
        }
        elseif ($ReportType == "Yesterday") {
            $yesterday = date('Y-m-d', strtotime("-1 day"));
            $sql .= " AND InvoiceDate = '$yesterday'";
        }
        elseif ($ReportType == "Week") {
            $week = date('Y-m-d', strtotime("-7 days"));
            $today = date('Y-m-d');
            $sql .= " AND InvoiceDate >= '$week' AND InvoiceDate <= '$today'";
        }
        elseif ($ReportType == "Month") {
            $month = date('Y-m-d', strtotime("-30 days"));
            $today = date('Y-m-d');
            $sql .= " AND InvoiceDate >= '$month' AND InvoiceDate <= '$today'";
        }
        elseif ($ReportType == "Custom") {
            // no condition
        }
    }

    /* ===========================================
       DATE RANGE FILTER
    ============================================ */
    if (!empty($FromDate)) $sql .= " AND InvoiceDate >= '$FromDate'";
    if (!empty($ToDate))   $sql .= " AND InvoiceDate <= '$ToDate'";

    /* ===========================================
       PAYMENT TYPE FILTER
    ============================================ */
    if (!empty($PayType) && $PayType !== "all") {
        $sql .= " AND PayType = '$PayType'";
    }

    /* ===========================================
       FRANCHISE FILTER
    ============================================ */
    if (!empty($FrId) && $FrId !== "all") {
        $sql .= " AND FrId = '$FrId'";
    }

    $sql .= " ORDER BY InvoiceDate DESC";

    $res = $conn->query($sql);

    $records = [];
    $TotalAmount = 0;
    $srNo = 1;

    while ($row = $res->fetch_assoc()) {

        // FETCH FRANCHISE NAME
        $sql3 = "SELECT ShopName FROM tbl_users_bill WHERE id = '".$row['FrId']."'";
        $row3 = getRecord($sql3);

        $TotalAmount += $row['NetAmount'];

        $records[] = [
             "SrNo" => $srNo++,
            "Franchise"    => $row3['ShopName'],
            "OrderNo"      => $row['OrderNo'],
            "InvoiceNo"    => $row['InvoiceNo'],
            "InvoiceDate"  => date("d/m/Y", strtotime($row['InvoiceDate'])),
            "InvoiceTime"  => date("h:i:s", strtotime($row['CreatedTime'])),
            "CustomerName" => $row['CustName'],
            "TotalAmount"  => round($row['NetAmount'], 2),
            "PaymentMode"  => $row['PayType']
        ];
    }

    echo json_encode([
        "status" => "success",
        "records" => $records,
        "TotalAmount" => round($TotalAmount, 2)
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch records: " . $e->getMessage()
    ]);
}
?>
