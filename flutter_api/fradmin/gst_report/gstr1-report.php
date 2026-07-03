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
    $placeOfSupplyExpr = gstPlaceOfSupplyExpr();
    $hsnCodeExpr = gstHsnCodeExpr();
    $hsnDescExpr = gstHsnDescriptionExpr();

    // A. B2B Sales
    $b2bSql = "SELECT
                    i.InvoiceNo,
                    i.InvoiceDate,
                    $customerGstinExpr AS CustomerGstin,
                    COALESCE(NULLIF(i.CustName, ''), NULLIF(c.Fname, ''), 'Walk-in Customer') AS CustomerName,
                    $placeOfSupplyExpr AS PlaceOfSupply,
                    SUM($taxableExpr) AS TaxableValue,
                    SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                    SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                    SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                    MAX(COALESCE(i.NetAmount, 0)) AS TotalInvoiceAmount,
                    $invoiceTypeExpr AS InvoiceType
                $joins
                LEFT JOIN tbl_hsn_master h ON h.HsnCode = COALESCE(NULLIF(p.HsnCode, ''), NULLIF(d.HsnCode, ''))
                WHERE $where
                  AND ($customerGstinExpr) != ''
                GROUP BY i.id, i.InvoiceNo, i.InvoiceDate, i.CustName, c.Fname, i.CustGstin, c.GstNo,
                         i.PlaceOfSupply, cs.Name, os.Name, i.NetAmount
                ORDER BY i.InvoiceDate ASC, i.InvoiceNo ASC";

    $b2bRows = gstFetchRows($conn, $b2bSql);
    $b2bSales = [];
    $sr = 1;
    foreach ($b2bRows as $row) {
        $b2bSales[] = [
            'SrNo' => $sr++,
            'InvoiceNo' => $row['InvoiceNo'],
            'InvoiceDate' => $row['InvoiceDate'],
            'CustomerGstin' => $row['CustomerGstin'],
            'CustomerName' => $row['CustomerName'],
            'PlaceOfSupply' => $row['PlaceOfSupply'],
            'TaxableValue' => gstFloat($row['TaxableValue']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalInvoiceAmount' => gstFloat($row['TotalInvoiceAmount']),
            'InvoiceType' => 'B2B',
        ];
    }

    // B. B2C Sales (aggregated by date)
    $b2cSql = "SELECT
                    x.InvoiceDate,
                    COUNT(*) AS InvoiceCount,
                    SUM(x.TaxableValue) AS TaxableValue,
                    SUM(x.Cgst) AS Cgst,
                    SUM(x.Sgst) AS Sgst,
                    SUM(x.Igst) AS Igst,
                    SUM(x.TotalAmount) AS TotalAmount
                FROM (
                    SELECT
                        i.id,
                        i.InvoiceDate,
                        SUM($taxableExpr) AS TaxableValue,
                        SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                        SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                        SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                        MAX(COALESCE(i.NetAmount, 0)) AS TotalAmount
                    $joins
                    WHERE $where
                      AND ($customerGstinExpr) = ''
                    GROUP BY i.id, i.InvoiceDate
                ) x
                GROUP BY x.InvoiceDate
                ORDER BY x.InvoiceDate ASC";

    $b2cRows = gstFetchRows($conn, $b2cSql);
    $b2cSales = [];
    $sr = 1;
    foreach ($b2cRows as $row) {
        $b2cSales[] = [
            'SrNo' => $sr++,
            'InvoiceDate' => $row['InvoiceDate'],
            'InvoiceCount' => (int) $row['InvoiceCount'],
            'TaxableValue' => gstFloat($row['TaxableValue']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalAmount' => gstFloat($row['TotalAmount']),
            'InvoiceType' => 'B2C',
        ];
    }

    // C. HSN Summary
    $hsnSql = "SELECT
                    $hsnCodeExpr AS HsnCode,
                    $hsnDescExpr AS Description,
                    SUM(COALESCE(d.Qty, 0)) AS TotalQty,
                    SUM($taxableExpr) AS TaxableValue,
                    SUM(COALESCE(d.CgstAmt, 0)) AS Cgst,
                    SUM(COALESCE(d.SgstAmt, 0)) AS Sgst,
                    SUM(COALESCE(d.IgstAmt, 0)) AS Igst,
                    SUM(COALESCE(d.CgstAmt, 0) + COALESCE(d.SgstAmt, 0) + COALESCE(d.IgstAmt, 0)) AS TotalTax
                $joins
                LEFT JOIN tbl_hsn_master h ON h.HsnCode = COALESCE(NULLIF(p.HsnCode, ''), NULLIF(d.HsnCode, ''))
                WHERE $where
                GROUP BY $hsnCodeExpr, $hsnDescExpr
                ORDER BY $hsnCodeExpr ASC";

    $hsnRows = gstFetchRows($conn, $hsnSql);
    $hsnSummary = [];
    $sr = 1;
    foreach ($hsnRows as $row) {
        $hsnSummary[] = [
            'SrNo' => $sr++,
            'HsnCode' => $row['HsnCode'],
            'Description' => $row['Description'],
            'TotalQty' => gstFloat($row['TotalQty']),
            'TaxableValue' => gstFloat($row['TaxableValue']),
            'Cgst' => gstFloat($row['Cgst']),
            'Sgst' => gstFloat($row['Sgst']),
            'Igst' => gstFloat($row['Igst']),
            'TotalTax' => gstFloat($row['TotalTax']),
        ];
    }

    $outlet = gstGetOutletInfo($conn, $filters['FrId']);

    gstRespond([
        'status' => 'success',
        'report' => 'GSTR-1',
        'filters' => $filters,
        'records' => $b2bSales,
        'outlet' => [
            'FrId' => (int) $filters['FrId'],
            'Name' => $outlet['Fname'] ?? '',
            'Gstin' => $outlet['GstNo'] ?? '',
            'State' => $outlet['StateName'] ?? '',
        ],
        'sections' => [
            'b2b_sales' => [
                'title' => 'B2B Sales',
                'columns' => [
                    'InvoiceNo', 'InvoiceDate', 'CustomerGstin', 'CustomerName',
                    'PlaceOfSupply', 'TaxableValue', 'Cgst', 'Sgst', 'Igst', 'TotalInvoiceAmount',
                ],
                'records' => $b2bSales,
                'totals' => gstSumTotals($b2bSales, [
                    'TaxableValue' => 'TaxableValue',
                    'Cgst' => 'Cgst',
                    'Sgst' => 'Sgst',
                    'Igst' => 'Igst',
                    'TotalInvoiceAmount' => 'TotalInvoiceAmount',
                ]),
            ],
            'b2c_sales' => [
                'title' => 'B2C Sales',
                'columns' => [
                    'InvoiceDate', 'InvoiceCount', 'TaxableValue', 'Cgst', 'Sgst', 'Igst', 'TotalAmount',
                ],
                'records' => $b2cSales,
                'totals' => gstSumTotals($b2cSales, [
                    'InvoiceCount' => 'InvoiceCount',
                    'TaxableValue' => 'TaxableValue',
                    'Cgst' => 'Cgst',
                    'Sgst' => 'Sgst',
                    'Igst' => 'Igst',
                    'TotalAmount' => 'TotalAmount',
                ]),
            ],
            'hsn_summary' => [
                'title' => 'HSN Summary',
                'columns' => [
                    'HsnCode', 'Description', 'TotalQty', 'TaxableValue', 'Cgst', 'Sgst', 'Igst', 'TotalTax',
                ],
                'records' => $hsnSummary,
                'totals' => gstSumTotals($hsnSummary, [
                    'TotalQty' => 'TotalQty',
                    'TaxableValue' => 'TaxableValue',
                    'Cgst' => 'Cgst',
                    'Sgst' => 'Sgst',
                    'Igst' => 'Igst',
                    'TotalTax' => 'TotalTax',
                ]),
            ],
        ],
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => 'Failed to fetch GSTR-1 report: ' . $e->getMessage(),
    ], 400);
}

?>
