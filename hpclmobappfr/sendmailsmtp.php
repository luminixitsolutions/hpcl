<?php
include('class.phpmailer.php');

include('class.smtp.php');
if($to!="")
{
	$mail = new PHPMailer();
	$mail->IsSMTP();                                   // send via SMTP
	$mail->Host     = "mail.mahabuddy.com"; // SMTP server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "noreply@mahabuddy.com";  // SMTP username
	$mail->Password = "e_ImUXIu1xdU"; // SMTP password
	$mail->From     = "noreply@mahabuddy.com";
	$mail->FromName = "Maha Buddy";                    
     $mail->SMTPSecure = 'tls';                    
     $mail->Port = 587;   
	$mail->IsHTML(true); 
	
	$mail->AddAddress($to);
		$mail->AddAddress($allmail);
    $mail->addBCC('rajatdh07@gmail.com');
	
	$mail->AddReplyTo("noreply@mahabuddy.com");
	
	$mail->WordWrap = 100;      // set word wrap
	//$mail->AddAttachment($file_name);                         // attachment
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  $subject;
	$mail->Body   =  $body;
	
	 //temp disable
	if(!$mail->Send())
	{
	echo "Message was not sent";
	echo "Mailer Error: " . $mail->ErrorInfo;
	exit;
	}
	else
	{
	//echo "Message was sent";
	}
	unlink($file_name);
	

} ?>