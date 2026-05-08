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
    $BillSoftFrId = $_POST['FrId'];
    $StockDate = $_POST['StockDate'];
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration'] ?? ''));
    
    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
  
    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'excelfiles/country/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());
        $insertCount = 0;
        $skipCount = 0;

        for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);

            foreach ($Reader as $Row) {
                // Column index might change depending on Excel structure
                $CatId = "";
                if (isset($Row[1])) {
                    $CatId = mysqli_real_escape_string($conn, trim($Row[1]));
                }
                
                $Name = "";
                if (isset($Row[2])) {
                    $Name = mysqli_real_escape_string($conn, trim($Row[2]));
                }

                // Skip empty rows or header rows
                if (empty($Name) || strtolower($Name) == 'region name') {
                    continue;
                }

                // Check if the country already exists
                $check = "SELECT id FROM tbl_sub_zone WHERE LOWER(Name) = LOWER('$Name') AND CatId='$CatId' LIMIT 1";
                $result = $conn->query($check);

                if ($result && $result->num_rows == 0) {
                    // Insert new country
                    $qx = "INSERT INTO tbl_sub_zone SET CatId='$CatId',Name='$Name', Status='1'";
                    if ($conn->query($qx)) {
                        $InvId = mysqli_insert_id($conn);
                        $createddate = date('Y-m-d H:i:s');

                        // Log action
                        $sql = "INSERT INTO tbl_user_logs 
                                SET userid='$user_id',
                                    frid='$BillSoftFrId',
                                    url='$Page',
                                    action='New Region Excel Data Uploaded',
                                    invid='$InvId',
                                    createddate='$createddate',
                                    roll='region-excel'";
                        $conn->query($sql);
                        $insertCount++;
                    }
                } else {
                    $skipCount++;
                }
            }
        }

        // Remove header row if accidentally imported
        $conn->query("DELETE FROM tbl_sub_zone WHERE Name='Region Name'");

        echo "<script>
                alert('Excel Data Imported Successfully. Added: $insertCount | Skipped (Already Exists): $skipCount');
                window.location.href='region-excel.php';
              </script>";
    } else {
        echo "<script>alert('Invalid File Type. Please upload an Excel file (.xls or .xlsx).');</script>";
    }
}
?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Upload Region Excel <span style="color:red;font-size:12px;">(Download Below Sample Excel Format For Uploading Excel Sheet Record)</span>
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
              
                <th>Zone Id</th>
                <th>Region Name</th>
                
            
            </tr>
        </thead>
        <tbody>
           
            <tr>
                <td>1</td>
                <td>1</td>
                <td>Region 1</td>
                
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
                filename: 'region_excel',
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
