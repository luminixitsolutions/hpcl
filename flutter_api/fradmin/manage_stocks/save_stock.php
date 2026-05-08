<?php
header('Content-Type: application/json; charset=utf-8');
include '../config.php';

try {
    // Start session and database transaction
    session_start();
    $conn->begin_transaction();

    // Get logged-in user details
    $user_id = isset($_SESSION['Admin']['id']) ? $_SESSION['Admin']['id'] : $_POST['CreatedBy'];
    $FrId = isset($_SESSION['fr_admin']) ? $_SESSION['fr_admin'] : $_POST['FrId'];
    $CreatedDate = date('Y-m-d');
    $CreatedDateTime = date('Y-m-d H:i:s');

    // Fetch Franchise Role Info
    $sql77 = "SELECT Roll, BillSoftFrId FROM tbl_users_bill WHERE id='$FrId'";
    $row77 = getRecord($sql77);
    if (!$row77) {
        throw new Exception("Invalid franchise ID or user not found.");
    }

    $Roll = $row77['Roll'];
    $BillSoftFrId = ($Roll == 5) ? $FrId : $row77['BillSoftFrId'];

    // Decode JSON product data
    $products = json_decode($_POST['products'], true);
    if (empty($products)) {
        throw new Exception("No product data received.");
    }

    // Loop through products and insert into tables
    foreach ($products as $p) {
        $ProdId = addslashes(trim($p['prodId']));
        $MainProdId = addslashes(trim($p['mainProdId']));
        $Qty = addslashes(trim($p['qty']));
        $PurchasePrice = addslashes(trim($p['price']));
        $SellPrice = addslashes(trim($p['price'])); // optional: you can change if you have different field
        $StockDate = $CreatedDate;
        $Narration = "Stock Added via Manage Stock Page";
        $VedId = "0"; // optional: modify as needed

        // ✅ Insert into main stock table
        $sql1 = "INSERT INTO tbl_cust_prod_stock_2025 
                    SET ProdId='$ProdId',
                    MainProdId='$MainProdId',
                        Qty='$Qty',
                        CreatedBy='$user_id',
                        StockDate='$StockDate',
                        Narration='$Narration',
                        Status='Cr',
                        UserId='$BillSoftFrId',
                        CreatedDate='$CreatedDate',
                        FrId='$BillSoftFrId',
                        PurchasePrice='$PurchasePrice',
                        SellPrice='$SellPrice',
                        VedId='$VedId'";
        if (!$conn->query($sql1)) {
            throw new Exception("Error inserting stock: " . $conn->error);
        }

        $InvId = $conn->insert_id;

        // ✅ Insert into backup stock table
        $sql2 = "INSERT INTO tbl_cust_prod_stock_2025_backup 
                    SET ProdId='$ProdId',
                    MainProdId='$MainProdId',
                        Qty='$Qty',
                        CreatedBy='$user_id',
                        StockDate='$StockDate',
                        Narration='$Narration',
                        Status='Cr',
                        UserId='$BillSoftFrId',
                        CreatedDate='$CreatedDate',
                        FrId='$BillSoftFrId',
                        PurchasePrice='$PurchasePrice',
                        SellPrice='$SellPrice',
                        orgstockid='$InvId',
                        VedId='$VedId'";
        if (!$conn->query($sql2)) {
            throw new Exception("Error inserting backup: " . $conn->error);
        }

        // ✅ Update product purchase price
        $sql3 = "UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
        if (!$conn->query($sql3)) {
            throw new Exception("Error updating product price: " . $conn->error);
        }

     
        
    }

    // ✅ Commit all operations
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Stock saved successfully."
    ]);
} catch (Exception $e) {
    // Rollback all changes if any error occurs
    $conn->rollback();

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
