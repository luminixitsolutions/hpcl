<?php
include 'db.php';

$user_id = $_GET['user_id'] ?? '';

$response = array();

if ($user_id != '') {
    $query = "SELECT * FROM tbl_users_bill WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // If BillSoftFrId = 0, set it to id
        if (isset($row['BillSoftFrId']) && $row['BillSoftFrId'] == 0) {
            $row['BillSoftFrId'] = $row['id'];
        }
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
