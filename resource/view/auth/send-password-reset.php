<?php
use App\Core\Database;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $userModel->findByEmail($_POST["email"]);
    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expiresAt = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $token_hash = hash("sha256", $token);

        $userModel->updateResetToken($user['id'], $token_hash, $expiresAt);

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '2078dd9b83a328';
            $mail->Password = '7d6b474b619e9b';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;
            $mail->SMTPDebug = 2;

            $mail->setFrom('noreply@example.com', 'Mailer'); 
            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $resetLink = "http://localhost:8000/reset-password?token=$token";
            $mail->Body = "Click here to reset your password: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            header("Location:/email-check");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "The email address is not found.";
    }
}
?>
