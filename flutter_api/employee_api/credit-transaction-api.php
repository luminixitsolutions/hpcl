<?php
header("Content-Type: application/json");
include 'db.php'; // Make sure this sets $conn

$data = json_decode(file_get_contents("php://input"), true);

// Correct way to extract data from raw JSON
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$FrId = isset($data['FrId']) ? intval($data['FrId']) : 0;

$response = [
    'status' => 'error',
    'message' => 'Invalid input',
];

if (!empty($phone) && $FrId > 0) {
    // Step 1: Get UserId by Phone
    $sql = "SELECT UserId AS id, CustPhone AS Phone, AccountName AS Fname 
            FROM tbl_cust_general_ledger 
            WHERE Roll=55 AND FrId='$FrId' AND CustPhone='$phone' 
            LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $userRow = $result->fetch_assoc();
        $UserId = $userRow['id'];

        // Step 2: Get Total Invoice Amount
        $TotAmt = 0;
        $sql2 = "SELECT Amount FROM tbl_cust_general_ledger 
                 WHERE FrId='$FrId' AND UserId='$UserId' AND Type='CINV' LIMIT 1";
        $result2 = $conn->query($sql2);
        if ($result2 && $result2->num_rows > 0) {
            $TotAmt = floatval($result2->fetch_assoc()['Amount']);
        }

        // Step 3: Get Total Paid Amount
        $PaidAmt = 0;
        $sql3 = "SELECT SUM(Amount) AS PaidAmt FROM tbl_cust_general_ledger 
                 WHERE FrId='$FrId' AND UserId='$UserId' AND Type!='CINV' AND CrDr='cr'";
        $result3 = $conn->query($sql3);
        if ($result3 && $result3->num_rows > 0) {
            $PaidAmt = floatval($result3->fetch_assoc()['PaidAmt']);
        }

        // Step 4: Get Ledger Entries
        $TotCreditAmt = 0;
        $TotDebitAmt = 0;
        $debitRows = [];
        $creditRows = [];

        $sqlLedger = "SELECT * FROM tbl_cust_general_ledger 
                      WHERE UserId='$UserId' AND FrId='$FrId' 
                      ORDER BY PaymentDate ASC";
        $ledgerResult = $conn->query($sqlLedger);

        while ($row = $ledgerResult->fetch_assoc()) {
            $entry = [
                'InvoiceNo'   => $row['CrDr'] === 'dr' ? $row['InvoiceNo'] : '',
                'PaymentDate' => date("d/m/Y", strtotime($row['PaymentDate'])),
                'PayMode'     => $row['PayMode'],
                'Credit'      => $row['CrDr'] === 'cr' ? number_format($row['Amount'], 2) : "0",
                'Debit'       => $row['CrDr'] === 'dr' ? number_format($row['Amount'], 2) : "0",
                'Narration'   => $row['Narration'],
                'UniqInvId'   => $row['UniqInvId'],
                'CrDr'        => $row['CrDr']
            ];

            if ($row['CrDr'] === 'dr') {
                $TotDebitAmt += floatval($row['Amount']);
                $debitRows[] = $entry;
            } else {
                $TotCreditAmt += floatval($row['Amount']);
                $creditRows[] = $entry;
            }
        }

        $response = [
            'status'        => 'success',
            'UserId'        => $UserId,
            'CustomerName'  => $userRow['Fname'],
            'Phone'         => $userRow['Phone'],
            'TotAmt'        => $TotAmt,
            'PaidAmt'       => $PaidAmt,
            'BalAmt'        => $TotAmt - $PaidAmt,
            'TotalCredit'   => $TotCreditAmt,
            'TotalDebit'    => $TotDebitAmt,
            'ledger'        => array_merge($debitRows, $creditRows)
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Customer not found with this phone number'
        ];
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
