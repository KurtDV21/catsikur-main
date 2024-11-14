<?php
session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Combine all OTP input fields into one variable
    $entered_otp = $_POST["otp-input1"] . $_POST["otp-input2"] . $_POST["otp-input3"] . 
                   $_POST["otp-input4"] . $_POST["otp-input5"] . $_POST["otp-input6"];
    
    if ($entered_otp == $_SESSION["otp"]) {
        // OTP is correct, log the user in
        header("Location: /user-homepage");
        exit;
    } else {
        $is_invalid = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/otp.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
<header>
    <nav class="navbar">
        <img src="/image/logo1.png" alt="logo" class="logo">
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>

<section id="main">
    <div class="otp-container">
        <form method="post" class="otp-Form">
            <span class="mainHeading">Enter OTP</span>
            <p class="otpSubheading">We have sent a verification code to your email address</p>
            
            <!-- Display an error message if OTP is invalid -->
            <?php if ($is_invalid): ?>
                <em class="error-message">Invalid OTP. Please try again.</em>
            <?php endif ?>

            <div class="inputContainer">
                <!-- Input fields for each OTP digit -->
                <input required maxlength="1" type="text" class="otp-input" name="otp-input1" id="otp-input1">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input2" id="otp-input2">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input3" id="otp-input3">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input4" id="otp-input4">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input5" id="otp-input5">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input6" id="otp-input6">
            </div>
            
            <!-- Verify button submits the form instead of redirecting directly -->
            <button class="verifyButton" type="submit">Verify</button>
            <button type="button" class="exitBtn">×</button>
            <p class="resendNote">Didn't receive the code? <button type="button" class="resendBtn">Resend Code</button></p>
        </form>
    </div>
</section>

<section id="about" class="about">
    <div class="footer-container">
        <div class="about-company">
            <div class="info-item">
                <img src="place.png" alt="" class="place-icon">
                <p><a href="#">9A Masambong St. Bahay Toro, Quezon City</a></p>
            </div>
            <div class="info-item">
                <img src="phone.png" alt="" class="phone-icon">
                <p><a href="#">09123456789</a></p>
            </div>
            <div class="info-item">
                <img src="email.png" alt="" class="email-icon">
                <p><a href="#">catfreeadopt@email.com</a></p>
            </div>
        </div>

        <div class="details">
            <h3>ABOUT COMPANY</h3>
            <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente doloremque et dolores doloribus...</p>
            <a href="https://www.facebook.com/suuupperrb" target="_blank">
                <img src="facebook.png" alt="Facebook" class="fb-icon">
            </a>
            <a href="https://www.messenger.com" target="_blank">
                <img src="messenger.png" alt="Messenger" class="mess-icon">
            </a>
        </div>
    </div>
</section>

<footer class="footer">
    Cats Free Adoption & Rescue Philippines
</footer>
</body>
</html>
