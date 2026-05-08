<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php'; // DB connection file

$response = ["status" => "error", "message" => "Something went wrong"];

try {
    // 🟢 Collect POST data
    $ProdId         = $_REQUEST['ProdId'] ?? '';
    $Qty            = $_REQUEST['Qty'] ?? '';
    $user_id        = $_REQUEST['user_id'] ?? '';
    $StockDate      = $_REQUEST['StockDate'] ?? date("Y-m-d");
    $Narration      = $_REQUEST['Narration'] ?? '';
    $BillSoftFrId   = $_REQUEST['BillSoftFrId'] ?? '';
    $PurchasePrice  = $_REQUEST['PurchasePrice'] ?? 0;
    $SellPrice      = $_REQUEST['SellPrice'] ?? 0;
    $CreatedDate    = $_REQUEST['CreatedDate'] ?? date("Y-m-d H:i:s");

    // 🟠 Validate required fields
    if ($ProdId == '' || $Qty == '' || $user_id == '' || $BillSoftFrId == '') {
        $response = [
            "status" => "error",
            "message" => "Missing required fields: ProdId, Qty, user_id, or BillSoftFrId"
        ];
        echo json_encode($response);
        exit;
    }

    // 🟣 Sanitize inputs (basic)
    $ProdId         = $conn->real_escape_string($ProdId);
    $Qty            = $conn->real_escape_string($Qty);
    $user_id        = $conn->real_escape_string($user_id);
    $StockDate      = $conn->real_escape_string($StockDate);
    $Narration      = $conn->real_escape_string($Narration);
    $BillSoftFrId   = $conn->real_escape_string($BillSoftFrId);
    $PurchasePrice  = $conn->real_escape_string($PurchasePrice);
    $SellPrice      = $conn->real_escape_string($SellPrice);
    $CreatedDate    = $conn->real_escape_string($CreatedDate);

    // 🟢 Final SQL query (your format)
    $qx = "INSERT INTO tbl_cust_prod_stock_2025 
           SET ProdId='$ProdId',
               Qty='$Qty',
               CreatedBy='$user_id',
               StockDate='$StockDate',
               Narration='$Narration',
               Status='Cr',
               UserId='$BillSoftFrId',
               CreatedDate='$CreatedDate',
               FrId='$BillSoftFrId',
               PurchasePrice='$PurchasePrice',
               SellPrice='$SellPrice'";

    // 🟢 Execute query
    if ($conn->query($qx)) {
        $insert_id = $conn->insert_id;
        $response = [
            "status" => "success",
            "message" => "Stock saved successfully",
            "id" => $insert_id
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Database error: " . $conn->error
        ];
    }

} catch (Exception $e) {
    $response = [
        "status" => "error",
        "message" => "Exception: " . $e->getMessage()
    ];
}

echo json_encode($response);
?>
