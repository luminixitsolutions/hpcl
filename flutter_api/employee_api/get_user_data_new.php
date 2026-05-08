<?php
include 'db.php';
header('Content-Type: application/json');

$response = array();

// Check if user_id is provided
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    $response['status'] = false;
    $response['message'] = 'User ID is required';
    echo json_encode($response);
    exit;
}

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

// Prepare SQL query
$query = "SELECT 
    id,
    Fname,
    Mname,
    Lname,
    ShopName,
    Address,
    Phone,
    GstNo,
    terms_condition,
    bottom_title,
    PrintCompName,
    PrintMobNo,
    BillSoftFrId
FROM tbl_users_bill 
WHERE id = '$user_id'";

// Execute query
$result = mysqli_query($conn, $query);

// Check query result
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
         // If BillSoftFrId = 0, use id instead
        if ($data['BillSoftFrId'] == 0) {
            $data['BillSoftFrId'] = $data['id'];
        }
        
        $response['status'] = true;
        $response['data'] = $data;
    } else {
        $response['status'] = false;
        $response['message'] = 'No data found';
    }
} else {
    $response['status'] = false;
    $response['message'] = 'Query error: ' . mysqli_error($conn);
}

// Output JSON
echo json_encode($response);
?>
