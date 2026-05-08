<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'] ?? 0;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // enable exceptions

// === Helper functions ===
function esc($conn, $val) { return mysqli_real_escape_string($conn, trim($val)); }
function post($key, $default = '') { return $_POST[$key] ?? $default; }
function handleUpload($key, $old = '', $uploadDir = '../../uploads/') {
    if (!isset($_FILES[$key]) || !is_uploaded_file($_FILES[$key]['tmp_name'])) {
        return $old;
    }
    $rand = rand(1, 100);
    $filename = basename($_FILES[$key]['name']);
    $name = str_replace(' ', '_', pathinfo($filename, PATHINFO_FILENAME));
    $ext = '.' . pathinfo($filename, PATHINFO_EXTENSION);
    $newName = "{$rand}_{$name}{$ext}";
    $dest = rtrim($uploadDir, '/') . '/' . $newName;
    if (move_uploaded_file($_FILES[$key]['tmp_name'], $dest)) {
        return $newName;
    }
    return $old;
}

try {
    if ($_POST['action'] == 'Save') {
        $id = post('id');
        $CreatedDate = date('Y-m-d');

        // === Collect & sanitize all fields ===
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
        $ZoneId = esc($conn, post('ZoneId'));
        $CocoFranchiseAccess = esc($conn, post('CocoFranchiseAccess'));
        $Options = esc($conn, post('Options'));
        $AreaId = esc($conn, post('AreaId'));
        $VedId = esc($conn, post('VedId'));
        
        if($_POST['AssignPincode']!=''){
$AssignPincode = implode(",", $_POST['AssignPincode']);
}
else{
   $AssignPincode = 0; 
}

 // === ✅ Server-side validation for duplicate pincodes ===
        if (!empty($_POST['AssignPincode'])) {
            $duplicates = [];
            $pincodes = $_POST['AssignPincode'];
            foreach ($pincodes as $pc) {
                $sqlCheck = "SELECT id, Fname FROM tbl_users_bill 
                             WHERE FIND_IN_SET('$pc', AssignPincode) 
                             AND VedId='$VedId' 
                             AND Status=1";
                if ($id != '') {
                    $sqlCheck .= " AND id!='$id'";
                }
                $result = $conn->query($sqlCheck);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $duplicates[] = $pc;
                }
            }

            if (!empty($duplicates)) {
                $dupList = implode(',', $duplicates);
                echo "<script>alert('The following Pincode(s) are already assigned to this vendor: $dupList');window.location.href='../add-distributer.php';</script>";
                exit;
            }
        }

        // === Upload Photos ===
        $Photo  = handleUpload('Photo', post('OldPhoto'));
        $Photo2 = handleUpload('Photo2', post('OldPhoto2'));
        $Photo3 = handleUpload('Photo3', post('OldPhoto3'));

        // Begin Transaction
        $conn->begin_transaction();

        // === Insert Case ===
        if ($id == '') {
            $check = $conn->query("SELECT id FROM tbl_users WHERE Phone='$Phone' AND Roll='$Roll'");
            if ($check->num_rows > 0) {
                echo "<script>alert('Phone No Already Exists!');window.location.href='../add-distributer.php';</script>";
                exit;
            }

            $sql = "INSERT INTO tbl_users SET 
                AssignPincode='$AssignPincode',ZoneId='$ZoneId', CocoFranchiseAccess='$CocoFranchiseAccess', 
                Fname='$Fname', Mname='$Mname', Lname='$Lname', 
                Phone='$Phone', EmailId='$EmailId', Password='$Password', Phone2='$Phone2',
                CountryId='$CountryId', StateId='$StateId', CityId='$CityId', 
                Address='$Address', Pincode='$Pincode', Status='$Status',
                Photo='$Photo', Roll='$Roll', CreatedDate='$CreatedDate', CreatedBy='$user_id',
                GstNo='$GstNo', Photo2='$Photo2', Photo3='$Photo3', Details='$Details', 
                CatId='$CatId', PanNo='$PanNo', Options='$Options', CompId='$CompId', 
                BranchId='$BranchId', FatherPhone='$FatherPhone', Designation='$Designation', 
                Dob='$Dob', AadharNo='$AadharNo', BloodGroup='$BloodGroup', JoinDate='$JoinDate',
                EmailId2='$EmailId2', PerDaySalary='$PerDaySalary', AccountName='$AccountName',
                BankName='$BankName', AccountNo='$AccountNo', IfscCode='$IfscCode', 
                Branch='$Branch', UpiNo='$UpiNo', UnderUser='$UnderUser', 
                ReportingMgr='$ReportingMgr', ResignStatus='$ResignStatus', 
                ResignDate='$ResignDate', ResignComment='$ResignComment',VedId='$VedId'";
            $conn->query($sql);
            $EmpId = $conn->insert_id;

            // CustomerId
            $CustomerId = "C" . $EmpId;
            $conn->query("UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'");

            // Insert into customer_address
            $conn->query("INSERT INTO customer_address SET 
                UserId='$EmpId', Fname='$Fname', Lname='$Lname', Phone='$Phone', EmailId='$EmailId', 
                CountryId='$CountryId', StateId='$StateId', CityId='$CityId', AreaId='$AreaId', 
                Address='$Address', Pincode='$Pincode', Status='1', CreatedDate='$CreatedDate'");

            // Copy to tbl_users_bill
            $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$EmpId'");

            $conn->commit();
            echo "<script>alert('Record Created Successfully!');window.location.href='../view-distributers.php';</script>";

        } else {
            // === Update Case ===
            $check = $conn->query("SELECT id FROM tbl_users WHERE Phone='$Phone' AND id!='$id'");
            if ($check->num_rows > 0) {
                echo "<script>alert('Phone No Already Exists!');window.location.href='../add-distributer.php?id=$id';</script>";
                exit;
            }

            $sql = "UPDATE tbl_users SET 
                AssignPincode='$AssignPincode',ZoneId='$ZoneId', CocoFranchiseAccess='$CocoFranchiseAccess',
                Fname='$Fname', Mname='$Mname', Lname='$Lname', Phone='$Phone', EmailId='$EmailId',
                Password='$Password', Phone2='$Phone2', CountryId='$CountryId', StateId='$StateId', CityId='$CityId',
                Address='$Address', Pincode='$Pincode', Status='$Status', Photo='$Photo', Roll='$Roll',
                ModifiedDate='$CreatedDate', ModifiedBy='$user_id', GstNo='$GstNo', Photo2='$Photo2', 
                Photo3='$Photo3', Details='$Details', CatId='$CatId', PanNo='$PanNo', Options='$Options', 
                CompId='$CompId', BranchId='$BranchId', FatherPhone='$FatherPhone', Designation='$Designation', 
                Dob='$Dob', AadharNo='$AadharNo', BloodGroup='$BloodGroup', JoinDate='$JoinDate', 
                EmailId2='$EmailId2', PerDaySalary='$PerDaySalary', AccountName='$AccountName', 
                BankName='$BankName', AccountNo='$AccountNo', IfscCode='$IfscCode', Branch='$Branch', 
                UpiNo='$UpiNo', UnderUser='$UnderUser', ReportingMgr='$ReportingMgr', 
                ResignStatus='$ResignStatus', ResignDate='$ResignDate', ResignComment='$ResignComment',VedId='$VedId'
                WHERE id='$id'";
            $conn->query($sql);

            // Mirror to tbl_users_bill
            $conn->query("DELETE FROM tbl_users_bill WHERE id='$id'");
            $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$id'");

            // Update customer_address
            $conn->query("UPDATE customer_address SET 
                Fname='$Fname', Lname='$Lname', Phone='$Phone', EmailId='$EmailId', 
                CountryId='$CountryId', StateId='$StateId', CityId='$CityId', AreaId='$AreaId', 
                Address='$Address', Pincode='$Pincode', Status='1', CreatedDate='$CreatedDate' 
                WHERE UserId='$id'");

            $conn->commit();
            echo "<script>alert('Record Updated Successfully!');window.location.href='../view-distributers.php';</script>";
        }
    }

    // === Delete Photo ===
    if ($_POST['action'] == 'deletePhoto') {
        $id = intval($_POST['id']);
        $conn->query("UPDATE tbl_users SET Photo='' WHERE id=$id");
        $conn->query("UPDATE tbl_users_bill SET Photo='' WHERE id=$id");
        echo "File Deleted Successfully";
    }

    // === Get User Details by ID ===
    if ($_POST['action'] == 'getUserDetails') {
        $id = intval($_POST['id']);
        $sql = "SELECT tu.*, tu2.Fname AS AgentName 
                FROM tbl_users tu 
                LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id 
                WHERE tu.id='$id'";
        echo json_encode(getRecord($sql));
    }

    // === Get User Details by Phone ===
    if ($_POST['action'] == 'getUserDetails2') {
        $CellNo = esc($conn, $_POST['CellNo']);
        $sql = "SELECT tu.*, tu2.Fname AS AgentName 
                FROM tbl_users tu 
                LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id 
                WHERE tu.Phone='$CellNo'";
        echo json_encode(getRecord($sql));
    }

} catch (Exception $e) {
    if ($conn->in_transaction) $conn->rollback();
    error_log("Distributor Save Error: " . $e->getMessage());
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');history.back();</script>";
}


if ($_POST['action'] == 'checkVendorPincode') {
    $VedId = $_POST['VedId'];
    $pincodes = $_POST['AssignPincode']; // array
    $id = $_POST['id'];
    if (!empty($pincodes)) {
        if($id!=''){
            $sql = "SELECT id, AssignPincode FROM tbl_users_bill WHERE VedId='$VedId' AND id!='$id' AND Status=1";
        }
        else{
          $sql = "SELECT id, AssignPincode FROM tbl_users_bill WHERE VedId='$VedId' AND Status=1";  
        }
        
        $result = $conn->query($sql);

        $duplicate = [];
        while ($row = $result->fetch_assoc()) {
            $existing = explode(',', $row['AssignPincode']);
            foreach ($pincodes as $pc) {
                if (in_array($pc, $existing)) {
                    $duplicate[] = $pc;
                }
            }
        }

        if (!empty($duplicate)) {
            echo "exists:" . implode(",", array_unique($duplicate));
        } else {
            echo "ok";
        }
    }
    exit;
}

?>
