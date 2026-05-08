<?php
include 'db.php';

$response = ['success' => false];

if (isset($_POST['token'])) {
    $token = $_POST['token'];

    $query = mysqli_query($con, "SELECT name, email, address, profile_image FROM users WHERE token='$token' LIMIT 1");

    if ($row = mysqli_fetch_assoc($query)) {
        // If image path is set and not empty
        if (!empty($row['profile_image'])) {
            $row['photo'] = 'https://vtechsolar.in/flutter/uploads/' . $row['profile_image'];
        } else {
            $row['photo'] = '';
        }

        $response['success'] = true;
        $response['data'] = $row;
    } else {
        $response['message'] = 'Token not matched.';
    }
} else {
    $response['message'] = 'Token not provided.';
}

echo json_encode($response);
?>
