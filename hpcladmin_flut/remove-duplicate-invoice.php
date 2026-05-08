<?php
include_once 'config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define date filter
$dateFilter  = '2025-10-01';
$dateFilter2 = '2025-10-11';

// Step 1: Find all duplicate InvoiceNos (grouped by InvoiceNo, FrId, and NetAmount)
$sql = "
    SELECT InvoiceNo, FrId, NetAmount
    FROM tbl_customer_invoice_2025
    WHERE InvoiceDate BETWEEN '$dateFilter' AND '$dateFilter2'
    GROUP BY InvoiceNo, FrId, NetAmount
    HAVING COUNT(*) > 1
";

$duplicates = getList($sql);
$i = 1;

foreach ($duplicates as $dup) {
    $InvoiceNo  = $dup['InvoiceNo'];
    $FrId       = $dup['FrId'];
    $NetAmount  = $dup['NetAmount'];

    // Step 2: Get all duplicate Unqid for this Invoice
    $sql2 = "
        SELECT Unqid
        FROM tbl_customer_invoice_2025
        WHERE InvoiceNo = '$InvoiceNo'
          AND FrId = '$FrId'
          AND NetAmount = '$NetAmount'
          AND InvoiceDate BETWEEN '$dateFilter' AND '$dateFilter2'
        ORDER BY Unqid ASC
    ";

    $records = getList($sql2);
    if (count($records) > 1) {
        // Keep the first Unqid and delete the rest
        $keepUnqid = $records[0]['Unqid'];
        $deleteUnqids = [];

        for ($j = 1; $j < count($records); $j++) {
            $deleteUnqids[] = $records[$j]['Unqid'];
        }

        if (!empty($deleteUnqids)) {
            $UnqidList = implode(',', $deleteUnqids);
            
            //$sql2 = "SELECT * FROM tbl_customer_invoice_2025 WHERE Unqid IN($UnqidList)"; echo $i." - ".$sql2."<br>";

            // Step 3: Delete from main invoice table
            $conn->query("DELETE FROM tbl_customer_invoice_2025 WHERE Unqid IN ($UnqidList)");

            // Step 4: Delete from invoice details table
            $conn->query("DELETE FROM tbl_customer_invoice_details_2025 WHERE ServerInvId IN ($UnqidList)");

            echo "<b>$i.</b> Deleted duplicates for InvoiceNo: <b>$InvoiceNo</b>, FrId: <b>$FrId</b> — Removed Unqid(s): $UnqidList<br>";
            $i++;
        }
    }
}

echo "<br><b>Duplicate cleanup completed successfully.</b>";
?>
