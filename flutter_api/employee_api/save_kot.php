<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';  // your DB connection file

$response = ["status" => "error", "message" => "Something went wrong"];

try {
    $invoice_no       = $_POST['invoice_no'] ?? '';
    $table_number     = $_POST['table_number'] ?? '';
    $customer_name    = $_POST['customer_name'] ?? '';
    $customer_phone   = $_POST['customer_phone'] ?? '';
    $order_instructions = $_POST['order_instructions'] ?? '';
    $kot_type         = $_POST['kot_type'] ?? '';
    $kot_data         = $_POST['kot_data'] ?? '';
    $total_items      = $_POST['total_items'] ?? 0;
    $total_amount     = $_POST['total_amount'] ?? 0;
    $printed_at       = $_POST['printed_at'] ?? date("Y-m-d H:i:s");

    // Validate required fields
    if ($invoice_no == '' || $kot_type == '' || $kot_data == '') {
        $response = ["status" => "error", "message" => "Missing required fields: invoice_no, kot_type, or kot_data"];
        echo json_encode($response);
        exit;
    }

    // Validate JSON data
    $decoded_kot_data = json_decode($kot_data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response = ["status" => "error", "message" => "Invalid JSON in kot_data: " . json_last_error_msg()];
        echo json_encode($response);
        exit;
    }

    // Validate that kot_data is an array
    if (!is_array($decoded_kot_data)) {
        $response = ["status" => "error", "message" => "kot_data must be a valid JSON array"];
        echo json_encode($response);
        exit;
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO tbl_kot_data 
            (invoice_no, table_number, customer_name, customer_phone, order_instructions, kot_type, kot_data, total_items, total_amount, printed_at) 
            VALUES (?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $response = ["status" => "error", "message" => "Failed to prepare statement: " . $conn->error];
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("sssssssids", 
        $invoice_no, $table_number, $customer_name, $customer_phone, 
        $order_instructions, $kot_type, $kot_data, $total_items, 
        $total_amount, $printed_at
    );

    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "KOT saved successfully", "id" => $stmt->insert_id];
    } else {
        $response = ["status" => "error", "message" => "Database error: " . $stmt->error];
    }

    $stmt->close();

} catch (Exception $e) {
    $response = ["status" => "error", "message" => "Exception: " . $e->getMessage()];
}

echo json_encode($response);
?>