<?php
include 'db.php';

$user_id = $_REQUEST['user_id'] ?? '';
//$user_id = 1;
$sql77 = "SELECT Roll, BillSoftFrId FROM tbl_users_bill WHERE id = '$user_id' LIMIT 1";
    $result77 = mysqli_query($conn, $sql77);

    if ($result77 && mysqli_num_rows($result77) > 0) {
        $row77 = mysqli_fetch_assoc($result77);
        $Roll = $row77['Roll'];

        // Determine BillSoftFrId
        if ($Roll == 5) {
            $BillSoftFrId = $user_id;
        } else {
            $BillSoftFrId = $row77['BillSoftFrId'];
        }
    }
$response = array();

if ($BillSoftFrId != '') {
    $query = "SELECT id,Fname,Phone FROM tbl_users_bill WHERE BillSoftFrId='$BillSoftFrId'";
    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $response['status'] = true;
    $response['data'] = $data;
} else {
    $response['status'] = false;
    $response['message'] = 'User ID is required';
}

echo json_encode($response);
?>
