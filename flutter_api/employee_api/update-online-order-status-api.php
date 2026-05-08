<?php
header("Content-Type: application/json");
include 'db.php'; // Secure DB connection

$order_id = isset($_REQUEST['Unqid']) ? trim($_REQUEST['Unqid']) : '';
$user_id  = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
$InvoiceNo = isset($_REQUEST['InvoiceNo']) ? trim($_REQUEST['InvoiceNo']) : '';


if (empty($order_id) || $user_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid parameters"]);
    exit;
}

// Update order process status
$sqlUpdate = "UPDATE tbl_customer_invoice_2025 
              SET OrderProcess = 1 
              WHERE Unqid = ? AND FrId = ? AND InvoiceNo = ?";

$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param("sii", $order_id, $user_id,$InvoiceNo);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Order marked as complete",
            "order_id" => $order_id
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Order not found or already completed"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error updating order",
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
