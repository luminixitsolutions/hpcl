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
    $user_id = $_SESSION['user_id']; // Assuming session contains current admin ID

    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

        $targetPath = 'excelfiles/accounts/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());
        $insertCount = 0;
        $skipCount = 0;

       for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);

            foreach ($Reader as $index => $Row) {
                // Skip header row
                if ($index == 0) continue;

                // Excel column mapping
                $Fname       = isset($Row[0]) ? mysqli_real_escape_string($conn, trim($Row[0])) : ''; // Vendor Name
                $EmailId     = isset($Row[1]) ? mysqli_real_escape_string($conn, trim($Row[1])) : '';
                $Phone       = isset($Row[2]) ? mysqli_real_escape_string($conn, trim($Row[2])) : '';
                $Phone2      = isset($Row[3]) ? mysqli_real_escape_string($conn, trim($Row[3])) : '';
                $CountryId   = isset($Row[4]) ? mysqli_real_escape_string($conn, trim($Row[4])) : '';
                $StateId     = isset($Row[5]) ? mysqli_real_escape_string($conn, trim($Row[5])) : '';
                $CityId      = isset($Row[6]) ? mysqli_real_escape_string($conn, trim($Row[6])) : '';
                $Pincode     = isset($Row[7]) ? mysqli_real_escape_string($conn, trim($Row[7])) : '';
                $Address     = isset($Row[8]) ? mysqli_real_escape_string($conn, trim($Row[8])) : '';
                $Status      = isset($Row[9]) ? mysqli_real_escape_string($conn, trim($Row[9])) : '1';
                $Roll        = '3'; // For Vendor type

                // Mandatory check
                if (empty($Fname) || empty($Phone)) continue;

                // Check duplicate vendor (based on phone)
                $check = "SELECT id FROM tbl_users WHERE Phone='$Phone' AND Roll=3 LIMIT 1";
                $result = $conn->query($check);

                if ($result && $result->num_rows == 0) {
                    // ✅ Insert new Vendor record
                    $insert_user = "
                        INSERT INTO tbl_users 
                        SET 
                            Fname='$Fname',
                            EmailId='$EmailId',
                            Phone='$Phone',
                            Phone2='$Phone2',
                            CountryId='$CountryId',
                            StateId='$StateId',
                            CityId='$CityId',
                            Pincode='$Pincode',
                            Address='$Address',
                            Status='$Status',
                            Roll='$Roll',
                            CreatedBy='$user_id',
                            CreatedDate=NOW()
                    ";

                    if ($conn->query($insert_user)) {
                        $NewUserId = mysqli_insert_id($conn);

                        // ✅ Copy same record into tbl_users_bill
                        $sql_copy = "INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$NewUserId'";
                        $conn->query($sql_copy);

                        // ✅ Log action
                        $createddate = date('Y-m-d H:i:s');
                        $log = "
                            INSERT INTO tbl_user_logs 
                            SET 
                                userid='$user_id',
                                frid='$BillSoftFrId',
                                url='$Page',
                                action='Vendor Excel Data Uploaded',
                                invid='$NewUserId',
                                createddate='$createddate',
                                roll='vendor-excel'
                        ";
                        $conn->query($log);

                        $insertCount++;
                    }
                } else {
                    $skipCount++;
                }
            }
        }

        echo "<script>
                alert('Vendor Excel Import Complete! Added: $insertCount | Skipped (Already Exists): $skipCount');
                window.location.href='vendor-excel.php';
              </script>";
    } else {
        echo "<script>alert('Invalid File Type! Please upload a valid Excel file (.xls or .xlsx).');</script>";
    }
}
?>





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Upload Vendor Excel <span style="color:red;font-size:12px;">(Download Below Sample Excel Format For Uploading Excel Sheet Record)</span>
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
         <thead>
    <tr>
      <th>Vendor Name</th>
      <th>Email Id</th>
      <th>Mobile No</th>
      <th>Another Mobile No</th>
      <th>Country Id</th>
      <th>State Id</th>
      <th>City Id</th>
      <th>Pincode No</th>
      <th>Address</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>ABC Traders</td>
      <td>abc.traders@example.com</td>
      <td>9876543210</td>
      <td>9123456780</td>
      <td>1</td>
      <td>27</td>
      <td>101</td>
      <td>411001</td>
      <td>123 MG Road, Pune</td>
      <td>1</td>
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
                filename: 'vendor_excel',
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
