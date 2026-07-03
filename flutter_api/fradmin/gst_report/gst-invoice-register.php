<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $filters = gstGetRequestFilters($conn);
    $where = gstInvoiceWhere($filters) . gstTypeFilterClause($filters);
    $joins = gstBaseJoins();
    $taxableExpr = gstLineTaxableExpr();
    $invoiceTypeExpr = gstInvoiceTypeExpr();
    $customerGstinExpr = gstCustomerGstinExpr();

    $sql = "SELECT
                i.InvoiceNo,
                i.InvoiceDate,
                COALESCE(NULLIF(i.CustName, ''), NULLIF(c.Fname, ''), 'Walk-in Customer') AS CustomerName,
                $customerGstinExpr AS Gstin,
                SUM($taxableExpr) AS TaxableValue,
                SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                MAX(COALESCE(i.NetAmount, 0)) AS TotalInvoiceAmount,
                $invoiceTypeExpr AS InvoiceType
            $joins
            WHERE $where
            GROUP BY i.id, i.InvoiceNo, i.InvoiceDate, i.CustName, c.Fname,
                     i.CustGstin, c.GstNo, i.NetAmount
            ORDER BY i.InvoiceDate ASC, i.InvoiceNo ASC";

    $rows = gstFetchRows($conn, $sql);
    $records = [];
    $sr = 1;
    foreach ($rows as $row) {
        $records[] = [
            'SrNo' => $sr++,
            'InvoiceNo' => $row['InvoiceNo'],
            'InvoiceDate' => $row['InvoiceDate'],
            'CustomerName' => $row['CustomerName'],
            'Gstin' => $row['Gstin'],
            'TaxableValue' => gstFloat($row['TaxableValue']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalInvoiceAmount' => gstFloat($row['TotalInvoiceAmount']),
            'InvoiceType' => $row['InvoiceType'],
        ];
    }

    gstRespond([
        'status' => 'success',
        'report' => 'GST Invoice Register',
        'filters' => $filters,
        'columns' => [
            'InvoiceNo', 'InvoiceDate', 'CustomerName', 'Gstin',
            'TaxableValue', 'Cgst', 'Sgst', 'Igst', 'TotalInvoiceAmount', 'InvoiceType',
        ],
        'records' => $records,
        'totals' => gstSumTotals($records, [
            'TaxableValue' => 'TaxableValue',
            'Cgst' => 'Cgst',
            'Sgst' => 'Sgst',
            'Igst' => 'Igst',
            'TotalInvoiceAmount' => 'TotalInvoiceAmount',
        ]),
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => 'Failed to fetch GST invoice register: ' . $e->getMessage(),
    ], 400);
}

?>
