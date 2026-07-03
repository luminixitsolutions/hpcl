<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once 'ajax_files/mrp_product_import_helper.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Customer-Products-2025';
$Page = 'Download-Customer-Products-Excel-2025';

$brands = getList("SELECT id, Fname FROM tbl_users WHERE Status='1' AND Roll=3 ORDER BY Fname");
$categories = getList("SELECT id, Name FROM tbl_cust_category_2025 WHERE Status='1' AND ProdType=0 ORDER BY Name");

if (isset($_POST['submit5'])) {
    $admin_id = (int) ($_SESSION['Admin']['id'] ?? 0);
    $defaultBrandId = trim((string) ($_POST['DefaultBrandId'] ?? ''));

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    $ext = strtolower(pathinfo($_FILES['file']['name'] ?? '', PATHINFO_EXTENSION));
    $mimeOk = in_array($_FILES['file']['type'] ?? '', $allowedFileType, true) || in_array($ext, ['xls', 'xlsx', 'csv'], true);

    if (!$mimeOk || empty($_FILES['file']['tmp_name'])) {
        echo "<script>alert('Invalid file. Please upload a .xls or .xlsx file.'); window.history.back();</script>";
        exit;
    }

    if (!is_dir('excelfiles/products')) {
        mkdir('excelfiles/products', 0755, true);
    }

    $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['file']['name']);
    $targetPath = 'excelfiles/products/' . time() . '_' . $safeName;
    move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

    $result = mrp_import_process_file($conn, $targetPath, $admin_id, $defaultBrandId);

    $msg = 'Import completed. Added: ' . $result['inserted'] . ', Failed: ' . $result['skipped'];
    if (!empty($result['errors'])) {
        $msg .= "\\n\\n" . implode("\\n", array_slice($result['errors'], 0, 8));
        if (count($result['errors']) > 8) {
            $msg .= "\\n... and " . (count($result['errors']) - 8) . ' more';
        }
    }

    echo "<script>alert(" . json_encode($msg) . "); window.location.href='mrp-product-excel.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
    <title><?php echo $Proj_Title; ?> | MRP Product Excel</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <style>
        .ref-table { font-size: 12px; }
        .ref-table th { background: #f5f5f5; }
        .excel-note { color: #c0392b; font-size: 13px; }
    </style>
</head>
<body>

<div class="layout-wrapper layout-1 layout-without-sidenav">
    <div class="layout-inner">
        <?php include_once 'top_header.php';
        include_once 'sidebar.php'; ?>

        <div class="layout-container">
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-0">Upload MRP Product Excel</h4>
                    <p class="excel-note mb-3">
                        Download the sample Excel below. Columns match <strong>Add Customer Product</strong> form fields.
                        Required: Product Name, Brand Id, Category Id, Purchase Price, MRP Price.
                    </p>

                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">Sample Excel Format (export this table)</div>
                        <div class="card-datatable table-responsive p-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Product Name</th>
                                        <th>Barcode No</th>
                                        <th>Brand Id</th>
                                        <th>Category Id</th>
                                        <th>Sub Category Id</th>
                                        <th>Purchase Price</th>
                                        <th>MRP Price</th>
                                        <th>Discount %</th>
                                        <th>CGST %</th>
                                        <th>SGST %</th>
                                        <th>IGST %</th>
                                        <th>Price Wo GST</th>
                                        <th>GST Amt</th>
                                        <th>Final Price</th>
                                        <th>CGST Amt</th>
                                        <th>SGST Amt</th>
                                        <th>IGST Amt</th>
                                        <th>Unit</th>
                                        <th>Min Stock Qty</th>
                                        <th>Status</th>
                                        <th>Transfer</th>
                                        <th>Sort Sr No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>5ml Pouch</td>
                                        <td>12345</td>
                                        <td><?php echo !empty($brands[0]['id']) ? (int) $brands[0]['id'] : 1; ?></td>
                                        <td><?php echo !empty($categories[0]['id']) ? (int) $categories[0]['id'] : 1; ?></td>
                                        <td></td>
                                        <td>5.00</td>
                                        <td>10.00</td>
                                        <td>0</td>
                                        <td>2.5</td>
                                        <td>2.5</td>
                                        <td>0</td>
                                        <td>9.52</td>
                                        <td>0.48</td>
                                        <td>10.00</td>
                                        <td>0.24</td>
                                        <td>0.24</td>
                                        <td>0</td>
                                        <td>PCS</td>
                                        <td>5</td>
                                        <td>1</td>
                                        <td>1</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header font-weight-bold">Brand / Vendor Ids</div>
                                <div class="table-responsive">
                                    <table class="table table-sm ref-table mb-0">
                                        <thead><tr><th>Id</th><th>Name</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($brands as $b) { ?>
                                            <tr><td><?php echo (int) $b['id']; ?></td><td><?php echo htmlspecialchars($b['Fname']); ?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header font-weight-bold">Category Ids</div>
                                <div class="table-responsive">
                                    <table class="table table-sm ref-table mb-0">
                                        <thead><tr><th>Id</th><th>Name</th></tr></thead>
                                        <tbody>
                                            <?php foreach ($categories as $c) { ?>
                                            <tr><td><?php echo (int) $c['id']; ?></td><td><?php echo htmlspecialchars($c['Name']); ?></td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="padding: 15px;">
                        <form method="post" enctype="multipart/form-data" autocomplete="off">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-3">
                                    <label class="form-label">Default Brand (if missing in Excel)</label>
                                    <select name="DefaultBrandId" class="form-control">
                                        <option value="">— Select —</option>
                                        <?php foreach ($brands as $b) { ?>
                                        <option value="<?php echo (int) $b['id']; ?>"><?php echo htmlspecialchars($b['Fname']); ?> (<?php echo (int) $b['id']; ?>)</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-label">Upload Excel File <span class="text-danger">*</span></label>
                                    <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="submit" name="submit5" class="btn btn-primary btn-block">Import</button>
                                </div>
                            </div>
                            <small class="text-muted">Status: 1 = Publish, 0 = Not Publish &nbsp;|&nbsp; Transfer: 1 = Yes, 0 = No &nbsp;|&nbsp; Price columns auto-calculate from MRP if left blank.</small>
                        </form>
                    </div>
                </div>

                <?php include_once 'footer.php'; ?>
            </div>
        </div>
    </div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>

<?php include_once 'footer_script.php'; ?>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        scrollX: true,
        dom: 'Bfrtip',
        paging: false,
        searching: false,
        info: false,
        buttons: [{
            extend: 'excelHtml5',
            title: null,
            filename: 'mrp_product_sample',
        }],
    });
});
</script>
</body>
</html>
