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
    <link rel="stylesheet" href="css/form2.css">
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

        <form action="" id="form1">

        <div class="question-container">
            <p class="question-text">How many hours each day will your new cat be home alone?<span class="required">*</span></p>
            <span class="example">Example: 8-10 hours | Estimate the usual hours the cat will be all alone in his/her new home.</span>
            <div class="answer-options">
                <input type="text" class="other-input" placeholder="Specify" required>
              </label>  
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Where will your new cat sleep at night?:<span class="required">*</span></p>
            <div class="answer-options">
              <label><input type="radio" name="status" value="bedroom" required> In our bedroom</label>
              <label><input type="radio" name="status" value="cage"> In their own cage</label>
              <label><input type="radio" name="status" value="anywhere"> Anywhere around the house as they are free to roam</label>
              <label><input type="radio" name="status" value="outside"> Just outside our house but inside our lot</label>
              <label class="other-option">
              <input type="radio" name="status" value="other" onclick="showOtherInput(true)"> Other...
              <input type="text" class="other" placeholder="Specify" id="otherCaregiverInput" style="display: none;" aria-placeholder="Please Specify:">
              </label>
   
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Are you aware that a new environment is stressful for your new cat, and they may exhibit uncharacteristic behavior?<span class="required">*</span></p>
            <div class="answer-options">
              <label><input type="radio" name="stressAwareness" value="me" required> Yes</label>
              <label><input type="radio" name="stressAwareness" value="parents"> No</label>
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Are you willing to work through your new cat's issues, if any?<span class="required">*</span></p>
            <div class="answer-options">
              <label><input type="radio" name="workThroughIssues" value="me" required> Yes</label>
              <label><input type="radio" name="workThroughIssues" value="parents"> No</label>
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Are you willing to have your new cat spayed/neutered (kapon) to reduce unwanted kittens, overpopulation and for the over-all health & behavior of the cat?*
            Please declare the truth.<span class="required">*</span></p>
            <div class="answer-options">
              <label><input type="radio" name="spayNeuter" value="me" required> Yes</label>
              <label><input type="radio" name="spayNeuter" value="parents"> No</label>
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Do you understand that a cat can be a 20-year commitment, and will be very costly especially for vet care?<span class="required">*</span></p>
            <div class="answer-options">
                <input type="text" class="other-input" placeholder="Specify" required>
              </label>  
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Do you understand that you assume full responsibility for the welfare of this cat from the date of adoption?<span class="required">*</span></p>
            <div class="answer-options">
                <input type="text" class="other-input" placeholder="Specify" required>
              </label>  
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Do you swear that you have provided correct information for the questions above in this form?<span class="required">*</span></p>
            <div class="answer-options">
                <input type="text" class="other-input" placeholder="Specify" required>
              </label>  
            </div>
          </div>
          
          <!-- Buttons inside the form-container and centered -->
          <div class="btn-container">
            <button type="reset" class="btn-cancel">Back</button>
            <button type="submit" class="btn-confirm">Confirm</button>
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

  <script src="/js/inquiry-form4.js"></script>

</body>

</html>
