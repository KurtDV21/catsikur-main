<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/otp.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header>
    <nav class="navbar">
    <img src="logo1.png" alt="logo" class="logo">

    
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

    <ul class="nav-link">
        <li><a href="#home">HOME</a></li>
        <li><a href="#ourcat">OUR CATS</a></li>
        <li><a href="">ABOUT</a></li>
        <li><a href="">FAQs</a></li>
        <li><button class="login-btn" onclick="location.href='/loginto'">Login</button></li>     
    </ul>
    </nav>
  </header>    


<section id="main">
<div class="otp-container">
  <form class="otp-Form">
    <span class="mainHeading">Enter OTP</span>
    <p class="otpSubheading">We have sent a verification code to your email address</p>
    <div class="inputContainer">
      <input required="required" maxlength="1" type="text" class="otp-input" id="otp-input1">
      <input required="required" maxlength="1" type="text" class="otp-input" id="otp-input2">
      <input required="required" maxlength="1" type="text" class="otp-input" id="otp-input3">
      <input required="required" maxlength="1" type="text" class="otp-input" id="otp-input4">
      <input required="required" maxlength="1" type="text" class="otp-input" id="otp-input5">
      <input required="required" maxlength="1" type="text" class="otp-input" id="otp-input6">
    </div>
    <button class="verifyButton" type="submit">Verify</button>
    <button class="exitBtn">×</button>
    <p class="resendNote">Didn't receive the code? <button class="resendBtn">Resend Code</button></p>
  </form>
</div>
</section>

<section id="about" class="about">
    <div class="footer-container">
        <div class="about-company">

        <div class="info-item">
        <img src="place.png" alt="" class="place-icon">
        <p><a href="">9A Masambong St. Bahay Toro, Quezon City</a></p>
    </div>
    <div class="info-item">
        <img src="phone.png" alt="" class="phone-icon">
        <p><a href="">09123456789</a></p>
    </div>
    <div class="info-item">
        <img src="email.png" alt="" class="email-icon">
        <p><a href="">catfreeadopt@email.com</a></p>
    </div>
        </div>


        <div class="details">
        <h3>ABOUT COMPANY</h3>
            <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente<br> doloremque et dolores doloribus est animi maiores. Ut fugiat <br> molestiae nam quia earum qui aliquid aliquid ab corrupti officiis. Et<br> temporibus quia 33 incidunt adipisci ea deleniti vero 33<br> reprehenderit repellat.</p>
            
            
            <a href="https://www.facebook.com/suuupperrb" target="_blank">
                <img src="facebook.png" alt="Facebook" class="fb-icon">
            </a>
            
            <a href="https://www.messenger.com" target="_blank">
                <img src="messenger.png" alt="Messenger" class="mess-icon">
            </a>
     
        </div>
    </div>

  </section>
  

  <footer class = "footer">
    Cats Free Adoption & Rescue Philippines
  </footer>



</body>
</html>