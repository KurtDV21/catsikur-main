<?php 

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../../../vendor/autoload.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 2525;
$mail->Username = '2078dd9b83a328';
$mail->Password = '7d6b474b619e9b'; 

$mail->isHTML(true);

// Looking to send emails in production? Check out our Email API/SMTP product!
return $mail;       
?>