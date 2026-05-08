<?php
header("Content-Type: application/json");
include 'db.php';

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

$sql = "SELECT * FROM tbl_products WHERE category_id = $category_id ORDER BY name LIMIT 100";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode(['status' => true, 'data' => $products]);
?>

// <?php
// include "db.php";
// $userId = $_POST['user_id'];
// $sql = "SELECT * FROM tbl_products WHERE FIND_IN_SET('$userId', user_id)";
// $result = mysqli_query($conn, $sql);
// $data = [];
// while($row = mysqli_fetch_assoc($result)){
//     $data[] = $row;
// }
// echo json_encode($data);
// ?>

