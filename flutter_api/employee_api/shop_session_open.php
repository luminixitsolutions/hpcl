<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include 'db.php';
require 'mailvendor/autoload.php'; // PhpSpreadsheet + PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$session_uid   = addslashes(trim($_REQUEST['session_uid']));
$FrId          = (int)$_REQUEST['fr_id'];
$UserId        = (int)$_REQUEST['user_id'];
$OpenedAt      = addslashes(trim($_REQUEST['opened_at']));
$OpeningCash   = isset($_REQUEST['opening_cash']) ? (float)$_REQUEST['opening_cash'] : 0;
$OpeningNote   = addslashes(trim($_REQUEST['opening_note'] ?? ''));
$OpenedAtFormatted = date("d/m/Y h:i A", strtotime($OpenedAt));

 // Fetch user role and BillSoftFrId
    $sql77 = "SELECT Roll, BillSoftFrId,Fname,ShopName FROM tbl_users_bill WHERE id = '$UserId' LIMIT 1";
    $result77 = mysqli_query($conn, $sql77);

    if ($result77 && mysqli_num_rows($result77) > 0) {
        $row77 = mysqli_fetch_assoc($result77);
        $Roll = $row77['Roll'];

        // Determine BillSoftFrId
        if ($Roll == 5) {
            $BillSoftFrId = $UserId;
            $UserName = $row77['ShopName'];
        } else {
            $BillSoftFrId = $row77['BillSoftFrId'];
            $UserName = $row77['Fname'];
        }
}

$q = mysqli_query($conn, "SELECT ShopName FROM tbl_users_bill WHERE id='$BillSoftFrId' AND Roll=5 LIMIT 1");
    $row = mysqli_fetch_assoc($q);
$ShopName = $row['ShopName'];
// Upsert by session_uid (idempotent)
$sql = "INSERT INTO tbl_shop_sessions (session_uid, FrId, UserId, OpenedAt, OpeningCash, OpeningNote)
        VALUES ('$session_uid', $BillSoftFrId, $UserId, '$OpenedAt', $OpeningCash, '$OpeningNote')
        ON DUPLICATE KEY UPDATE
        FrId=$BillSoftFrId, UserId=$UserId, OpenedAt='$OpenedAt', OpeningCash=$OpeningCash, OpeningNote='$OpeningNote'";

if (mysqli_query($conn, $sql)) {
    $q = mysqli_query($conn, "SELECT id FROM tbl_shop_sessions WHERE session_uid='$session_uid' LIMIT 1");
    $row = mysqli_fetch_assoc($q);
    
   // ✅ Send Mail
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'mail.persistsolution.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@persistsolution.com'; 
    $mail->Password = '5%t7tkJMYH%W'; // Use real app password if possible
    $mail->SMTPSecure = 'tls'; 
    $mail->Port = 587;

    $mail->setFrom("noreply@persistsolution.com", "MAHACHAI PVT LTD");
    //$mail->addAddress("rajatdh07@gmail.com");
    $mail->addReplyTo("support@persistsolution.com", "Support Team");
    
    // CC recipients
        //$cc = 'nileshgiradkar1@gmail.com';
        $ccRecipients = explode(',', $cc);
        foreach ($ccRecipients as $email) {
            $email = trim($email);
            if (!empty($email)) {
                $mail->addCC($email);
            }
        }

    $mail->isHTML(true);
    $mail->Subject = "Shop Opened - {$ShopName}";

    // Add plain text for spam filters
    $mail->AltBody = "Shop Opened Notification\n\n"
        . "Date & Time: {$OpenedAtFormatted}\n"
        . "Shop / Franchise: {$ShopName}\n"
        . "Cashier: {$UserName}\n"
        . "Regards,\nMAHACHAI PVT LTD";

    // HTML body (simple formatting only)
    $mail->Body = "
        <h3>Shop Opened Notification</h3>
        <p>
        Date & Time: {$OpenedAtFormatted}<br>
        Shop / Franchise: {$ShopName}<br>
        Cashier: {$UserName}<br>
        </p>
        <p>Regards,<br>MAHACHAI PVT LTD</p>
    ";

    $mail->send();
    echo 'sent';
} catch (Exception $e) {
    // log error if needed
}

    
    echo json_encode(["status"=>"success","data"=>["server_session_id"=>$row['id']]]);
} else {
    echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
}
?>