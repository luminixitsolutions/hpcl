<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'mailvendor/autoload.php';

$mail = new PHPMailer(true);

if (!empty($to)) {
    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.kwickbill.in';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@kwickbill.in';
        $mail->Password   = 'MuqMAZtkTW6QIhIA';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('noreply@kwickbill.in', 'HPCL Orders');

        $recipients = explode(',', $to);
        foreach ($recipients as $email) {
            $email = trim($email);
            if (!empty($email)) {
                $mail->addAddress($email);
            }
        }

        $cc = isset($cc) ? $cc : 'nileshgiradkar1@gmail.com';
        $ccRecipients = explode(',', $cc);
        foreach ($ccRecipients as $email) {
            $email = trim($email);
            if (!empty($email)) {
                $mail->addCC($email);
            }
        }

        $mail->isHTML(true);
        $mail->Subject = $subject ?? '(No Subject)';
        $mail->Body    = $message ?? '';
        $mail->AltBody = 'Please enable HTML to view this message.';

        if ($mail->send()) {
            //echo json_encode(['mail' => 'sent', 'to' => $to]);
        } else {
            //echo json_encode(['mail' => 'failed', 'reason' => $mail->ErrorInfo]);
        }
    } catch (Exception $e) {
        //echo json_encode(['mail' => 'error', 'error' => $mail->ErrorInfo]);
    }
} else {
    //echo json_encode(['mail' => 'skipped', 'reason' => 'No recipient']);
}
?>
