<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

use App\Models\Inquiry;
use App\Controllers\InquiryController;

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
} else {
  $name = ''; 
}

// Form processing logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are set
    if (isset($_POST['user_id'], $_POST['post_id'], $_POST['name'], $_POST['occupation'], $_POST['address'], $_POST['email'], $_POST['phone'], $_POST['message'])) {
        $inquiryModel = new Inquiry($dbConnection);
        $inquiryController = new InquiryController($inquiryModel);
        
        // Call the method to handle the inquiry submission
        $inquiryController->submitAdoptionInquiry();
        
        // Optionally, redirect to a success page or show a success message
        header('Location: /user-homepage?user_id=' . urlencode(htmlspecialchars($userId))); // Redirect to the user homepage
        exit;
    } else {
        // Handle missing fields (optional)
        $errorMessage = "Please fill in all required fields.";
    }
}
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

  $postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';

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
      <img src="/image/logo1.png" alt="logo" class="logo">
      <div class="nav-container">
        <div class="hamburger" onclick="toggleMenu(this)">
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
        </div>
        <ul class="nav-link">
          <li><a href="/user-homepage">HOME</a></li>
          <li><a href="#ourcat">OUR CATS</a></li>
          <li><a href="#">ABOUT</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a><?php echo htmlspecialchars($name); ?></a></li>
        </ul> 
      </div>
    </nav>
  </header>

<section>
<div class="outer-container">
        <div class="form-container">
            <h1>Cat Adoption Application Form</h1>
            <h2>Applicant Details</h2>
            <form action="" method="POST"> 
              <input type="hidden" name="user_id" value="<?php echo isset($userId) ? htmlspecialchars($userId) : ''; ?>"> 
              <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">
            <div class="input-group">
                    <div class="input-box">
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($name); ?>">
                        <label>Name</label>
                    </div>

                    <div class="input-box">
                        <input type="text" name="occupation" required placeholder=" ">
                        <label>Occupation</label>
                    </div>
                </div>

                <div class="input-box">
                    <input type="text" name="address" required placeholder=" ">
                    <label>Address</label>
                </div>
            <div class="input-group">
                <div class="input-box">
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <label>Email Address</label>
                </div>

                <div class="input-box">
                    <input type="tel" name="phone" required value="<?php echo htmlspecialchars($phone); ?>">
                    <label>Phone Number</label>
                </div>
            </div>

                <div class="input-box">
                    <textarea name="message" required placeholder=" "></textarea>
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