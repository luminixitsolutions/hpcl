<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['Admin']['id'])) {
    header('Location:../index.php');
    exit;
}

include_once __DIR__ . '/../shop_admin_helper.php';

$user_id = $_SESSION['Admin']['id'];
$uid = $_REQUEST['uid'] ?? $_REQUEST['user_id'] ?? '';
if ($uid === '') {
    $sql11 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
    $row = getRecord($sql11);
    if ($row) {
        $_SESSION['Admin'] = $row;
    }
} else {
    $sql11 = "SELECT * FROM tbl_users_bill WHERE id='$uid'";
    $row = getRecord($sql11);
    if ($row) {
        $_SESSION['Admin'] = $row;
    }
}

if (!isset($_SESSION['Admin']['id'])) {
    header('Location:../index.php');
    exit;
}

if (isShopAdmin()) {
    $dealerId = (int)($_REQUEST['id'] ?? $_SESSION['fr_admin'] ?? 0);
    if (!shopAdminCanAccessDealer($dealerId)) {
        header('Location: ../dashboard.php');
        exit;
    }
    if ($dealerId > 0) {
        $_SESSION['fr_admin'] = $dealerId;
    }
}
?>
