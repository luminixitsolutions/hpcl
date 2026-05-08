<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

/* ========================
   HELPER FUNCTIONS
========================= */

function calMonth($fromdate,$todate){
    $ts1 = strtotime($fromdate);
    $ts2 = strtotime($todate);
    $diff = ((date('Y',$ts2) - date('Y',$ts1)) * 12) + (date('m',$ts2) - date('m',$ts1));
    return ($diff > 0) ? $diff : 0;
}

function ftdRevenue($frid,$fromdate,$todate,$paymode){
    global $conn;

    $sql = "SELECT SUM(TotalInv) AS TotalInv FROM (
                SELECT COUNT(*) AS TotalInv
                FROM tbl_customer_invoice
                WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";

    if($paymode=='Cash'){
        $sql.=" AND PayType='Cash'";
    } else if($paymode!=''){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }

    $sql.=" UNION ALL 
            SELECT COUNT(*) AS TotalInv
            FROM tbl_customer_invoice_2025
            WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";

    if($paymode=='Cash'){
        $sql.=" AND PayType='Cash'";
    } else if($paymode!=''){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }

    $sql.=" ) as a";

    $row = getRecord($sql);
    return $row['TotalInv'] ?? 0;
}

function ftdRevenueAmt($frid,$fromdate,$todate,$paymode){
    global $conn;

    $sql = "SELECT SUM(NetAmount) AS NetAmount FROM (
                SELECT SUM(NetAmount) AS NetAmount
                FROM tbl_customer_invoice
                WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";

    if($paymode=='Cash'){
        $sql.=" AND PayType='Cash'";
    } else if($paymode!=''){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }

    $sql.=" UNION ALL 
            SELECT SUM(NetAmount) AS NetAmount
            FROM tbl_customer_invoice_2025
            WHERE FrId='$frid' AND InvoiceDate>='$fromdate' AND InvoiceDate<='$todate'";

    if($paymode=='Cash'){
        $sql.=" AND PayType='Cash'";
    } else if($paymode!=''){
        $sql.=" AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }

    $sql.=" ) as a";

    $row = getRecord($sql);
    return $row['NetAmount'] ?? 0;
}

/* ========================
     MAIN WEEKLY API
========================= */

try {

    $FrId = $_REQUEST['user_id'] ?? '';

    if (empty($FrId)) {
        throw new Exception("user_id is required");
    }

    // Fetch outlet
    $sql = "SELECT * FROM tbl_users_bill 
            WHERE id='$FrId' AND Roll=5 AND Status=1 AND ShowFrStatus=1";

    $outlet = getRecord($sql);

    if (!$outlet) {
        throw new Exception("No outlet found for this user_id");
    }

    // Month details
    $cal = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

    // Weeks
    $w1s = date('Y-m')."-01";
    $w1e = date('Y-m')."-07";

    $w2s = date('Y-m')."-08";
    $w2e = date('Y-m')."-14";

    $w3s = date('Y-m')."-15";
    $w3e = date('Y-m')."-21";

    $w4s = date('Y-m')."-22";
    $w4e = date('Y-m')."-28";

    $w5s = date('Y-m')."-29";
    $w5e = date('Y-m')."-".$cal;

    // Build one outlet record
    $record = [
        "SrNo"             => 1,
        "OutletId"         => $outlet['CustomerId'],
        "OutletName"       => $outlet['ShopName'],
        "Location"         => $outlet['Address'],

        "Week1_Invoices"   => ftdRevenue($FrId, $w1s, $w1e, ''),
        "Week1_Amount"     => ftdRevenueAmt($FrId, $w1s, $w1e, ''),

        "Week2_Invoices"   => ftdRevenue($FrId, $w2s, $w2e, ''),
        "Week2_Amount"     => ftdRevenueAmt($FrId, $w2s, $w2e, ''),

        "Week3_Invoices"   => ftdRevenue($FrId, $w3s, $w3e, ''),
        "Week3_Amount"     => ftdRevenueAmt($FrId, $w3s, $w3e, ''),

        "Week4_Invoices"   => ftdRevenue($FrId, $w4s, $w4e, ''),
        "Week4_Amount"     => ftdRevenueAmt($FrId, $w4s, $w4e, ''),
    ];

    // Week 5 only if month > 28 days
    if ($cal > 28) {
        $record["Week5_Invoices"] = ftdRevenue($FrId, $w5s, $w5e, '');
        $record["Week5_Amount"]   = ftdRevenueAmt($FrId, $w5s, $w5e, '');
    } else {
        $record["Week5_Invoices"] = 0;
        $record["Week5_Amount"]   = 0;
    }

    echo json_encode([
        "status" => "success",
        "records" => [ $record ]
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
