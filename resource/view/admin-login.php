<?php

use App\Core\Database;
use App\Models\Admin;
use App\Controllers\AdminController;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$adminModel = new Admin($dbConnection);
$adminController = new AdminController($adminModel);
  
$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admin = $adminController->login($_POST["email"], $_POST["password"]);
    
    if ($admin) {
        // Generate OTP
        $otp = rand(100000, 999999);
        session_regenerate_id();
        $_SESSION["user_id"] = $admin["id"];
        $_SESSION["user_name"] = $admin["name"];
        $_SESSION["otp"] = $otp;
        $_SESSION["role"] = $admin["role"];
        
        // Mailer for sending OTP
        $mail = require __DIR__ . "/auth/mailer.php";
        $mail->setFrom('noreply@yourdomain.com', 'Your App');
        $mail->addAddress($admin["email"]);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: $otp";

        // Redirect based on role
        if ($_SESSION["role"] === 'admin') {
            header("Location: /admin");
            exit; // Ensure that the script stops after redirection
        } else {
            // If the login is invalid or not an admin, set the error flag
            $is_invalid = true;
        }   
    } else {
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

<?php if ($is_invalid): ?>
    <em>Invalid login. Please check your email and password.</em>   
<?php endif ?>
    
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

        <div class="remember-forgot">
          <a href="/forgot-password" class="forgot"><b>FORGOT PASSWORD?</b></a>
        </div>
        <button type="submit" href="/user-homepage" class="btn"><b>Login</b></button>

        <div class="login-register">
          <p >Want to create an Admin Account? <a href="/admin-register" class="register-link"><b>Register</b></a></p>
          <a href="/loginto" class="register-link"><b>User</b></a></p>
        </div>
        </div>
      </form>
    </div>



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>