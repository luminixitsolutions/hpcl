<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

/* ==================================================
    HELPER FUNCTIONS
================================================== */

function calMonth($fromdate, $todate) {
    $ts1 = strtotime($fromdate);
    $ts2 = strtotime($todate);
    $diff = ((date('Y', $ts2) - date('Y', $ts1)) * 12) + (date('m', $ts2) - date('m', $ts1));
    return ($diff > 0) ? $diff : 0;
}

function ftdRevenue($frid, $fromdate, $todate, $paymode){
    global $conn;

    $sql = "SELECT SUM(TotalInv) AS TotalInv FROM (
                SELECT COUNT(*) AS TotalInv
                FROM tbl_customer_invoice
                WHERE FrId='$frid' 
                  AND InvoiceDate>='$fromdate' 
                  AND InvoiceDate<='$todate'";

    if ($paymode == 'Cash') {
        $sql .= " AND PayType='Cash'";
    } elseif ($paymode != '') {
        $sql .= " AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI','Borrowing','Swiggy','Zomato')";
    }

    $sql .= " UNION ALL
                SELECT COUNT(*) AS TotalInv
                FROM tbl_customer_invoice_2025
                WHERE FrId='$frid'
                  AND InvoiceDate>='$fromdate'
                  AND InvoiceDate<='$todate'";

    if ($paymode == 'Cash') {
        $sql .= " AND PayType='Cash'";
    } elseif ($paymode != '') {
        $sql .= " AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI','Borrowing','Swiggy','Zomato')";
    }

    $sql .= ") AS a";

    $row = getRecord($sql);
    return $row['TotalInv'] ?? 0;
}

function ftdRevenueAmt($frid, $fromdate, $todate, $paymode){
    global $conn;

    $sql = "SELECT SUM(NetAmount) AS NetAmount FROM (
                SELECT SUM(NetAmount) AS NetAmount
                FROM tbl_customer_invoice
                WHERE FrId='$frid'
                  AND InvoiceDate>='$fromdate'
                  AND InvoiceDate<='$todate'";

    if ($paymode == 'Cash') {
        $sql .= " AND PayType='Cash'";
    } elseif ($paymode != '') {
        $sql .= " AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }

    $sql .= " UNION ALL
                SELECT SUM(NetAmount) AS NetAmount
                FROM tbl_customer_invoice_2025
                WHERE FrId='$frid'
                  AND InvoiceDate>='$fromdate'
                  AND InvoiceDate<='$todate'";

    if ($paymode == 'Cash') {
        $sql .= " AND PayType='Cash'";
    } elseif ($paymode != '') {
        $sql .= " AND PayType IN ('UPI','Phone Pay','Paytm','Online Payment','Other UPI')";
    }

    $sql .= ") AS a";

    $row = getRecord($sql);
    return $row['NetAmount'] ?? 0;
}

/* ==================================================
    MAIN SINGLE OUTLET API
================================================== */

try {

    $FrId = $_REQUEST['user_id'] ?? '';

    if (empty($FrId)) {
        throw new Exception("user_id is required");
    }

    $sql = "SELECT * FROM tbl_users_bill 
            WHERE id='$FrId' AND Roll=5 AND Status=1 AND ShowFrStatus=1";

    $outlet = getRecord($sql);

    if (!$outlet) {
        throw new Exception("No outlet found for given user_id");
    }

    // Calculate Dates
    $yesterday = date('Y-m-d', strtotime("-1 day"));
    $lastmonth_start = date('Y-m-01', strtotime("-1 month"));
    $lastmonth_end = date('Y-m-d', strtotime("-1 day"));

    // MTD
    $mtd_inv  = ftdRevenue($FrId, date('Y-m-01'), $yesterday, '');
    $mtd_amt  = ftdRevenueAmt($FrId, date('Y-m-01'), $yesterday, '');

    // PMSD
    $pmsd_inv = ftdRevenue($FrId, $lastmonth_start, $lastmonth_end, '');
    $pmsd_amt = ftdRevenueAmt($FrId, $lastmonth_start, $lastmonth_end, '');

    // Growth
    $growth_inv = ($pmsd_inv == 0) ? 0 : (($mtd_inv - $pmsd_inv) / $pmsd_inv);
    $growth_amt = ($pmsd_amt == 0) ? 0 : (($mtd_amt - $pmsd_amt) / $pmsd_amt);


    /* ============================
        SEND CLEAN JSON RESPONSE
    ============================ */
    echo json_encode([
    "status" => "success",
    "records" => [
        [
            "SrNo"          => 1,
            "OutletId"      => $outlet['CustomerId'],
            "OutletName"    => $outlet['ShopName'],
            "Location"      => $outlet['Address'],
            "FTD_Invoices"  => ftdRevenue($FrId, $yesterday, $yesterday, ''),
            "FTD_Amount"    => ftdRevenueAmt($FrId, $yesterday, $yesterday, ''),
            "MTD_Invoices"  => $mtd_inv,
            "MTD_Amount"    => $mtd_amt,
            "PMSD_Invoices" => $pmsd_inv,
            "PMSD_Amount"   => $pmsd_amt,
            "Growth_Inv"    => round($growth_inv, 2),
            "Growth_Amount" => round($growth_amt, 2),
            "Cash_Invoices" => ftdRevenue($FrId, date('Y-m-01'), $yesterday, 'Cash'),
            "Cash_Value"    => ftdRevenueAmt($FrId, date('Y-m-01'), $yesterday, 'Cash'),
            "UPI_Invoices"  => ftdRevenue($FrId, date('Y-m-01'), $yesterday, 'UPI'),
            "UPI_Value"     => ftdRevenueAmt($FrId, date('Y-m-01'), $yesterday, 'UPI')
        ]
    ]
]);


} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}
?>
