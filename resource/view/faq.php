<?php
require_once __DIR__ . '/../../vendor/autoload.php';




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/rules.css">
    <link rel="stylesheet" href="/css/userdropdown.css"> <!-- Add your CSS file link if needed -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Rules</title>

</head>
<body>
<header>
<nav class="navbar">
     <?php include("header.php"); ?>
</header>


<section id="main">
  <div class="rules-container">
    <div class="house-rules">
      <h1>FAQ (Freqeuntly Ask Questions)</h1>
      <h2>Why was my Post got declined?</h2>
      <h3>Your post was declined for the following reasons:</h3>
      <ul>
        <li>Did not put all the required information.</li>
        <li>It's not related in adopting.</li>
        <li>Post is something of malicious photos</li>
        <li>Content is unavailabe</li>
        <li>Admin denied your post</li>
      </ul>
      <h2>Why was my POST got declined even if I have provided all the necessary info from the previous slides?</h2>
        <h3>Some posts will be AUTOMATICALLY declined by our Admin for the following reasons:</h3>
        <ul>
            <li>Account has no profile picture.</li>
            <li>Account has <strong>illegal</strong> activities.</li>
            <li>Author Post has fewer than 10 characters.</li>
            <li>Author is taking long to respond</li>
        </ul>
        <h2>Why does every time I COMMENT, it is being removed? Why were my COMMENTS got removed?</h2>
        <h3>Some comments will be AUTOMATICALLY removed by our WEBSITE for the following reasons:</h3>
        <ul>
            <li>Account does not have a profile picture.</li>
            <li>Account was reported by a fellow member in the past 28 days.</li>
            <li>Account commenting bad words.</li>
            <li>Account is banned for commenting.</li>
        </ul>
    </div>
  </div>
</section>

    
    
    <section id="about" class="about">
    <div class="footer-container">
        <div class="about-company">

        <div class="info-item">
        <img src="/image/place.png" alt="" class="place-icon">
        <p><a href="">9A Masambong St. Bahay Toro, Quezon City</a></p>
    </div>
    <div class="info-item">
        <img src="/image/phone.png" alt="" class="phone-icon">
        <p><a href="">09123456789</a></p>
    </div>
    <div class="info-item">
        <img src="/image/email.png" alt="" class="email-icon">
        <p><a href="">catfreeadopt@email.com</a></p>
    </div>
        </div>


        <div class="details">
        <h3>COMMUNITY RULES</h3>
            <p><a href="/rules">Rules and Regulations</a></p>
            <p><a href="/faq">FAQs</a></p>
            
            
            <a href="https://www.facebook.com/groups/1591906714301364" target="_blank">
                <img src="/image/facebook.png" alt="Facebook" class="fb-icon">
            </a>
            
            <a href="https://www.messenger.com" target="_blank">
                <img src="/image/messenger.png" alt="Messenger" class="mess-icon">
            </a>
     
        </div>
    </div>

  </section>
  

  <footer class = "footer">
    Cats Free Adoption & Rescue Philippines
  </footer>   
  <script src="/js/faq.js"></script>

</body>

</html>
