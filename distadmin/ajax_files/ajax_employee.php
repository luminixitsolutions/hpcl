<?php
session_start();
include_once '../config.php';
//include('../../libs/phpqrcode/qrlib.php');

$user_id = $_SESSION['Admin']['id'] ?? 0;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // enable exceptions

// === Helper Functions ===
function esc($conn, $val) { return mysqli_real_escape_string($conn, trim($val)); }
function post($key, $default = '') { return $_POST[$key] ?? $default; }
function handleUpload($key, $old = '', $path = '../../uploads/') {
    if (!isset($_FILES[$key]) || !is_uploaded_file($_FILES[$key]['tmp_name'])) return $old;
    $rand = rand(1, 100);
    $name = basename($_FILES[$key]['name']);
    $safe = str_replace(' ', '_', pathinfo($name, PATHINFO_FILENAME));
    $ext = '.' . pathinfo($name, PATHINFO_EXTENSION);
    $file = "{$rand}_{$safe}{$ext}";
    $dest = rtrim($path, '/') . '/' . $file;
    return move_uploaded_file($_FILES[$key]['tmp_name'], $dest) ? $file : $old;
}

try {
    if ($_POST['action'] === 'Save') {
        $id = post('id');
        $CreatedDate = date('Y-m-d');

        // === Capture all fields ===
        $Fname = esc($conn, post('Fname'));
        $Mname = esc($conn, post('Mname'));
        $Lname = esc($conn, post('Lname'));
        $Phone = esc($conn, post('Phone'));
        $EmailId = esc($conn, post('EmailId'));
        $Phone2 = esc($conn, post('Phone2'));
        $Password = esc($conn, post('Password'));
        $CountryId = esc($conn, post('CountryId'));
        $StateId = esc($conn, post('StateId'));
        $CityId = esc($conn, post('CityId'));
        $Address = esc($conn, post('Address'));
        $GstNo = esc($conn, post('GstNo'));
        $Pincode = esc($conn, post('Pincode'));
        $Details = esc($conn, post('Details'));
        $FatherPhone = esc($conn, post('FatherPhone'));
        $Designation = esc($conn, post('Designation'));
        $Dob = esc($conn, post('Dob'));
        $AadharNo = esc($conn, post('AadharNo'));
        $BloodGroup = esc($conn, post('BloodGroup'));
        $JoinDate = esc($conn, post('JoinDate'));
        $EmailId2 = esc($conn, post('EmailId2'));
        $PerDaySalary = esc($conn, post('PerDaySalary'));
        $Status = esc($conn, post('Status'));
        $CatId = esc($conn, post('CatId'));
        $Roll = esc($conn, post('Roll'));
        $PanNo = esc($conn, post('PanNo'));
        $CompId = esc($conn, post('CompId'));
        $BranchId = esc($conn, post('BranchId'));
        $AccountName = esc($conn, post('AccountName'));
        $BankName = esc($conn, post('BankName'));
        $AccountNo = esc($conn, post('AccountNo'));
        $IfscCode = esc($conn, post('IfscCode'));
        $Branch = esc($conn, post('Branch'));
        $UpiNo = esc($conn, post('UpiNo'));
        $UnderUser = esc($conn, post('UnderUser'));
        $ReportingMgr = esc($conn, post('ReportingMgr'));
        $ResignStatus = esc($conn, post('ResignStatus'));
        $ResignDate = esc($conn, post('ResignDate'));
        $ResignComment = esc($conn, post('ResignComment'));

        // === Multi-select fields ===
        $Options2 = !empty($_POST['Options']) ? esc($conn, implode(",", $_POST['Options'])) : 0;
        $zone = !empty($_POST['zone']) ? esc($conn, implode(",", $_POST['zone'])) : 0;
        $subzone = !empty($_POST['subzone']) ? esc($conn, implode(",", $_POST['subzone'])) : 0;
        $CocoFranchiseAccess = !empty($_POST['CocoFranchiseAccess']) ? esc($conn, implode(",", $_POST['CocoFranchiseAccess'])) : 0;

        // === File uploads ===
        $Photo  = handleUpload('Photo', post('OldPhoto'));
        $Photo2 = handleUpload('Photo2', post('OldPhoto2'));
        $Photo3 = handleUpload('Photo3', post('OldPhoto3'));

        $tempDir = '../../barcodes/';

        // === Begin Transaction ===
        $conn->begin_transaction();

        if ($id == '') {
            // Duplicate check
            $check = $conn->query("SELECT id FROM tbl_users WHERE Phone='$Phone'");
            if ($check->num_rows > 0) {
                echo "<script>alert('Phone No Already Exists!');window.location.href='../add-employee.php';</script>";
                exit;
            }

            // === Insert into tbl_users ===
            $sql = "INSERT INTO tbl_users SET
                Options2='$Options2',subzone='$subzone',zone='$zone',CocoFranchiseAccess='$CocoFranchiseAccess',
                Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',
                Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',
                Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',CreatedDate='$CreatedDate',CreatedBy='$user_id',
                GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',
                CompId='$CompId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',
                AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',PerDaySalary='$PerDaySalary',
                AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',
                UpiNo='$UpiNo',UnderUser='$UnderUser',ReportingMgr='$ReportingMgr',ResignStatus='$ResignStatus',
                ResignDate='$ResignDate',ResignComment='$ResignComment'";
            $conn->query($sql);
            $EmpId = $conn->insert_id;

            // === Generate Barcode ===
           /* $filename = $EmpId . ".png";
            QRcode::png($Phone, $tempDir . $filename, QR_ECLEVEL_L, 5);
            $CustomerId = "C" . $EmpId;*/

            // Update barcode and ID
            $conn->query("UPDATE tbl_users SET Barcode='$filename', CustomerId='$CustomerId' WHERE id='$EmpId'");

            // === Copy to tbl_users_bill ===
            $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$EmpId'");

            // === Customer Address ===
            $conn->query("INSERT INTO customer_address 
                SET UserId='$EmpId',Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',
                    CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',
                    Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'");

            $conn->commit();
            echo "<script>alert('Record Created Successfully!');window.location.href='../view-employee.php';</script>";

        } else {
            // === Update existing ===
            $check = $conn->query("SELECT id FROM tbl_users WHERE Phone='$Phone' AND id!='$id'");
            if ($check->num_rows > 0) {
                echo "<script>alert('Phone No Already Exists!');window.location.href='../add-employee.php?id=$id';</script>";
                exit;
            }

            /*$filename = $id . ".png";
            QRcode::png($Phone, $tempDir . $filename, QR_ECLEVEL_L, 5);*/

            $sql = "UPDATE tbl_users SET 
                Options2='$Options2',subzone='$subzone',zone='$zone',CocoFranchiseAccess='$CocoFranchiseAccess',
                Barcode='$filename',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',
                Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',
                Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',ModifiedDate='$CreatedDate',
                ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',
                PanNo='$PanNo',CompId='$CompId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',
                Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',
                PerDaySalary='$PerDaySalary',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',
                IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',UnderUser='$UnderUser',ReportingMgr='$ReportingMgr',
                ResignStatus='$ResignStatus',ResignDate='$ResignDate',ResignComment='$ResignComment'
                WHERE id='$id'";
            $conn->query($sql);

            // Mirror changes into tbl_users_bill
            $conn->query("DELETE FROM tbl_users_bill WHERE id='$id'");
            $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$id'");

            // Update customer address
            $conn->query("UPDATE customer_address 
                SET Fname='$Fname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',
                    CountryId='$CountryId',StateId='$StateId',CityId='$CityId',
                    Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'
                WHERE UserId='$id'");

            $conn->commit();
            echo "<script>alert('Record Updated Successfully!');window.location.href='../view-employee.php';</script>";
        }
    }

    // === Delete Photo ===
    if ($_POST['action'] === 'deletePhoto') {
        $id = intval($_POST['id']);
        $conn->query("UPDATE tbl_users SET Photo='' WHERE id=$id");
        $conn->query("UPDATE tbl_users_bill SET Photo='' WHERE id=$id");
        echo "File Deleted Successfully";
    }

    // === Get Details ===
    if ($_POST['action'] === 'getUserDetails') {
        $id = intval($_POST['id']);
        $sql = "SELECT tu.*,tu2.Fname AS AgentName 
                FROM tbl_users tu 
                LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id 
                WHERE tu.id='$id'";
        echo json_encode(getRecord($sql));
    }

    if ($_POST['action'] === 'getUserDetails2') {
        $CellNo = esc($conn, $_POST['CellNo']);
        $sql = "SELECT tu.*,tu2.Fname AS AgentName 
                FROM tbl_users tu 
                LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id 
                WHERE tu.Phone='$CellNo'";
        echo json_encode(getRecord($sql));
    }

} catch (Exception $e) {
    if ($conn->in_transaction) $conn->rollback();
    error_log("Error in employee-save.php: " . $e->getMessage());
    echo "<script>alert('Something went wrong: " . addslashes($e->getMessage()) . "');history.back();</script>";
}
?>
