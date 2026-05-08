<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include 'db.php';
require 'mailvendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$session_uid = addslashes(trim($_REQUEST['session_uid']));
$ClosedAt    = addslashes(trim($_REQUEST['closed_at']));
$ClosingCash = isset($_REQUEST['closing_cash']) ? (float)$_REQUEST['closing_cash'] : 0;
$ClosingNote = addslashes(trim($_REQUEST['closing_note'] ?? ''));

$q = mysqli_query($conn, "SELECT FrId,UserId FROM tbl_shop_sessions WHERE session_uid='$session_uid' LIMIT 1");
    $row = mysqli_fetch_assoc($q);
$UserId = $row['UserId'];

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

// ✅ Update shop session
$sql = "UPDATE tbl_shop_sessions
        SET ClosedAt='$ClosedAt', ClosingCash=$ClosingCash, ClosingNote='$ClosingNote'
        WHERE session_uid='$session_uid'";

if (mysqli_query($conn, $sql)) {
    // Fetch details for mail
    $q = mysqli_query($conn, "SELECT ts.*, u.Fname, u.ShopName 
                              FROM tbl_shop_sessions ts 
                              LEFT JOIN tbl_users_bill u ON ts.UserId = u.id
                              WHERE ts.session_uid='$session_uid' LIMIT 1");
    $row = mysqli_fetch_assoc($q);

    $ShopName    = $row['ShopName'] ?? 'Unknown Shop';
    $UserName    = $row['Fname'] ?? 'Cashier';
    $ClosedAt    = $row['ClosedAt'];
    $ClosingCash = $row['ClosingCash'];
    $ClosingNote = $row['ClosingNote'];
    $ClosedAtFormatted = date("d/m/Y h:i A", strtotime($ClosedAt));
    // ✅ Send Mail
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'mail.persistsolution.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@persistsolution.com'; 
        $mail->Password = '5%t7tkJMYH%W'; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587;

        $mail->setFrom("noreply@persistsolution.com", "MAHACHAI PVT LTD");
        //$mail->addAddress("rajatdh07@gmail.com");
        $mail->addReplyTo("support@persistsolution.com", "Support Team");

        // CC recipients
        ///$cc = 'nileshgiradkar1@gmail.com';
        $ccRecipients = explode(',', $cc);
        foreach ($ccRecipients as $email) {
            $email = trim($email);
            if (!empty($email)) {
                $mail->addCC($email);
            }
        }

        $mail->isHTML(true);
        $mail->Subject = "Shop Closed - {$ShopName}";

        // Plain-text body
        $mail->AltBody = "Shop Closed Notification\n\n"
            . "Date & Time: {$ClosedAtFormatted}\n"
            . "Shop / Franchise: {$ShopName}\n"
            . "Cashier: {$UserName}\n"
            
            . "Regards,\nMAHACHAI PVT LTD";

        // HTML body
        $mail->Body = "
            <h3>Shop Closed Notification</h3>
            <p>
            Date & Time: {$ClosedAtFormatted}<br>
            Shop / Franchise: {$ShopName}<br>
            Cashier: {$UserName}<br>
          
            </p>
            <p>Regards,<br>MAHACHAI PVT LTD</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log error silently
    }

    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
}
?>
