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
                DATE_FORMAT(x.InvoiceDate, '%Y-%m') AS Month,
                COUNT(*) AS InvoiceCount,
                SUM(x.TaxableValue) AS TaxableValue,
                SUM(x.Cgst) AS Cgst,
                SUM(x.Sgst) AS Sgst,
                SUM(x.Igst) AS Igst,
                SUM(x.TotalGst) AS TotalGst,
                SUM(x.GrandTotal) AS GrandTotal
            FROM (
                SELECT
                    i.InvoiceDate,
                    i.id,
                    SUM($taxableExpr) AS TaxableValue,
                    SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                    SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                    SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                    SUM(COALESCE(d.CgstAmt, 0) + COALESCE(d.SgstAmt, 0) + COALESCE(d.IgstAmt, 0)) AS TotalGst,
                    MAX(COALESCE(i.NetAmount, 0)) AS GrandTotal
                $joins
                WHERE $where
                GROUP BY i.id, i.InvoiceDate
            ) x
            GROUP BY DATE_FORMAT(x.InvoiceDate, '%Y-%m')
            ORDER BY Month ASC";

    $rows = gstFetchRows($conn, $sql);
    $records = [];
    $sr = 1;
    foreach ($rows as $row) {
        $records[] = [
            'SrNo' => $sr++,
            'Month' => $row['Month'],
            'InvoiceCount' => (int) $row['InvoiceCount'],
            'TaxableValue' => gstFloat($row['TaxableValue']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalGst' => gstFloat($row['TotalGst']),
            'GrandTotal' => gstFloat($row['GrandTotal']),
        ];
    }

    gstRespond([
        'status' => 'success',
        'report' => 'Monthly GST Summary',
        'filters' => $filters,
        'columns' => [
            'Month', 'InvoiceCount', 'TaxableValue', 'Cgst', 'Sgst', 'Igst', 'TotalGst', 'GrandTotal',
        ],
        'records' => $records,
        'totals' => gstSumTotals($records, [
            'InvoiceCount' => 'InvoiceCount',
            'TaxableValue' => 'TaxableValue',
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
        'message' => 'Failed to fetch monthly GST summary: ' . $e->getMessage(),
    ], 400);
}

?>
