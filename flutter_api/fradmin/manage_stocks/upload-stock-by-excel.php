<?php 
session_start();
include_once '../config.php';
//include_once 'auth.php';
require_once('../excel_vendor/php-excel-reader/excel_reader2.php');
require_once('../excel_vendor/SpreadsheetReader.php');

$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products-2025";
$Page = "Download-Customer-Products-Excel-2025";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>📦 Upload & Manage Product Stock - Maha Chai</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f6f4fa;
      font-family: 'Inter', 'Segoe UI', sans-serif;
      color: #333;
    }

    .card {
      border: none;
      border-radius: 18px;
      box-shadow: 0 4px 18px rgba(90, 60, 200, 0.08);
      background-color: #fff;
      padding: 24px;
    }

    h4.page-title {
      font-weight: 700;
      color: #5b3cc4;
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.5rem;
    }

    table.dataTable th {
      background-color: #6a4fe0 !important;
      color: #fff !important;
      font-weight: 600;
      font-size: 13px;
      text-align: center;
      border: 1px solid #e3dfff;
    }

    table.dataTable td {
      text-align: center;
      font-size: 13px;
      border: 1px solid #eae6ff;
      vertical-align: middle;
    }

    .form-section {
      background-color: #faf9ff;
      border: 1px solid #e0dafc;
      border-radius: 14px;
      padding: 18px;
      margin-top: 25px;
    }

    label.form-label {
      font-weight: 600;
      color: #5b3cc4;
      font-size: 13px;
    }

    .btn-primary {
      background-color: #6a4fe0 !important;
      border: none !important;
      border-radius: 10px;
      font-weight: 600;
      font-size: 13px;
      padding: 8px 18px;
    }

    .btn-primary:hover {
      background-color: #573ec6 !important;
    }

    .btn-success {
      background-color: #00b894 !important;
      border: none;
      font-weight: 600;
      font-size: 13px;
      border-radius: 10px;
      padding: 8px 18px;
    }

    .btn-success:hover {
      background-color: #00a884 !important;
    }

    .table-container {
      margin-top: 15px;
    }

    .upload-icon {
      color: #6a4fe0;
      font-size: 20px;
      margin-right: 8px;
    }

    .export-btn {
      background-color: #00b894 !important;
      border: none !important;
      font-size: 13px;
      border-radius: 10px;
      padding: 7px 16px;
      color: #fff;
      font-weight: 600;
    }

    .export-btn:hover {
      background-color: #00a884 !important;
    }

    .footer-note {
      text-align: center;
      font-size: 12px;
      color: #888;
      margin-top: 15px;
    }
  </style>
</head>

<body>
    <?php
