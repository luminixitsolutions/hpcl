
<?php
include 'db.php';

$token = $_POST['token'];
$res = $conn->query("SELECT id FROM users WHERE token='$token'");
if ($res->num_rows == 0) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}
$user = $res->fetch_assoc();
$user_id = $user['id'];

$status = $_POST['status'];
$sql = "INSERT INTO attendance (user_id, status) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $status);

echo json_encode(["success" => $stmt->execute()]);
?>
