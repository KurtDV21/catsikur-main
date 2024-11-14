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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user = $userController->login($_POST["email"], $_POST["password"]);
  
  if ($user) {
      // Generate OTP
      $otp = rand(100000, 999999);
      session_regenerate_id();
      $_SESSION["user_id"] = $user["id"];
      $_SESSION["user_name"] = $user["name"];
      $_SESSION["otp"] = $otp;
      $_SESSION["role"] = $user["role"];
      
      // Mailer for sending OTP
      $mail = require __DIR__ . "/auth/mailer.php";
      $mail->setFrom('noreply@yourdomain.com', 'Your App');
      $mail->addAddress($user["email"]);
      $mail->Subject = 'Your OTP Code';
      $mail->Body = "Your OTP code is: $otp";

      // Check if the user has the 'user' role
      if ($_SESSION["role"] === 'user') {
          // Redirect to user homepage if role is 'user'
          header("Location: /user-homepage");
          exit; // Make sure the script stops after redirection
      } else {
          // Set the invalid flag if role is not 'user'
          $is_invalid = true;
      }
  } else {
      // Set the invalid flag if user does not exist
      $is_invalid = true;
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
        <li><a href="">OUR CATS</a></li>
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
            <p style="color: red;"><em>Invalid login. Please check your email and password.</em></p></br>   
        <?php endif ?>

        <div class="remember-forgot">
          <a href="/forgot-password" class="forgot"><b>FORGOT PASSWORD?</b></a>
        </div>
        <button type="submit" href="/user-homepage" class="btn"><b>Login</b></button>

        <div class="login-register">
          <p >Don't have an account? <a href="/register-form" class="register-link"><b>Register</b></a></p>
          <p><a href="/admin-login" class="register-link"><b>Admin</b></a></p>

        </div>
      </form>
    </div>



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>