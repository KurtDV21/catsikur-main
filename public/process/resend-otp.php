<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

$mail = new PHPMailer(true);


$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 2525;
$mail->Username = '2078dd9b83a328';
$mail->Password = '7d6b474b619e9b';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $otp = rand(100000, 999999);
    $_SESSION["otp"] = $otp;
    
    $user_email = $_SESSION["user_email"];

    try {
        $mail->setFrom('noreply@yourdomain.com', 'Mailer');
        $mail->addAddress($user_email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: $otp";

        if (!$mail->send()) {
            echo json_encode(['success' => false, 'message' => 'Failed to resend OTP']);
        } else {
            echo json_encode(['success' => true, 'message' => 'OTP resent successfully']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $e->getMessage()]);
    }
}
