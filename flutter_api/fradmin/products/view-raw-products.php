<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $user_id = $_REQUEST['user_id'] ?? '';

    if (empty($user_id)) {
        throw new Exception("user_id is required");
    }

    // Fetch user to get allocated raw products
    $sql77 = "SELECT * FROM tbl_users_bill WHERE id = '$user_id'";
    $row77 = getRecord($sql77);
    $AllocateRawProd = $row77['AllocateRawProd'] ?? '';

    if (empty($AllocateRawProd)) {
        throw new Exception("No allocated raw products found for this user");
    }

    $sql = "SELECT tp.ProductName, tp.Unit, tp.CreatedDate, tc.Name AS CatName, tcs.Name AS SubCatName
            FROM tbl_cust_products2 tp
            LEFT JOIN tbl_cust_category_2025 tc ON tp.CatId = tc.id
            LEFT JOIN tbl_cust_sub_category_2025 tcs ON tp.SubCatId = tcs.id
            WHERE tp.id IN ($AllocateRawProd) AND tp.ProdType = '1'
            ORDER BY tp.ProductName";

    $res = $conn->query($sql);

    $products = [];
    $i = 1;
    while ($row = $res->fetch_assoc()) {
        $products[] = [
            "SerialNo" => $i,
            "ProductName" => $row['ProductName'],
            "Category" => $row['CatName'],

            "Unit" => $row['Unit'],
            "RegisterDate" => date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate'])))
        ];
        $i++;
    }

    echo json_encode([
        "status" => "success",
        "records" => $products
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch products: " . $e->getMessage()
    ]);
}
?>
