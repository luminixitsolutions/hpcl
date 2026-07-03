<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    gstRespond([
        'status' => 'success',
        'module' => 'GST Reporting',
        'base_path' => '/flutter_api/fradmin/gst_report/',
        'common_params' => [
            'user_id' => 'Outlet / Franchise ID (required for reports)',
            'FromDate' => 'YYYY-MM-DD',
            'ToDate' => 'YYYY-MM-DD',
            'month' => '1-12 (optional, used by GSTR-3B)',
            'year' => 'YYYY (optional, used by GSTR-3B)',
            'gst_type' => 'ALL | B2B | B2C (GSTR-1 / Invoice Register)',
        ],
        'masters' => [
            [
                'name' => 'GST Rate Master',
                'list' => 'gst-rate-master.php?action=list&user_id=1',
                'save' => 'save-gst-rate.php (POST)',
            ],
            [
                'name' => 'HSN Master',
                'list' => 'hsn-master.php?action=list&user_id=1',
                'save' => 'save-hsn.php (POST)',
            ],
            [
                'name' => 'Assign GST To Product',
                'url' => 'assign-product-gst.php (POST ProdId, GstRateId, HsnCode)',
            ],
            [
                'name' => 'GST Calculator For Billing',
                'url' => 'calculate-gst.php?ProdId=1&rate=100&qty=1&same_state=1',
            ],
        ],
        'reports' => [
            [
                'name' => 'GSTR-1 Report',
                'url' => 'gstr1-report.php?user_id=1&FromDate=2025-11-01&ToDate=2025-11-30&gst_type=ALL',
                'sections' => ['b2b_sales', 'b2c_sales', 'hsn_summary'],
            ],
            [
                'name' => 'GSTR-3B Report',
                'url' => 'gstr3b-report.php?user_id=1&month=11&year=2025',
                'sections' => ['outward_supplies', 'tax_liability'],
            ],
            [
                'name' => 'Daily GST Summary',
                'url' => 'daily-gst-summary.php?user_id=1&FromDate=2025-11-01&ToDate=2025-11-30',
            ],
            [
                'name' => 'Monthly GST Summary',
                'url' => 'monthly-gst-summary.php?user_id=1&FromDate=2025-01-01&ToDate=2025-12-31',
            ],
            [
                'name' => 'GST Invoice Register',
                'url' => 'gst-invoice-register.php?user_id=1&FromDate=2025-11-01&ToDate=2025-11-30&gst_type=ALL',
            ],
        ],
        'notes' => [
            'All report APIs return JSON like inventory reports.',
            'B2B is detected when customer GSTIN exists on invoice or linked customer master.',
            'B2C is used when customer GSTIN is blank.',
            'GST rates are controlled from tbl_gst_master only.',
            'Tables are auto-created on first API call; old invoices continue to work.',
        ],
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => $e->getMessage(),
    ], 400);
}

?>
