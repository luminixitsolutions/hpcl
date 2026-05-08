
<?php
include 'db.php';

$token = $_POST['token'];
$res = $conn->query("SELECT id FROM users WHERE token='$token'");
if ($res->num_rows == 0) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}
$user = $res->fetch_assoc();
$user_id = $user['id'];

$reason = $_POST['reason'];

$sql = "INSERT INTO leave_requests (user_id, reason) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $reason);

echo json_encode(["success" => $stmt->execute()]);
?>
