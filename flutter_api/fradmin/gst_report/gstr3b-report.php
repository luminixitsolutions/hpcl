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
    $gstRateExpr = gstGstPercentageExpr();

    // A. Outward Supplies Summary grouped by GST rate
    $outwardSql = "SELECT
                        $gstRateExpr AS GstRate,
                        SUM($taxableExpr) AS TaxableValue,
                        SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                        SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                        SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                        SUM(COALESCE(d.CgstAmt, 0) + COALESCE(d.SgstAmt, 0) + COALESCE(d.IgstAmt, 0)) AS TotalGst,
                        SUM(COALESCE(d.Total, 0)) AS TotalAmount
                    $joins
                    WHERE $where
                    GROUP BY $gstRateExpr
                    ORDER BY GstRate ASC";

    $outwardRows = gstFetchRows($conn, $outwardSql);
    $outwardSupplies = [];
    $sr = 1;
    foreach ($outwardRows as $row) {
        $outwardSupplies[] = [
            'SrNo' => $sr++,
            'GstRate' => gstFloat($row['GstRate']),
            'TaxableValue' => gstFloat($row['TaxableValue']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalGst' => gstFloat($row['TotalGst']),
            'TotalAmount' => gstFloat($row['TotalAmount']),
        ];
    }

    $outwardTotals = gstSumTotals($outwardSupplies, [
        'TaxableValue' => 'TaxableValue',
        'Cgst' => 'Cgst',
        'Sgst' => 'Sgst',
        'Igst' => 'Igst',
        'TotalGst' => 'TotalGst',
        'TotalAmount' => 'TotalAmount',
    ]);

    // B. Tax Liability Summary
    $taxLiability = [
        ['TaxType' => 'CGST', 'Amount' => $outwardTotals['Cgst']],
        ['TaxType' => 'SGST', 'Amount' => $outwardTotals['Sgst']],
        ['TaxType' => 'IGST', 'Amount' => $outwardTotals['Igst']],
        ['TaxType' => 'Total GST', 'Amount' => $outwardTotals['TotalGst']],
    ];

    $outlet = gstGetOutletInfo($conn, $filters['FrId']);

    gstRespond([
        'status' => 'success',
        'report' => 'GSTR-3B',
        'filters' => $filters,
        'records' => $outwardSupplies,
        'outlet' => [
            'FrId' => (int) $filters['FrId'],
            'Name' => $outlet['Fname'] ?? '',
            'Gstin' => $outlet['GstNo'] ?? '',
            'State' => $outlet['StateName'] ?? '',
        ],
        'sections' => [
            'outward_supplies' => [
                'title' => 'Outward Supplies Summary',
                'columns' => [
                    'GstRate', 'TaxableValue', 'Cgst', 'Sgst', 'Igst', 'TotalGst', 'TotalAmount',
                ],
                'records' => $outwardSupplies,
                'totals' => $outwardTotals,
            ],
            'tax_liability' => [
                'title' => 'Tax Liability Summary',
                'columns' => ['TaxType', 'Amount'],
                'records' => $taxLiability,
                'totals' => [
                    'Amount' => $outwardTotals['TotalGst'],
                ],
            ],
        ],
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => 'Failed to fetch GSTR-3B report: ' . $e->getMessage(),
    ], 400);
}

?>
