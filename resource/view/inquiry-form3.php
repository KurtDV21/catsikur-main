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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/form1.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Adoption</title>
</head>

<body>

  <!-- Header with Navigation -->
  <header>
  <nav class="navbar">
    <img src="/image/logo1.png" alt="logo" class="logo">

      <div class="hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>

      <ul class="nav-link">
        <li><a href="/user-homepage">HOME</a></li>
        <li><a href="#ourcat">OUR CATS</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#faq">FAQs</a></li>
        <li>
          <div class="user-dropdown">
            <button class="user-dropdown-button" onclick="toggleUserDropdown()">
              <?php echo htmlspecialchars($name); ?>
            </button>
            <div class="user-dropdown-content" id="userDropdownContent">
              <a href="/logout">Logout</a>
            </div>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <!-- Adoption Application Form Section -->
  <section class="form" id="form">
    <div class="outer-container">
      <div class="form-container">
        <div class="text">
          <h1>Cat Adoption Application Form</h1>
        </div>

        <form id="form1" action="" method="post">

        <div class="question-container">
  <p class="question-text">List any pets you own or have owned in the past five (5) years, their breeds, and their ages:<span class="required">*</span></p>
  <span class="example">Format: NAME-SPECIES-BREED-AGE (Example: Lala-Cat-Puspin-2yrs old). Separate by "enter".Type N/A if you don't have any pets in the past five (5) years.</span>
  <div class="answer-options">
    <input type="text" class="other-input" placeholder="Specify" required>
  </div>
</div>

<div class="question-container">
  <p class="question-text">How many of these pets are spayed/neutered?:<span class="required">*</span></p>
  <span class="example">Type N/A if you don't have any pets.</span>
  <div class="answer-options">
    <input type="text" class="other-input" placeholder="Specify" required>
  </div>
</div>

<div class="question-container">
  <p class="question-text">Where are the animals (your pets) you mentioned ABOVE now?<span class="required">*</span></p>
  <div class="answer-options">
    <label><input type="radio" name="status" value="alive" required> Alive and with me/us</label>
    <label><input type="radio" name="status" value="dead"> Dead/Passed away</label>
    <label><input type="radio" name="status" value="given"> Given to relative</label>
    <label><input type="radio" name="status" value="adopt"> Given to adoption or to other people</label>
    <label><input type="radio" name="status" value="none"> N/A. Did not own pet/s before</label>
  </div>
</div>

<div class="question-container">
  <p class="question-text">Have you adopted from a rescuer or animal welfare group before?<span class="required">*</span></p>
  <div class="answer-options">
    <label><input type="radio" name="restrictions" value="yes" required> Yes</label>
    <label><input type="radio" name="restrictions" value="no"> No</label>
  </div>
</div>

<div class="question-container">
  <p class="question-text">Do you have supplies and goods that you can provide for your new cat right now?* Check all that you have already bought for your new cat. No worries if you have only selected a FEW OR NONE AT ALL, we are NOT REQUIRING you to have all of the following choices below.<span class="required">*</span></p>
  <div class="check-options">
    <label><input type="checkbox" name="supply[]" value="catfood" required> Cat Food (wet and/or dry)</label>
    <label><input type="checkbox" name="supply[]" value="milk"> Kitten Milk</label>
    <label><input type="checkbox" name="supply[]" value="food"> Food bowl and Water bottle/fountain</label>
    <label><input type="checkbox" name="supply[]" value="litter"> Litter box and its accessories (scooper/litter mat)</label>
    <label><input type="checkbox" name="supply[]" value="bedding"> Bedding and blankets</label>
    <label><input type="checkbox" name="supply[]" value="flee"> Flea & worming medications</label>
    <label><input type="checkbox" name="supply[]" value="cage"> Cage or cat condo/tree</label>
    <label><input type="checkbox" name="supply[]" value="toys"> Toys and Scratching post</label>
    <label><input type="checkbox" name="supply[]" value="grooming"> Grooming supplies (comb, brush, nail clipper, cat shampoo/soap, towel, toothbrush)</label>
    <label><input type="checkbox" name="supply[]" value="crate"> Crate or Pet Travel Bag</label>
    <label><input type="checkbox" name="supply[]" value="vita"> Vitamins & Supplements</label>
    <label><input type="checkbox" name="supply[]" value="none"> None as of the moment</label>
  </div>
</div>
          
          <!-- Buttons inside the form-container and centered -->
          <div class="btn-container">
            <button type="reset" class="btn-cancel">Back</button>
            <button type="submit" class="btn-confirm">Next</button>
          </div>

        </form>
      </div>
    </div>
  </section>

  <!-- About Section -->
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
        <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente doloremque et dolores doloribus est animi maiores. Ut fugiat molestiae nam quia earum qui aliquid aliquid ab corrupti officiis. Et temporibus quia 33 incidunt adipisci ea deleniti vero 33 reprehenderit repellat.</p>

        <a href="https://www.facebook.com/groups/1591906714301364" target="_blank">
          <img src="facebook.png" alt="Facebook" class="fb-icon">
        </a>
        <a href="https://www.messenger.com" target="_blank">
          <img src="messenger.png" alt="Messenger" class="mess-icon">
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    Cats Free Adoption & Rescue Philippines
  </footer>

  <script src="/js/inquiry-form3.js"></script>

</body>

</html>
