<?php
session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entered_otp = $_POST["otp-input1"] . $_POST["otp-input2"] . $_POST["otp-input3"] . 
                   $_POST["otp-input4"] . $_POST["otp-input5"] . $_POST["otp-input6"];
    
    if ($entered_otp == $_SESSION["otp"]) {
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
            
            <?php if ($is_invalid): ?>
                <em class="error-message">Invalid OTP. Please try again.</em>
            <?php endif ?>

            <div class="inputContainer">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input1" id="otp-input1" oninput="filterNonNumeric(this); moveFocus(this)">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input2" id="otp-input2" oninput="filterNonNumeric(this); moveFocus(this)">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input3" id="otp-input3" oninput="filterNonNumeric(this); moveFocus(this)">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input4" id="otp-input4" oninput="filterNonNumeric(this); moveFocus(this)">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input5" id="otp-input5" oninput="filterNonNumeric(this); moveFocus(this)">
                <input required maxlength="1" type="text" class="otp-input" name="otp-input6" id="otp-input6" oninput="filterNonNumeric(this); moveFocus(this)">
            </div>
            
            <button class="verifyButton" type="submit">Verify</button>
            <button type="button" class="exitBtn">×</button>
            <p class="resendNote">Didn't receive the code? <button type="button" class="resendBtn" id="resendBtn">Resend Code</button></p>
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

        <div class="socials">
            <p>Follow us:</p>
            <div class="socials-icons">
                <img src="facebook.png" alt="">
                <img src="twitter.png" alt="">
                <img src="instagram.png" alt="">
            </div>
        </div>

        <div class="copyright">
            <p>© 2024 Cat Free Adopt. All Rights Reserved.</p>
        </div>
    </div>
</section>

<div id="otpModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <p id="otpMessage"></p>
    </div>
</div>

<script>
document.getElementById("resendBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/resend-otp", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            var modal = document.getElementById("otpModal");
            var otpMessage = document.getElementById("otpMessage");

            if (response.success) {
                otpMessage.textContent = "OTP resent successfully. Please check your email.";
            } else {
                otpMessage.textContent = "Failed to resend OTP. Please try again later.";
            }

            modal.style.display = "block";

            document.querySelector(".close-button").onclick = function() {
                modal.style.display = "none";
            };

            var resendBtn = document.getElementById("resendBtn");
            resendBtn.disabled = true;
            var timer = 60;

            var countdown = setInterval(function() {
                timer--;
                resendBtn.textContent = "Resend Code (" + timer + ")";
                if (timer === 0) {
                    clearInterval(countdown);
                    resendBtn.disabled = false;
                    resendBtn.textContent = "Resend Code";
                }
            }, 1000);
        }
    };
    xhr.send();
});

function moveFocus(current) {
    if (current.value.length > 0) { // Check if the current input has a value
        var nextInput = current.nextElementSibling;
        if (nextInput) {
            nextInput.focus();
        }
    }
}

function filterNonNumeric(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>

</body>
</html>
