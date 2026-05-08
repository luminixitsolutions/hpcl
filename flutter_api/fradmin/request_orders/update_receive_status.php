<?php
include '../config.php';
header('Content-Type: application/json');
error_reporting(0);
ob_clean();

try {
    $conn->begin_transaction();

    $itemId = intval($_POST['itemId'] ?? 0);
    $qty = floatval($_POST['qty'] ?? 0);
    $user_id = $_SESSION['Admin']['id'] ?? 0;
    $FrId = $_SESSION['fr_admin'] ?? 0;
    $ReceiveDate = date('Y-m-d H:i:s');
    $CreatedDate = date('Y-m-d');
    $Narration = "Stock received from dealer order";

    if ($itemId <= 0) {
        throw new Exception("Invalid Item ID");
    }

    // Fetch item info
    $sql = "SELECT ItemId, ProdId, Qty, Price, DistId, FrId, MainProdId 
            FROM tbl_dealer_req_order_items 
            WHERE ItemId = '$itemId'";
    $res = $conn->query($sql);
    if (!$res || $res->num_rows == 0) {
        throw new Exception("Item not found");
    }

    $row = $res->fetch_assoc();
    $orderedQty = floatval($row['Qty']);
    $ProdId = $row['ProdId'];
    $Price = $row['Price'];
    $DealerId = $row['DistId'];
    $FrId = $row['FrId'];
    $MainProdId = $row['MainProdId'];

    if ($qty > $orderedQty) $qty = $orderedQty;

    // Determine status
    if ($qty == 0) {
        $ReceiveStatus = 0; // pending
    } elseif ($qty < $orderedQty) {
        $ReceiveStatus = 2; // partial
    } else {
        $ReceiveStatus = 1; // received
    }

    // Update the dealer request item record
    $sqlUp = "UPDATE tbl_dealer_req_order_items 
              SET ReceiveQty = '$qty', 
                  ReceiveStatus = '$ReceiveStatus',
                  ReceiveDate = '$ReceiveDate'
              WHERE ItemId = '$itemId'";
    if (!$conn->query($sqlUp)) {
        throw new Exception("Failed to update receive quantity: " . $conn->error);
    }

    // If qty > 0, insert into stock tables
    if ($qty > 0) {

        // Get Franchise Role Details
        $sql77 = "SELECT Roll, BillSoftFrId FROM tbl_users_bill WHERE id='$FrId'";
        $row77 = getRecord($sql77);
        if (!$row77) {
            throw new Exception("Invalid franchise details.");
        }
        $Roll = $row77['Roll'];
        $BillSoftFrId = ($Roll == 5) ? $FrId : $row77['BillSoftFrId'];

        // Insert into main stock table
        $sqlStock = "INSERT INTO tbl_cust_prod_stock_2025 
                        SET ProdId='$ProdId',
                        MainProdId='$MainProdId',
                            Qty='$qty',
                            CreatedBy='$user_id',
                            StockDate='$CreatedDate',
                            Narration='$Narration',
                            Status='Cr',
                            UserId='$BillSoftFrId',
                            CreatedDate='$CreatedDate',
                            FrId='$BillSoftFrId',
                            PurchasePrice='$Price',
                            SellPrice='$Price',
                            VedId='$DealerId'";
        if (!$conn->query($sqlStock)) {
            throw new Exception("Failed to insert into stock table: " . $conn->error);
        }

        $StockId = $conn->insert_id;

        // Insert into backup stock table
        $sqlBackup = "INSERT INTO tbl_cust_prod_stock_2025_backup 
                        SET ProdId='$ProdId',
                        MainProdId='$MainProdId',
                            Qty='$qty',
                            CreatedBy='$user_id',
                            StockDate='$CreatedDate',
                            Narration='$Narration',
                            Status='Cr',
                            UserId='$BillSoftFrId',
                            CreatedDate='$CreatedDate',
                            FrId='$BillSoftFrId',
                            PurchasePrice='$Price',
                            SellPrice='$Price',
                            VedId='$DealerId',
                            orgstockid='$StockId'";
        if (!$conn->query($sqlBackup)) {
            throw new Exception("Failed to insert into stock backup: " . $conn->error);
        }

        


        // Update product price
        $conn->query("UPDATE tbl_cust_products_2025 SET PurchasePrice='$Price' WHERE id='$ProdId'");

        // Insert log
        $url = $_SERVER['REQUEST_URI'];
        $createddate = date('Y-m-d H:i:s');
        $log = "INSERT INTO tbl_user_logs 
                SET userid='$user_id',
                    frid='$BillSoftFrId',
                    url='$url',
                    action='Dealer stock received and added',
                    invid='$StockId',
                    createddate='$createddate',
                    roll='dealer-stock-receive'";
        $conn->query($log);
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Stock received and updated successfully.'
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
exit;
?>
