<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/register.css">
    <script src="https://cdn.jsdelivr.net/npm/just-validate@latest/dist/just-validate.production.min.js"></script>
    <script src="/js/validation.js" defer></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header>
    <nav class="navbar">
        <img src="/image/logo1.png" alt="logo" class="logo">

        <div class="nav-container"> <!-- New div to contain nav links -->
            <ul class="nav-link">
                <li><a href="/">HOME</a></li>
                <li><a href="/#ourcats">OUR CATS</a></li>
                <li><a href="/rules">ABOUT</a></li>
                <li><a href="/faq">FAQs</a></li>
                <button class="login-btn">
                    <a href="/loginto">Login</a>
                </button>
            </ul>
        </div>
    </nav>
</header>

<section id="main">
  <div class="container-middle">
    <div class="wrapper">
        <span class="icon-close"><ion-icon name="close"></ion-icon></span>
        <div class="form-box register">
            <h2>Register</h2>
            <form id="signup" action="/process-signup" method="post">

                <div class="input-box">
                    <span class="icon"><ion-icon name="people"></ion-icon></span>
                    <input name="name" id="name" type="user" required>
                    <label for="name">Username</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="people"></ion-icon></span>
                    <input type="text" name="email" id="email" required>
                    <label for="email">Email</label>
                    <span id="email-error" class="error-message"></span> <!-- Error message span -->
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input name="password" id="password" type="password" required>
                    <label for="password">Password</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input name="confirm-password" id="confirm-password" type="password" required>
                    <label for="confirm-password">Confirm Password</label>
                    <span id="password-error" class="error-message"></span> <!-- Error message span -->
                </div>

                <button type="submit" id="submit-btn" class="btn">
                    <b>Register</b>
                </button>

                <div class="terms">
                    <label>
                    <button type="button" id="terms-link" class="terms-link">Terms and Conditions</button>
                        <input type="checkbox" id="terms" required>
                    </label>
                </div>

                <div class="login-register">
                    <p>Already have an account? 
                        <a href="/loginto" class="register-link"><b>Login</b></a>
                    </p>
                </div>
            </form>
        </div>
    </div>
  </div>
</section>

<div id="terms-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Terms and Conditions for Online Pet Adoption System</h2>
    <p><strong>1. Introduction</strong><br>
    This document outlines the terms and conditions for the use of the Online Pet Adoption System, created by third-year IT students from the University of Caloocan City for academic purposes. The system is provided to Cats Free Adoption and Rescue Philippines free of charge.</p>

    <p><strong>2. Purpose</strong><br>
    The system is designed to assist users in browsing available pets, submitting inquiries, and accessing information provided by pet owners or rescuers. It aims to streamline the adoption process for Cats Free Adoption and Rescue Philippines.</p>

    <p><strong>3. Non-Commercial Use</strong><br>
    The system is offered to Cats Free Adoption and Rescue Philippines as a non-commercial service, with no fees or payments required for its use. It is intended solely for voluntary and non-profit purposes.</p>

    <p><strong>4. Responsibility for Content</strong><br>
    Users who post pets for adoption are responsible for keeping their listings updated, including pet availability and any other relevant information. The system is designed to automate as much of the process as possible, reducing the need for manual intervention by the admins of Cats Free Adoption and Rescue Philippines. Admins will only need to oversee the general platform use and ensure that all posts comply with community guidelines.</p>

    <p><strong>5. Data Security</strong><br>
    The system will not collect or store sensitive personal data. Only basic information, such as pet profiles, inquiries, and contact details, will be stored. All user information will be kept secure and will not be shared with third parties.</p>

    <p><strong>6. Accuracy of Information</strong><br>
    The developers of the system are not responsible for the accuracy of the information provided by users. The system depends on timely updates from pet owners, rescuers, and admins of Cats Free Adoption and Rescue Philippines to ensure the accuracy of available data.</p>

    <p><strong>7. Discontinuation</strong><br>
    Either Cats Free Adoption and Rescue Philippines or the developers may discontinue use of the system at any time. If Cats Free Adoption and Rescue Philippines wishes to stop using the system, they may inform the developers, and access will be discontinued.</p>

    <button class="modal-close-btn">I Understand</button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
  // Get the modal
  var modal = document.getElementById("terms-modal");

  // Get the button that opens the modal
  var btn = document.getElementById("terms-link");

  // Get the <span> element and button that closes the modal
  var closeSpan = document.getElementsByClassName("close")[0];
  var closeButton = document.querySelector(".modal-content .modal-close-btn");

  // When the user clicks the button, open the modal
  btn.onclick = function(event) {
    event.preventDefault(); // Prevent any default action
    modal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  closeSpan.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks on the close button, close the modal
  closeButton.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

  // Display error messages based on URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const errorMessage = urlParams.get('error');
  if (errorMessage) {
    if (errorMessage === 'Email is already used') {
      const emailError = document.getElementById("email-error");
      emailError.textContent = "Email is already used";
      emailError.style.color = "red";
    } else if (errorMessage === 'Passwords do not match') {
      const passwordError = document.getElementById("password-error");
      passwordError.textContent = "Passwords do not match";
      passwordError.style.color = "red";
    }
  }
});

document.getElementById("signup").addEventListener("submit", function(event) {
    var isValid = true;
    var requiredFields = document.querySelectorAll("#signup input[required]");
    var emailError = document.getElementById("email-error");
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;
    var passwordError = document.getElementById("password-error");

    requiredFields.forEach(function(field) {
        if (!field.value) {
            isValid = false;
            field.classList.add("invalid");
            alert(field.labels[0].textContent + " is required");
        } else {
            field.classList.remove("invalid");
        }
    });

    if (password !== confirmPassword) {
        isValid = false;
        passwordError.textContent = "Passwords do not match";
        passwordError.style.color = "red";
    } else {
        passwordError.textContent = "";
    }
    
    if (!isValid) {
        event.preventDefault(); // Prevent form submission
    }
});
</script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https[_{{{CITATION{{{_1{](https://github.com/LennonStolk/google-news-api/tree/07474ba9ef82e5e362b212ab791fc78a25a7ffec/index.php)[_{{{CITATION{{{_2{](https://github.com/vasia-kos/myLoginSystem-PHP-MySQLi-CSS-HTML/tree/b33d959c531e66d53effabbf3eb8661da6e611b7/header.php)[_{{{CITATION{{{_3{](https://github.com/jcampanotto/a-list-old/tree/492ddcda2e7a8377bbce28e86cb2e3ecea553d67/js%2Fmodal.js)[_{{{CITATION{{{_4{](https://github.com/Kevin-Kinyua/thia/tree/5950b45f14d782a56af3f9b2eca99964fc19ea82/deposit.php)[_{{{CITATION{{{_5{](https://github.com/03sid-sharma/Scribbler-Frontend/tree/21604dc839867869253945589c6d685e2ad4f362/scripts%2Fcomponent.js)