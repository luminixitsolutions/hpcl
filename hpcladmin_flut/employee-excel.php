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
                $EmployeeId     = isset($Row[0]) ? mysqli_real_escape_string($conn, trim($Row[0])) : '';
                $Fname          = isset($Row[1]) ? mysqli_real_escape_string($conn, trim($Row[1])) : '';
                $Address        = isset($Row[2]) ? mysqli_real_escape_string($conn, trim($Row[2])) : '';
                $Password       = isset($Row[3]) ? mysqli_real_escape_string($conn, trim($Row[3])) : '12345';
                $Phone          = isset($Row[4]) ? mysqli_real_escape_string($conn, trim($Row[4])) : '';
                $Phone2         = isset($Row[5]) ? mysqli_real_escape_string($conn, trim($Row[5])) : '';
                $EmailId        = isset($Row[6]) ? mysqli_real_escape_string($conn, trim($Row[6])) : '';
                $Designation    = isset($Row[7]) ? mysqli_real_escape_string($conn, trim($Row[7])) : '';
                $AadharNo       = isset($Row[8]) ? mysqli_real_escape_string($conn, trim($Row[8])) : '';
                $JoinDate       = isset($Row[9]) ? mysqli_real_escape_string($conn, trim($Row[9])) : '';
                $PerDaySalary   = isset($Row[10]) ? mysqli_real_escape_string($conn, trim($Row[10])) : '';
                $Status         = isset($Row[11]) ? mysqli_real_escape_string($conn, trim($Row[11])) : '1';
                $AccountName    = isset($Row[12]) ? mysqli_real_escape_string($conn, trim($Row[12])) : '';
                $BankName       = isset($Row[13]) ? mysqli_real_escape_string($conn, trim($Row[13])) : '';
                $AccountNo      = isset($Row[14]) ? mysqli_real_escape_string($conn, trim($Row[14])) : '';
                $Branch         = isset($Row[15]) ? mysqli_real_escape_string($conn, trim($Row[15])) : '';
                $IfscCode       = isset($Row[16]) ? mysqli_real_escape_string($conn, trim($Row[16])) : '';
                $UpiNo          = isset($Row[17]) ? mysqli_real_escape_string($conn, trim($Row[17])) : '';
                $Roll           = '63'; // Default: Employee

                // Skip empty essential fields
                if (empty($Fname) || empty($Phone)) continue;

                // Prevent duplicate (based on phone or aadhar)
                $check = "SELECT id FROM tbl_users WHERE Phone='$Phone' AND Roll=63 LIMIT 1";
                $result = $conn->query($check);

                if ($result && $result->num_rows == 0) {

                    // ✅ Insert employee record in tbl_users
                    $insert_user = "
                        INSERT INTO tbl_users 
                        SET 
                            CustomerId='$EmployeeId',
                            Fname='$Fname',
                            Address='$Address',
                            Password='$Password',
                            Phone='$Phone',
                            Phone2='$Phone2',
                            EmailId='$EmailId',
                            Designation='$Designation',
                            AadharNo='$AadharNo',
                            JoinDate='$JoinDate',
                            PerDaySalary='$PerDaySalary',
                            Roll='$Roll',
                            Status='$Status',
                            AccountName='$AccountName',
                            BankName='$BankName',
                            AccountNo='$AccountNo',
                            Branch='$Branch',
                            IfscCode='$IfscCode',
                            UpiNo='$UpiNo',
                            CreatedBy='$user_id',
                            CreatedDate=NOW()
                    ";

                    if ($conn->query($insert_user)) {
                        $NewUserId = mysqli_insert_id($conn);

                        // ✅ Copy same record into tbl_users_bill (same ID)
                        $sql_copy = "
                            INSERT INTO tbl_users_bill
                            SELECT * FROM tbl_users WHERE id='$NewUserId'
                        ";
                        $conn->query($sql_copy);

                        // ✅ Log import action
                        $createddate = date('Y-m-d H:i:s');
                        $log = "
                            INSERT INTO tbl_user_logs 
                            SET 
                                userid='$user_id',
                                frid='$BillSoftFrId',
                                url='$Page',
                                action='Employee Excel Data Uploaded',
                                invid='$NewUserId',
                                createddate='$createddate',
                                roll='employee-excel'
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
                alert('Employee Excel Import Complete! Added: $insertCount | Skipped (Already Exists): $skipCount');
                window.location.href='employee-excel.php';
              </script>";
    } else {
        echo "<script>alert('Invalid File Type! Please upload a valid Excel file (.xls or .xlsx).');</script>";
    }
}
?>





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Upload Employee Excel <span style="color:red;font-size:12px;">(Download Below Sample Excel Format For Uploading Excel Sheet Record)</span>
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
         <thead>
    <tr>
      <th>Employee Id</th>
      <th>Employee Name</th>
      <th>Permanent Address</th>
      <th>Password</th>
      <th>Mobile No</th>
      <th>Another Mobile No</th>
      <th>Email Id</th>
      <th>Designation</th>
      <th>Aadhar Card No</th>
      <th>Date Of Joining</th>
      <th>Per Day Salary</th>
      <th>Status</th>
      <th>Bank Holder Name</th>
      <th>Bank Name</th>
      <th>Account No</th>
      <th>Branch</th>
      <th>IFSC Code</th>
      <th>UPI ID</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>E12345</td>
      <td>Ramesh Kumar</td>
      <td>123 MG Road, Pune</td>
      <td>12345</td>
      <td>9876543210</td>
      <td>9123456780</td>
      <td>ramesh@example.com</td>
      <td>Shop Manager</td>
      <td>123456789012</td>
      <td>2024-02-01</td>
      <td>500</td>
      <td>1</td>
      <td>Ramesh Kumar</td>
      <td>State Bank of India</td>
      <td>1234567890</td>
      <td>Pune Main</td>
      <td>SBIN0000123</td>
      <td>ramesh@upi</td>
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
                filename: 'employee_excel',
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
