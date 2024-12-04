<?php 

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../../../vendor/autoload.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 2525;
$mail->Username = '74ebc0aaaa06a1';
$mail->Password = '2397f3d8c486b5'; 

$mail->isHTML(true);    
    
// Looking to send emails in production? Check out our Email API/SMTP product!
return $mail;       
?>

