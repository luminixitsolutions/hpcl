<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {

    $FrId = $_REQUEST['user_id'] ?? '';
    $FromDate = $_REQUEST['FromDate'] ?? '';
    $ToDate   = $_REQUEST['ToDate'] ?? '';

    if (empty($FrId)) {
        throw new Exception("user_id is required");
    }

    // FIELD LIST
    $fields = [
        [ "label" => "Sr No", "key" => "SrNo" ],
        [ "label" => "Customer Name", "key" => "CustomerName" ],
        [ "label" => "Phone No", "key" => "PhoneNo" ],
        [ "label" => "Total Amount (Debit)", "key" => "TotalAmount" ],
        [ "label" => "Paid Amount (Credit)", "key" => "PaidAmount" ],
        [ "label" => "Balance Amount", "key" => "BalanceAmount" ]
    ];

    // MAIN QUERY
    $sql = "SELECT * FROM tbl_cust_general_ledger WHERE FrId='$FrId'";

    if (!empty($FromDate)) {
        $sql .= " AND PaymentDate >= '$FromDate'";
    }
    if (!empty($ToDate)) {
        $sql .= " AND PaymentDate <= '$ToDate'";
    }

    $sql .= " GROUP BY UserId ORDER BY PaymentDate";

    $res = $conn->query($sql);

    $records = [];
    $sr = 1;

    while ($row = $res->fetch_assoc()) {

        $UserId = $row['UserId'];

        // Calculate Debit/Credit
        $sql2 = "
            SELECT 
                SUM(CASE WHEN CrDr = 'cr' THEN Amount ELSE 0 END) AS CreditAmount,
                SUM(CASE WHEN CrDr = 'dr' THEN Amount ELSE 0 END) AS DebitAmount
            FROM tbl_cust_general_ledger 
            WHERE UserId='$UserId' AND FrId='$FrId'
        ";
        $row2 = getRecord($sql2);

        $CreditAmt = (float)$row2['CreditAmount'];
        $DebitAmt  = (float)$row2['DebitAmount'];
        $Balance   = $DebitAmt - $CreditAmt;

        // Build final JSON row
        $records[] = [
            "SrNo"          => $sr++,
            "CustomerName"  => $row['AccountName'],
            "PhoneNo"       => $row['CustPhone'],
            "TotalAmount"   => $DebitAmt,
            "PaidAmount"    => $CreditAmt,
            "BalanceAmount" => $Balance
        ];
    }

    // Final response
    echo json_encode([
        "status" => "success",
        "fields" => $fields,
        "records" => $records
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}
?>
