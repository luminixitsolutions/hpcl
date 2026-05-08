<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $FrId = $_REQUEST['user_id'] ?? '';
    $FromDate = $_REQUEST['FromDate'] ?? '';
    $ToDate = $_REQUEST['ToDate'] ?? '';

    if (empty($FrId)) {
        throw new Exception("FrId is required");
    }

    $sql = "SELECT td.*, tu2.Fname AS CustName
            FROM tbl_cust_general_ledger td
            INNER JOIN tbl_users tu2 ON tu2.id = td.UserId
            WHERE td.Type = 'PR' AND td.FrId = '$FrId'";

    if (!empty($FromDate)) {
        $sql .= " AND td.PaymentDate >= '$FromDate'";
    }

    if (!empty($ToDate)) {
        $sql .= " AND td.PaymentDate <= '$ToDate'";
    }

    $sql .= " ORDER BY td.id DESC";

    $res = $conn->query($sql);

    $records = [];
    $i = 1;
    while ($row = $res->fetch_assoc()) {
        $records[] = [
            "SerialNo" => $i++,
            "VoucherNo" => $row['Code'],
            "PaymentDate" => date("d/m/Y", strtotime(str_replace('-', '/', $row['PaymentDate']))),
            "CustomerName" => $row['CustName'],
            "Amount" => (float) $row['Amount'],
            "PaymentMode" => $row['PayMode'],
            "EditLink" => "add-credit-account.php?id=" . $row['id'],
            "DeleteLink" => $_SERVER['PHP_SELF'] . "?id=" . $row['id'] . "&action=delete"
        ];
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
