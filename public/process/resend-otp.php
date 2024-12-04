<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

if (!isset($_SESSION["user_email"])) {
    echo json_encode(['error' => 'Email not available. Please log in again.']);
    exit;
}

// Generate a new OTP
$otp = rand(100000, 999999);
$_SESSION["otp"] = $otp;

// Send the OTP via email
$mail = require __DIR__ . "/auth/mailer.php";

if ($mail !== false) {
    $mail->setFrom('noreply@yourdomain.com', 'Your App');
    $mail->addAddress($_SESSION["user_email"]); // Use stored email from session
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Your OTP code is: $otp";

    if ($mail->send()) {
        echo json_encode(['success' => 'OTP resent successfully.']);
    } else {
        echo json_encode(['error' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['error' => 'Mailer initialization failed.']);
}
?>