if (isset($_POST['submit5'])) {
    $BillSoftFrId = $_POST['FrId'];
    $StockDate = $_POST['StockDate'];
    $CreatedDate = date('Y-m-d H:i:s');
    $Narration = addslashes(trim($_POST['Narration']));
    $CreatedBy = $_SESSION['Admin']['id'];

    // Allowed Excel MIME types
    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    // Validate uploaded file
    if (isset($_FILES["file"]["type"]) && in_array($_FILES["file"]["type"], $allowedFileType)) {
        $targetPath = '../excelfiles/' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());

        $insertedCount = 0;

        for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);

            foreach ($Reader as $Row) {
                 $MainProdId = trim($Row[1] ?? '');
                $ProdId = trim($Row[2] ?? '');
                $PurchasePrice = trim($Row[5] ?? '');
                $SellPrice = trim($Row[6] ?? '');
                $Qty = trim($Row[11] ?? '');

                // Skip empty rows
                if (empty($ProdId) || empty($Qty)) continue;

                // Insert into main stock table
                $insertQuery = "
                    INSERT INTO tbl_cust_prod_stock_2025 
                    (MainProdId,ProdId, Qty, CreatedBy, StockDate, Narration, Status, UserId, CreatedDate, FrId, PurchasePrice, SellPrice)
                    VALUES 
                    ('$MainProdId','$ProdId', '$Qty', '$CreatedBy', '$StockDate', '$Narration', 'Cr', '$BillSoftFrId', '$CreatedDate', '$BillSoftFrId', '$PurchasePrice', '$SellPrice')
                ";
                if ($conn->query($insertQuery)) {
                    $InvId = $conn->insert_id;
                    $insertedCount++;

                    // Backup Entry
                    $backupQuery = "
                        INSERT INTO tbl_cust_prod_stock_2025_backup 
                        (MainProdId,ProdId, Qty, CreatedBy, StockDate, Narration, Status, UserId, CreatedDate, FrId, PurchasePrice, SellPrice, orgstockid)
                        VALUES 
                        ('$MainProdId','$ProdId', '$Qty', '$CreatedBy', '$StockDate', '$Narration', 'Cr', '$BillSoftFrId', '$CreatedDate', '$BillSoftFrId', '$PurchasePrice', '$SellPrice', '$InvId')
                    ";
                    $conn->query($backupQuery);

                    // Update product purchase price
                    $conn->query("UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'");

                   
                }
            }
        }
        ?>
        <script>
        alert("✅ Successfully imported <?php echo $insertedCount; ?> stock records!");
        window.location.href = "upload-stock-by-excel.php?user_id=<?php echo $BillSoftFrId; ?>";
        </script>
        <?php
    } else {
        ?>
        <script>
        alert("❌ Invalid File Type. Please upload only Excel (.xls or .xlsx) files.");
        window.history.back();
        </script>
        <?php
    }
}
?>
<!--<div class="container-fluid py-4 px-3 px-md-4">
  <div class="card">-->
  <div style="background-color: white;padding:10px;">
  <div>
    <h4 class="page-title">📊 Upload Product Stock Excel</h4>

    <!-- Table Section -->
    <!--<div class="table-container">
      <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <h6 class="fw-semibold mb-0 text-secondary">📦 Current Product Stock List</h6>
       <button id="exportExcel" class="btn export-btn"><i class="bi bi-file-earmark-excel"></i> Download Excel</button>
      </div>

      <div class="table-responsive">
        <table id="productTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Main Product ID</th>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Barcode No</th>
              <th>Purchase Price</th>
              <th>Sell Price</th>
              <th>Credit</th>
              <th>Debit</th>
              <th>Balance</th>
              <th>Min Qty</th>
               <th>Add Stock Qty</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $BillSoftFrId = $_GET['user_id'] ?? 1;
            $sql = "SELECT p.ProdId AS MainId,p.BarcodeNo,p.CreatedBy AS FrId, p.id AS ProdId, p.ProductName, 
                           COALESCE(p.MinQty, 0) AS MinQty, p.PurchasePrice,p.MinPrice 
                    FROM tbl_cust_products_2025 p 
                    INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                    WHERE p.CreatedBy = '$BillSoftFrId' 
                    AND p.ProdType = 0 AND p.ProdType2 IN (1) AND p.delete_flag=0 AND p.checkstatus=1 
                    GROUP BY p.id ORDER BY p.ProductName ASC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc()){
               $sql2 = "SELECT SUM(creditqty) AS creditqty,SUM(debitqty) AS debitqty,
                              SUM(creditqty)-SUM(debitqty) AS balqty 
                       FROM (
                          SELECT (CASE WHEN Status='Dr' THEN SUM(Qty) ELSE '0' END) AS debitqty,
                                 (CASE WHEN Status='Cr' THEN SUM(Qty) ELSE '0' END) AS creditqty
                          FROM tbl_cust_prod_stock_2025 
                          WHERE FrId='$BillSoftFrId' AND ProdId='".$row['ProdId']."' AND ProdType=0 
                          GROUP BY Status
                       ) AS a";
              $row2 = getRecord($sql2);
              echo "<tr>
                <td>{$row['MainId']}</td>
                <td>{$row['ProdId']}</td>
                <td>{$row['ProductName']}</td>
                <td>{$row['BarcodeNo']}</td>
                <td>{$row['PurchasePrice']}</td>
                <td>{$row['MinPrice']}</td>
                <td>{$row2['creditqty']}</td>
                <td>{$row2['debitqty']}</td>
                <td>{$row2['balqty']}</td>
                <td>{$row['MinQty']}</td>
                <td></td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>-->

    <!-- Upload Excel Section -->
    <div class="form-section mt-4">
      <!--<h6 class="fw-semibold mb-3 text-secondary"><i class="bi bi-upload upload-icon"></i>Upload Excel to Add Stock</h6>-->

      <form id="excelUploadForm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="FrId" value="<?php echo $BillSoftFrId; ?>">

        <div class="row g-3 align-items-end">
          <div class="col-md-2">
            <label class="form-label">Stock Date</label>
            <input type="date" name="StockDate" class="form-control" required value="<?php echo date('Y-m-d');?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Upload Excel File</label>
            <input type="file" name="file" accept=".xls,.xlsx" class="form-control" required>
          </div>
          <div class="col-md-5">
            <label class="form-label">Narration</label>
            <input type="text" name="Narration" class="form-control" placeholder="Enter remarks or purpose...">
          </div>
          <div class="col-md-2 text-end">
            <button type="submit" name="submit5" class="btn btn-primary"><i class="bi bi-cloud-arrow-up"></i> Submit</button>
          </div>
        </div>
      </form>
    </div>

    <div class="footer-note">💡 Tip: Download the Excel file, fill in stock details, and upload it back to update records.</div>
  </div>
</div>

<!-- JS Imports -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
  // === Initialize DataTable with Excel Export ===
  $('#productTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel"></i> Export Excel',
        className: 'btn btn-success btn-sm',
        title: 'Product_Stock_Report'
      }
    ],
    scrollX: true,
    pageLength: 10
  });

  // === Manual Export Button ===
  $('#exportExcel').on('click', function() {
    $('.buttons-excel').click();
  });
});
</script>
</body>
</html>
