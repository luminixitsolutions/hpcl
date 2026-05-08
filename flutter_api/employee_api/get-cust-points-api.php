<?php
include 'db.php';

$phone = $_REQUEST['phone'] ?? '';
$response = array();

if ($phone != '') {
    // Optimized query to calculate credit, debit, and balance in one go
    $sql = "
        SELECT 
            SUM(CASE WHEN status='cr' THEN rupees ELSE 0 END) AS total_credit,
            SUM(CASE WHEN status='dr' THEN rupees ELSE 0 END) AS total_debit,
            (SUM(CASE WHEN status='cr' THEN rupees ELSE 0 END) 
            - SUM(CASE WHEN status='dr' THEN rupees ELSE 0 END)) AS balance
        FROM tbl_customer_points 
        WHERE phone = '$phone'
    ";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
     $sql2 = "SELECT Fname,EmailId FROM tbl_users WHERE Phone = '$phone' AND Roll=55";
    
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);

    $response['status'] = true;
    $response['data'] = [
        'phone' => $phone,
        'Name' => $row2['Fname'],
        'EmailId' => $row2['EmailId'],
        'total_credit' => (float)($row['total_credit'] ?? 0),
        'total_debit' => (float)($row['total_debit'] ?? 0),
        'balance' => (float)($row['balance'] ?? 0)
    ];
} else {
    $response['status'] = false;
    $response['message'] = 'Phone number is required';
}

echo json_encode($response);
?>
