<?php
include 'db.php';

$Name = $_REQUEST['couponcode'] ?? '';
$response = array();

if ($Name != '') {
    // Optimized query to calculate credit, debit, and balance in one go
    $sql = "
        SELECT 
            *
        FROM tbl_coupon 
        WHERE Name = '$Name'
    ";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
  
    $response['status'] = true;
    $response['data'] = [
        'CouponCode' => $row['Name'],
        'Price' => $row['Price'],
         'MinOrder' => $row['MinOrder'],
      
    ];
} else {
    $response['status'] = false;
    $response['message'] = 'Coupon Code required';
}

echo json_encode($response);
?>
