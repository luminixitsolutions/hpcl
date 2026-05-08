<?php
include 'db.php';

header('Content-Type: application/json');

$response = ['status' => false, 'message' => '', 'user' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = trim($_POST['mobile'] ?? '');

    if ($mobile === '') {
        $response['message'] = 'Mobile number is required.';
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, ShopName, Phone FROM tbl_users_bill WHERE Phone = ? AND Roll=5");
    $stmt->bind_param("s", $mobile);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // You can generate an OTP/token if needed here
        // For now, just return user data
        $response['status'] = true;
        $response['message'] = 'User found';
        $response['user'] = $user;
    } else {
        $response['message'] = 'Mobile number not registered.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>