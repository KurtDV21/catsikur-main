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

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; 
    $user = $userModel->findUserById($userId); 
    $name = $user['name'] ?? ''; 
    $phone =$user['Phone_number'] ?? '';
    $email = $user['email'] ?? '';
  } else {
    $name = ''; 
    $phone = ''; 
    $email = '';
  }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/adoptform.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
      <!-- header nav-bar -->
  <header>
  <nav class="navbar">
     <img src="image/logo1.png" alt="logo" class="logo">

    <div class="nav-container">
   
      <!-- Hamburger Icon -->
      <div class="hamburger" onclick="toggleMenu()">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
      </div>

      <ul class="nav-link" id="nav-list">
        <li><a href="landing.php">HOME</a></li>
        <li><a href="#cats">OUR CATS</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#faqs">FAQs</a></li>
        <button onclick="visitPage()" class="login-btn">Login</button>
      </ul>
    </div>
  </nav>
</header>

<section>
<div class="outer-container">
        <div class="form-container">
            <h1>Cat Adoption Application Form</h1>
            <h2>Applicant Details</h2>
            <form>
            <div class="input-group"> <!-- Group for Name and Occupation inputs -->
                    <div class="input-box">
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($name); ?> ">
                        <label>Name</label>
                    </div>

                    <div class="input-box">
                        <input type="text" name="occupation" required placeholder=" ">
                        <label>Occupation</label>
                    </div>
                </div>


                <div class="input-box">
                    <input type="text" required placeholder=" ">
                    <label>Address</label>
                </div>
            <div class="input-group">
                <div class="input-box">
                    <input type="email" name="email" value=" <?php echo htmlspecialchars($email); ?>">
                    <label>Email Address</label>
                </div>

                <div class="input-box">
                    <input type="tel" name="phone" required value="<?php echo htmlspecialchars($phone); ?> ">
                    <label>Phone Number</label>
                </div>
            </div>
              

                <div class="input-box">
                    <textarea required placeholder=" "></textarea>
                    <label>Message</label>
                </div>

                <div class="btn-container">
                    <button type="reset" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm</button>
                </div>
            </form>
        </div>
         
    </div>
</section>
</body>
<footer class="footer">
        <div class="footer-container">
    <!-- About Section -->
    <div class="footer-about">
        <h3>ABOUT COMPANY</h3>
        <div class="para">
        <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente<br> doloremque et dolores doloribus est animi maiores. Ut fugiat <br> molestiae nam quia earum qui aliquid aliquid ab corrupti officiis. Et<br> temporibus quia 33 incidunt adipisci ea deleniti vero 33<br> reprehenderit repellat.</p>
        </div>
      
       
    </div>
 <!-- Social Media Section -->
    <div class="fb">
      <img src="facebook.png" alt="" class="fb-icon">
    </div>

    <div class="mess">
      <img src="messenger.png" alt="" class="mess-icon">
    </div>

   

</div>
 
<div class="details-container">
<div class="details">
<div class="place">
    <img src="place.png" alt="" class="place-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>

  <div class="phone">
    <img src="phone.png" alt="" class="phone-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>

  <div class="email">
    <img src="email.png" alt="" class="email-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>
  </div>
  </div>


  



    <!-- Left Bottom Text -->
    <div class="footer-left-text">
      <p>Cat Free Adoption & Rescue Philippines</p>
    </div>
  </div>
  
  
</footer>
</html>