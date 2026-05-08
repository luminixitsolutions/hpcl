<?php
include 'db.php';

$user_id = $_REQUEST['user_id'] ?? '';
$BillSoftFrId = ''; // Initialize to avoid "undefined variable" warning

if ($user_id != '') {
    $sql77 = "SELECT id,Roll, BillSoftFrId FROM tbl_users_bill WHERE id = '$user_id' LIMIT 1";
    $result77 = mysqli_query($conn, $sql77);

    if ($result77 && mysqli_num_rows($result77) > 0) {
        $row77 = mysqli_fetch_assoc($result77);
        $Roll = $row77['Roll'];

        // Determine BillSoftFrId
        if ($Roll == 5) {
            $BillSoftFrId = $user_id;
        } else {
            $BillSoftFrId = $row77['id'];
        }
    }
}

$response = array();

if ($BillSoftFrId != '') {
    if ($Roll == 5) {
    $query = "SELECT * FROM tbl_customer_invoice_2025 
              WHERE FrId='$BillSoftFrId' 
              AND InvoiceDate='".date('Y-m-d')."'";
    }
    else{
        $query = "SELECT * FROM tbl_customer_invoice_2025 
              WHERE user_id='$BillSoftFrId' 
              AND InvoiceDate='".date('Y-m-d')."'";
    }
    $result = mysqli_query($conn, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $response['status'] = true;
    $response['data'] = $data;
} else {
    $response['status'] = false;
    $response['message'] = 'User ID is required or invalid';
}

echo json_encode($response);
?>
