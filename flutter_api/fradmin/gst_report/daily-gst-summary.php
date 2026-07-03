<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $filters = gstGetRequestFilters($conn);
    $where = gstInvoiceWhere($filters);
    $joins = gstBaseJoins();
    $taxableExpr = gstLineTaxableExpr();

    $sql = "SELECT
                x.InvoiceDate AS Date,
                COUNT(*) AS TotalBills,
                SUM(x.TaxableAmount) AS TaxableAmount,
                SUM(x.Cgst) AS Cgst,
                SUM(x.Sgst) AS Sgst,
                SUM(x.Igst) AS Igst,
                SUM(x.TotalGst) AS TotalGst,
                SUM(x.GrandTotal) AS GrandTotal
            FROM (
                SELECT
                    i.InvoiceDate,
                    i.id,
                    SUM($taxableExpr) AS TaxableAmount,
                    SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                    SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                    SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                    SUM(COALESCE(d.CgstAmt, 0) + COALESCE(d.SgstAmt, 0) + COALESCE(d.IgstAmt, 0)) AS TotalGst,
                    MAX(COALESCE(i.NetAmount, 0)) AS GrandTotal
                $joins
                WHERE $where
                GROUP BY i.id, i.InvoiceDate
            ) x
            GROUP BY x.InvoiceDate
            ORDER BY x.InvoiceDate ASC";

    $rows = gstFetchRows($conn, $sql);
    $records = [];
    $sr = 1;
    foreach ($rows as $row) {
        $records[] = [
            'SrNo' => $sr++,
            'Date' => $row['Date'],
            'TotalBills' => (int) $row['TotalBills'],
            'TaxableAmount' => gstFloat($row['TaxableAmount']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalGst' => gstFloat($row['TotalGst']),
            'GrandTotal' => gstFloat($row['GrandTotal']),
        ];
    }

    gstRespond([
        'status' => 'success',
        'report' => 'Daily GST Summary',
        'filters' => $filters,
        'columns' => [
            'Date', 'TotalBills', 'TaxableAmount', 'Cgst', 'Sgst', 'Igst', 'TotalGst', 'GrandTotal',
        ],
        'records' => $records,
        'totalRecords' => count($records),
        'totals' => gstSumTotals($records, [
            'TotalBills' => 'TotalBills',
            'TaxableAmount' => 'TaxableAmount',
            'Cgst' => 'Cgst',
            'Sgst' => 'Sgst',
            'Igst' => 'Igst',
            'TotalGst' => 'TotalGst',
            'GrandTotal' => 'GrandTotal',
        ]),
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => 'Failed to fetch daily GST summary: ' . $e->getMessage(),
    ], 400);
}

?>
