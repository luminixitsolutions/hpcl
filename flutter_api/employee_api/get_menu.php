<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

include 'db.php'; // change path if needed

$response = [];
$userid = $_REQUEST['user_id'];
$sql = "SELECT menu_ids FROM tbl_users_bill WHERE id='$userid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$MenuId = $row['menu_ids'];

$query = "SELECT * FROM tbl_dynamic_menu WHERE status = 1 AND id IN ($MenuId) ORDER BY id ASC";
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
        "id"        => (int)$row['id'],
        "title"     => $row['title'],
        "icon"      => $row['icon'],
        "color"     => $row['color'],
        "route"     => $row['route'],
        "open_web"  => (int)$row['open_web'],
        "url"       => "http://hpclpos.com/flutter_api/fradmin/".$row['url'],
        "status"    => (int)$row['status'],
        "srnum"    => (int)$row['srnum']
    ];
}

echo json_encode([
    "status" => true,
    "menu" => $menu_items
]);
?>