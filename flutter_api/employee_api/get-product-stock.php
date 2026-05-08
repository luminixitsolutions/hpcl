<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

include 'db.php'; // update if needed

$response = [];
$userid = $_REQUEST['user_id'];

if (empty($userid)) {
    echo json_encode([
        "status" => false,
        "message" => "Missing parameter: user_id"
    ]);
    exit;
}

// Step 1️⃣ — Fetch all products for this user
$query = "SELECT id, ProductName 
          FROM tbl_cust_products_2025 
          WHERE CreatedBy='$userid' 
            AND ProdType=0 
            AND ProdType2=1 
            AND checkstatus=1 
            AND Status=1";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode([
        "status" => false,
        "message" => "Query failed: " . mysqli_error($conn)
    ]);
    exit;
}

$menu_items = [];

// Step 2️⃣ — For each product, calculate stock
while ($row = mysqli_fetch_assoc($result)) {
    $prodId = $row['id'];

    $stockQuery = "
        SELECT 
            SUM(CASE WHEN Status='Cr' THEN Qty ELSE 0 END) AS creditqty,
            SUM(CASE WHEN Status='Dr' THEN Qty ELSE 0 END) AS debitqty,
            (SUM(CASE WHEN Status='Cr' THEN Qty ELSE 0 END) - SUM(CASE WHEN Status='Dr' THEN Qty ELSE 0 END)) AS balqty
        FROM tbl_cust_prod_stock_2025
        WHERE ProdId = '$prodId' AND ProdType = 0
    ";

    $stockResult = mysqli_query($conn, $stockQuery);
    $stockRow = mysqli_fetch_assoc($stockResult);

    $creditqty = $stockRow['creditqty'] ? (float)$stockRow['creditqty'] : 0;
    $debitqty  = $stockRow['debitqty'] ? (float)$stockRow['debitqty'] : 0;
    $balqty    = $stockRow['balqty'] ? (float)$stockRow['balqty'] : 0;

    // ✅ Replace negative balance with 0
    if ($balqty < 0) {
        $balqty = 0;
    }

    $menu_items[] = [
        "id"          => (int)$prodId,
        "ProductName" => $row['ProductName'],
        "creditqty"   => $creditqty,
        "debitqty"    => $debitqty,
        "balqty"      => $balqty
    ];
}

// Step 3️⃣ — Return response
echo json_encode([
    "status" => true,
    "menu"   => $menu_items
]);
?>
