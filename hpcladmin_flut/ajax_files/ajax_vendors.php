<?php
session_start();
include_once '../config.php';

$user_id = $_SESSION['Admin']['id'] ?? 0;

// Enable exceptions for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Helper: escape and trim input
 */
function esc($conn, $val) {
    return mysqli_real_escape_string($conn, trim($val));
}

/**
 * Helper: safely get POST variable
 */
function post($key, $default = '') {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

/**
 * Helper: handle file uploads
 */
function handleUpload($key, $old = '', $uploadDir = '../../uploads/') {
    if (!isset($_FILES[$key]) || !is_uploaded_file($_FILES[$key]['tmp_name'])) {
        return $old;
    }
    $rand = rand(1, 100);
    $filename = $_FILES[$key]['name'];
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

        // Collect & sanitize input
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
        $Status = esc($conn, post('Status'));
        $CatId = esc($conn, post('CatId'));
        $Roll = 3; // Vendor
        $Photo  = handleUpload('Photo', post('OldPhoto'));
        $Photo2 = handleUpload('Photo2', post('OldPhoto2'));
        $Photo3 = handleUpload('Photo3', post('OldPhoto3'));

        // Begin transaction
        $conn->begin_transaction();

        if ($id == '') {
            // Check for duplicate phone number
            $check = $conn->query("SELECT id FROM tbl_users WHERE Phone='$Phone' AND Roll='3'");
            if ($check->num_rows > 0) {
                echo "<script>alert('Phone number already exists!');window.location.href='../add-vendor.php';</script>";
                exit;
            }

            // Insert into tbl_users
            $sql = "INSERT INTO tbl_users SET 
                Fname='$Fname', Mname='$Mname', Lname='$Lname',
                Phone='$Phone', EmailId='$EmailId', Password='$Password',
                Phone2='$Phone2', CountryId='$CountryId', StateId='$StateId', CityId='$CityId',
                Address='$Address', Pincode='$Pincode', Status='$Status',
                Photo='$Photo', Roll='$Roll', CreatedDate='$CreatedDate', CreatedBy='$user_id',
                GstNo='$GstNo', Photo2='$Photo2', Photo3='$Photo3', Details='$Details', CatId='$CatId'";
            $conn->query($sql);

            $EmpId = $conn->insert_id;
            $CustomerId = "V" . $EmpId;

            // Update CustomerId
            $conn->query("UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'");

            // Also insert into tbl_users_bill
            $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$EmpId'");

            $conn->commit();
            echo "<script>alert('Record Created Successfully!');window.location.href='../view-vendors.php';</script>";
        } else {
            // Update vendor record
            $sql = "UPDATE tbl_users SET 
                Fname='$Fname', Mname='$Mname', Lname='$Lname',
                Phone='$Phone', EmailId='$EmailId', Password='$Password',
                Phone2='$Phone2', CountryId='$CountryId', StateId='$StateId', CityId='$CityId',
                Address='$Address', Pincode='$Pincode', Status='$Status',
                Photo='$Photo', Roll='$Roll', ModifiedDate='$CreatedDate', ModifiedBy='$user_id',
                GstNo='$GstNo', Photo2='$Photo2', Photo3='$Photo3', Details='$Details', CatId='$CatId'
                WHERE id='$id'";
            $conn->query($sql);

            // Mirror in tbl_users_bill
            $conn->query("DELETE FROM tbl_users_bill WHERE id='$id'");
            $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$id'");

            $conn->commit();
            echo "<script>alert('Record Updated Successfully!');window.location.href='../view-vendors.php';</script>";
        }
    }

    // Delete Photo
    if ($_POST['action'] == 'deletePhoto') {
        $id = intval($_POST['id']);
        $conn->query("UPDATE tbl_users SET Photo='' WHERE id=$id");
        $conn->query("UPDATE tbl_users_bill SET Photo='' WHERE id=$id");
        echo "File Deleted Successfully";
    }

    // Get Vendor Details by ID
    if ($_POST['action'] == 'getUserDetails') {
        $id = intval($_POST['id']);
        $sql = "SELECT tu.*, tu2.Fname AS AgentName 
                FROM tbl_users tu 
                LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id 
                WHERE tu.id='$id'";
        echo json_encode(getRecord($sql));
    }

    // Get Vendor Details by Phone
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
    error_log("Vendor Save Error: " . $e->getMessage());
    echo "<script>alert('Something went wrong: " . addslashes($e->getMessage()) . "');history.back();</script>";
}
?>
