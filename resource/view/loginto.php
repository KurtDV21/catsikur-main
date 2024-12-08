<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$is_invalid = false;

// Initialize session variables for tracking login attempts and lockout time
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the user is currently locked out
    if ($_SESSION['lockout_time'] && time() < $_SESSION['lockout_time']) {
        $lockout_remaining = $_SESSION['lockout_time'] - time();
        $is_invalid = true;
        $lockout_message = "Too many login attempts. Please try again in";
    } else {
        // Reset lockout if lockout period has expired
        if ($_SESSION['lockout_time'] && time() >= $_SESSION['lockout_time']) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;
        }

        // Process login
        $user = $userController->login($_POST["email"], $_POST["password"]);

        if ($user) {
            // Reset attempts on successful login
            $_SESSION['login_attempts'] = 0;

            // Generate OTP
            $otp = rand(100000, 999999);
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["otp"] = $otp;
            $_SESSION["role"] = $user["role"];
            $_SESSION["user_email"] = $user["email"];

            // Mailer for sending OTP
            $mail = require __DIR__ . "/auth/mailer.php";

            try {
                $mail->setFrom('noreply@yourdomain.com', 'Mailer');
                $mail->addAddress($user["email"]);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is: $otp";

                if (!$mail->send()) {
                    $is_invalid = true;
                    $error_message = "Failed to send OTP. Please try again.";
                } else {
                    // Redirect based on role
                    if ($_SESSION["role"] === 'user') {
                        header("Location: /otp");
                        exit;
                    } else {
                        $is_invalid = true;
                    }
                }
            } catch (Exception $e) {
                $is_invalid = true;
                $error_message = "Mailer Error: " . $e->getMessage();
            }
        } else {
            // Increment login attempts on failure
            $_SESSION['login_attempts']++;

            // Lockout after 5 failed attempts
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['lockout_time'] = time() + 300; // 5 minutes lockout
                $is_invalid = true;
                $lockout_message = "Too many login attempts. Please try again in 5 minutes.";
            } else {
                $is_invalid = true;
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<header>
  <nav class="navbar">
    <img src="/image/logo1.png" alt="logo" class="logo">

    
      <ul class="nav-link">
      <li><a href="/">HOME</a></li>
      <li><a href="/#ourcats">OUR CATS</a></li> 
        <li><a href="">ABOUT</a></li>
        <li><a href="">FAQs</a></li>
        <button class="login-btn" onclick="location.href='/loginto'">Login</button>
      </ul>
    
    </div>
  </nav>
</header>


<div class="wrapper">
    <span class="icon-close"><ion-icon name="close"></ion-icon></span>
    <div class="form-box login">
      <h2>Login</h2>
      <form action="#" method="post">
        <div class="input-box">
          <span class="icon"><ion-icon name="people"></ion-icon></span>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>" required>
          <label for="email">Email</label>
        </div>

        <div class="input-box">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" id="password" name="password" required>
          <label for="password">Password</label>
        </div>

        <?php if ($is_invalid): ?>
            <p style="color: red;">
                <?= isset($lockout_message) ? htmlspecialchars($lockout_message) : "Invalid login. Please check your email and password."; ?>
                <span id="countdown"></span>.
            </p><br>
        <?php endif; ?>

        <div class="remember-forgot">
          <a href="/forgot-password" class="forgot"><b>FORGOT PASSWORD?</b></a>
        </div>
        <button type="submit" href="/user-homepage" class="btn"><b>Login</b></button>

        <div class="login-register">
          <p >Don't have an account? <a href="/register-form" class="register-link"><b>Register</b></a></p>

        </div>
      </form>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const countdownElement = document.getElementById("countdown");
    const remainingTime = <?= isset($lockout_remaining) ? $lockout_remaining : 0 ?>;

    if (remainingTime > 0) {
        let timeLeft = remainingTime;

        const updateCountdown = () => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            countdownElement.textContent = `${minutes}m ${seconds}s`;

            if (timeLeft > 0) {
                timeLeft--;
            } else {
                countdownElement.textContent = "You can try logging in now.";
                clearInterval(timer);
            }
        };

        // Initial call to display immediately
        updateCountdown();

        // Update every second
        const timer = setInterval(updateCountdown, 1000);
    }
});
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>