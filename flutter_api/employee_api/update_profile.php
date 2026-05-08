
<?php
include 'db.php';

$token = $_POST['token'];
$res = $conn->query("SELECT id FROM users WHERE token='$token'");
if ($res->num_rows == 0) {
    die(json_encode(["success" => false, "message" => "Unauthorized"]));
}
$user = $res->fetch_assoc();
$id = $user['id'];

$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$imagePath = '';

if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    $imagePath = $targetDir . time() . "_" . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
}

$sql = "UPDATE users SET name=?, email=?, address=?, profile_image=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $email, $address, $imagePath, $id);

echo json_encode(["success" => $stmt->execute()]);
?>
