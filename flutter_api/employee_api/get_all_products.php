<?php
include 'db.php';

// Set JSON response header
header('Content-Type: application/json');

// Get and sanitize user_id
$user_id = isset($_REQUEST['user_id']) ? mysqli_real_escape_string($conn, $_REQUEST['user_id']) : '';

$response = [];

if ($user_id !== '') {

    // Fetch user role and BillSoftFrId
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

        // Fetch products
        $query = "
            SELECT * 
            FROM tbl_cust_products_2025 
            WHERE CreatedBy = '$BillSoftFrId' 
              AND checkstatus = 1 AND Status=1
              AND ProdType = 0 
              AND ProdType2 != 3
        ";

        $result = mysqli_query($conn, $query);

        if ($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Handle Photo field
        if (empty($row['Photo'])) {
            $row['Photo'] = "https://mahachai.in/images/products/p4.jpg";
        } else {
            $row['Photo'] = "https://kwickfoods.in/uploads/" . $row['Photo'];
        }

        $data[] = $row;
    }

    $response['status'] = true;
    $response['data'] = $data;
} else {
    $response['status'] = false;
    $response['message'] = 'Query failed: ' . mysqli_error($conn);
}
    } else {
        $response['status'] = false;
        $response['message'] = 'User not found';
    }
} else {
    $response['status'] = false;
    $response['message'] = 'User ID is required';
}

echo json_encode($response);
?>
