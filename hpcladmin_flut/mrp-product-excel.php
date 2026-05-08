<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once('excel_vendor/php-excel-reader/excel_reader2.php');
require_once('excel_vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products-2025";
$Page = "Download-Customer-Products-Excel-2025";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>Product Raw Stock Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<?php 
if (isset($_POST['submit5'])) {

    $BillSoftFrId = $_POST['FrId'] ?? 0;
    $CreatedDate = date('Y-m-d');
    $CreatedBy = $_SESSION['user_id'];
    $insertCount = 0;
    $skipCount = 0;
    $SrNo = 0;

    // Allowed Excel Types
    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        // Upload Excel
        $targetPath = 'excelfiles/products/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        // Read Excel File
        $Reader = new SpreadsheetReader($targetPath);

        foreach ($Reader as $Row) {

            $ProductName = trim($Row[1] ?? '');

            // Skip header or empty rows
            if ($ProductName == '' || strtolower($ProductName) == 'productname') {
                continue;
            }

            $SrNo++;

            $BarcodeNo = trim($Row[2] ?? '');
            $CatId = trim($Row[3] ?? '');
            $SubCatId = trim($Row[4] ?? '');

            $SgstPer = trim($Row[5] ?? '0');
            $CgstPer = trim($Row[6] ?? '0');
            $IgstPer = trim($Row[7] ?? '0');

            $CgstAmt = trim($Row[8] ?? '0');
            $SgstAmt = trim($Row[9] ?? '0');
            $IgstAmt = trim($Row[10] ?? '0');

            $PurchasePrice = trim($Row[11] ?? '0');
            $ProdPrice = trim($Row[12] ?? '0');
            $GstAmt = trim($Row[13] ?? '0');
            $MinPrice = trim($Row[14] ?? '0');

            //$Status = trim($Row[15] ?? '1');

            // ----------------------------------------------
            // INSERT ONLY INTO tbl_cust_products2
            // ----------------------------------------------
            $sql = "INSERT INTO tbl_cust_products2 SET 
                ProductName='$ProductName', BarcodeNo='$BarcodeNo',
                CatId='$CatId', SubCatId='$SubCatId',
                PurchasePrice='$PurchasePrice', ProdPrice='$ProdPrice',
                MinPrice='$MinPrice', Status='$Status',
                SgstPer='$SgstPer', CgstPer='$CgstPer', IgstPer='$IgstPer',
                GstAmt='$GstAmt', CgstAmt='$CgstAmt', SgstAmt='$SgstAmt', IgstAmt='$IgstAmt',
                SrNo='$SrNo', CreatedDate='$CreatedDate', CreatedBy='$CreatedBy',
                 ProdType2='1'";

            if ($conn->query($sql)) {
                $insertCount++;
            } else {
                $skipCount++;
            }
        }
        
       $conn->query("DELETE FROM tbl_cust_products2 WHERE ProductName='' OR ProductName='Product Name'");

        echo "<script>
                alert('Excel Import Completed! Added: $insertCount | Failed: $skipCount');
                window.location.href='mrp-product-excel.php';
              </script>";

    } else {
        echo "<script>alert('Invalid File Format! Please upload a .xls or .xlsx file.');</script>";
    }
}
?>






<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Upload MRP Product Excel <span style="color:red;font-size:12px;">(Download Below Sample Excel Format For Uploading Excel Sheet Record)</span>
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
         <thead>
    <tr>
        <th>Sr No</th>
      <th>Product Name</th>
      <th>Barcode No</th>
      <th>Category Id</th>
      <th>SubCategory Id</th>
      <th>Sgst Percentage</th>
      <th>Cgst Percentage</th>
      <th>Igst Percentage</th>
      <th>Cgst Amt</th>
      <th>Sgst Amt</th>
      <th>Igst Amt</th>
      <th>Purchase Price</th>
      <th>Without Gst Price</th>
      <th>Gst Amt</th>
      <th>Price</th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td>1</td>
      <td>5ml Pouch</td>
      <td>12345</td>
      <td>1</td>
      <td>2</td>
      <td>2.5</td>
      <td>2.5</td>
      <td>0</td>
      <td>0.24</td>
      <td>0.24</td>
      <td>0</td>
      <td>5.00</td>
      <td>9.52</td>
       <td>0.48</td>
       <td>10.00</td>
    </tr>
  </tbody>
    </table>
</div>

<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
<div class="form-row">

       

<input type="hidden" name="FrId" id="FrId" class="form-control" value="<?php echo $BillSoftFrId;?>" autocomplete="off">
<div class="form-group col-md-2">
<label class="form-label">Date </label>
<input type="date" name="StockDate" id="StockDate" class="form-control" value="" autocomplete="off" required>
</div>
<div class="form-group col-md-3">
   <label class="form-label">Upload Excel File </label>
     <input type="file" name="file" id="" class="form-control"
                                                placeholder=""
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div>


<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit5" class="btn btn-primary btn-finish" id="submit">Submit</button>
</div>

</div>

</form>

</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,   // remove default auto-title
                filename: 'product_excel',
                customize: function(xlsx) {
                    // No custom header rows — export only actual table data
                }
            }
        ]
    });
});
</script>



</body>
</html>
