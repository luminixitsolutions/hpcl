<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

require_once '../config.php';

try {
    $BillSoftFrId = $_REQUEST['user_id'] ?? '';
    $CatId = $_REQUEST['CatId'] ?? '';
    $SubCatId = $_REQUEST['SubCatId'] ?? '';
    $ProdType2 = $_REQUEST['ProdType2'] ?? '';

    if (empty($BillSoftFrId)) {
        throw new Exception("BillSoftFrId is required");
    }

    $sql = "SELECT p.*, c.Name AS Category, cs.Name AS SubCatName
            FROM tbl_cust_products_2025 p
            LEFT JOIN tbl_cust_category_2025 c ON c.id = p.CatId
            LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id = p.SubCatId
            WHERE p.id=1";

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
        $products[] = [
            "id" => $row['id'],
            "ProductName" => $row['ProductName'],
            "BarcodeNo" => $row['BarcodeNo'],
            "Category" => 1,
            "SubCategory" => 2,
            "SgstPer" => number_format($row["SgstPer"], 2),
            "CgstPer" => number_format($row["CgstPer"], 2),
            "IgstPer" => number_format($row["IgstPer"], 2),
            "CgstAmt" => number_format($row["CgstAmt"], 2),
            "SgstAmt" => number_format($row["SgstAmt"], 2),
            "IgstAmt" => number_format($row["IgstAmt"], 2),
            
            "PurchasePrice" => number_format($row["PurchasePrice"], 2),
            "WithoutGstPrice" => number_format($row["ProdPrice"], 2),
            "GstAmt" => number_format($row["GstAmt"], 2),
            "Price" => number_format($row["MinPrice"], 2),
            "Status" => 1,
            "StockQty"=>""
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
