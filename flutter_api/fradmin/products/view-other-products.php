<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $user_id = $_REQUEST['user_id'] ?? '';
    $CatId = $_REQUEST['CatId'] ?? '';
    $SubCatId = $_REQUEST['SubCatId'] ?? '';
    $ProdType2 = $_REQUEST['ProdType2'] ?? '';

    if (empty($user_id)) {
        throw new Exception("user_id is required");
    }

    $sql = "SELECT p.*, c.Name AS Category, cs.Name AS SubCatName
            FROM tbl_cust_products_2025 p
            LEFT JOIN tbl_cust_category_2025 c ON c.id = p.CatId
            LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id = p.SubCatId
            WHERE p.CreatedBy = '$user_id'
              AND p.ProdType = 0
              AND p.ProdType2 = 3
              AND p.delete_flag = 0
              AND p.checkstatus = 1";

    if ($CatId && $CatId != 'all') {
        $sql .= " AND p.CatId = '$CatId'";
    }

    if ($SubCatId && $SubCatId != 'all') {
        $sql .= " AND p.SubCatId = '$SubCatId'";
    }

    if ($ProdType2 && $ProdType2 != 'all') {
        $sql .= " AND p.ProdType2 = '$ProdType2'";
    }

    $sql .= " ORDER BY p.ProductName ASC";

    $res = $conn->query($sql);

    $products = [];
    while ($row = $res->fetch_assoc()) {
        // Determine photo URL
        if (empty($row["Photo"])) {
            $photoUrl = "../no_image.jpg";
        } else if (file_exists("../uploads/" . $row["Photo"])) {
            $photoUrl = "../uploads/" . $row["Photo"];
        } else {
            $photoUrl = "../no_image.jpg";
        }

        $products[] = [
            "id" => $row['id'],
            "ProductName" => $row['ProductName'],
            "BarcodeNo" => $row['BarcodeNo'],
            "Category" => $row['Category'],
          
            "ProductType" => ($row['ProdType2'] == '1' ? 'MRP Product' :
                             ($row['ProdType2'] == '2' ? 'Making Product' : 'Other Product')),
            "PurchasePrice" => number_format($row["PurchasePrice"], 2),
            "Price" => number_format($row["MinPrice"], 2),
            "Status" => ($row['Status'] == '1' ? 'Publish' : 'Not Publish'),
           
            "RegisterDate" => date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate'])))
        ];
    }

    echo json_encode([
        "status" => "success",
        "products" => $products
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch products: " . $e->getMessage()
    ]);
}
?>
