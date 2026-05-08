<?php
header("Content-Type: application/json");
include 'db.php'; // Secure DB connection

$user_id = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
$response = [];

if ($user_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid user_id"]);
    exit;
}

// Fetch all invoices of this user
$sqlInvoices = "SELECT Unqid,id,CellNo,CustName,InvoiceNo,InvoiceDate,SubTotal,GstAmt,Discount,DiscPer,NetAmount,FrId FROM tbl_customer_invoice_2025 WHERE FrId='$user_id' AND PayType='Online' ORDER BY id DESC";
$resultInvoices = $conn->query($sqlInvoices);

if ($resultInvoices && $resultInvoices->num_rows > 0) {
    while ($row = $resultInvoices->fetch_assoc()) {
        $invoiceId = $row['Unqid'];

        // Fetch items for this invoice
        $sqlItems = "SELECT tc.Unqid,tc.ServerInvId,tc.InvId,tc.ProdId,tu.ProductName,tc.ActPrice,tc.Qty,tc.Price AS ProdPrice,tc.CgstPer,tc.SgstPer,tc.CgstAmt,tc.SgstAmt,tc.GstAmt,tc.Total,tc.CreatedDate,tc.FrId FROM `tbl_customer_invoice_details_2025` tc INNER JOIN tbl_cust_products_2025 tu ON tu.id=tc.ProdId WHERE tc.InvId='$invoiceId' AND tc.FrId='$user_id'";
        $resultItems = $conn->query($sqlItems);

        $items = [];
        if ($resultItems && $resultItems->num_rows > 0) {
            while ($itemRow = $resultItems->fetch_assoc()) {
                $items[] = $itemRow;
            }
        }

        $row['items'] = $items;
        $response[] = $row;
    }

    echo json_encode([
        "success" => true,
        "user_id" => $user_id,
        "invoices" => $response
    ], JSON_PRETTY_PRINT);

} else {
    echo json_encode([
        "success" => false,
        "user_id" => $user_id,
        "message" => "No invoices found"
    ]);
}
?>
