<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

include 'db.php'; // change path if needed

$response = [];
$userid = $_REQUEST['user_id'];
$sql = "SELECT submenuid FROM tbl_users_bill WHERE id='$userid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$MenuId = $row['submenuid'];

$query = "SELECT * FROM tbl_sub_menu WHERE id IN ($MenuId) ORDER BY id ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode([
        "status" => false,
        "message" => "Query failed: " . mysqli_error($conn)
    ]);
    exit;
}

$menu_items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $menu_items[] = [
        "id"           => (int)$row['id'],
        "menuid"       => $row['menuid'],
        "title"        => $row['title'],
        "table_values" => $row['table_values'],
        "link"         => "http://hpclpos.com/flutter_api/fradmin/".$row['link'],
        "srno"         => (int)$row['srno']
    ];
}

echo json_encode([
    "status" => true,
    "menu" => $menu_items
]);
?>
