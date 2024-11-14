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
  header('location:/loginto'); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Adoption</title>
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

  <!-- Header with Navigation -->
  <header>
    <?php include("header.php"); ?>
  </header>

  <!-- Adoption Application Form Section -->
  <section class="form" id="form">
    <div class="outer-container">
        <div class="form-container">
            <div class="text">
                <h1>Cat Adoption Application Form</h1>
            </div>

            <form action="form1.php" id="form1" method="POST">

                <!-- Caregiver Question -->
                <div class="question-container">
                    <p class="question-text">Who will be the pet's primary caregiver & will give financial support?:<span class="required">*</span></p>
                    <div class="answer-options">
                        <label><input type="radio" name="caregiver" value="me" required> Me</label>
                        <label><input type="radio" name="caregiver" value="parents" required> Parents/Guardian</label>
                        <label><input type="radio" name="caregiver" value="partner" required> Partner</label>
                        <label><input type="radio" name="caregiver" value="children" required> Kids/Children</label>
                        <label class="other-option">
                            <input type="radio" name="caregiver" value="other" onclick="showOtherInput(true)" required> Other...
                            <input type="text" class="other-input" placeholder="Specify" id="otherCaregiverInput" style="display: none;" aria-placeholder="Please Specify:" required>
                        </label>
                    </div>
                </div>

                <!-- Landlord Permission Question -->
                <div class="question-container">
                    <p class="question-text">Does your Landlord/Landlady allow pets?:<span class="required">*</span></p>
                    <div class="answer-options">
                        <label><input type="radio" name="landlord_permission" value="yes" required> Yes</label>
                        <label><input type="radio" name="landlord_permission" value="no" required> No</label>
                        <label><input type="radio" name="landlord_permission" value="na" required> N/A (if you or your family owns your current residence)</label>
                    </div>
                </div>

                <!-- Restrictions Question -->
                <div class="question-container">
                    <p class="question-text">Are there any restrictions from your landlord or subdivision/barangay regarding pets?:<span class="required">*</span></p>
                    <div class="answer-options">
                        <label><input type="radio" name="restrictions" value="number" required> Number of Pets</label>
                        <label><input type="radio" name="restrictions" value="size" required> Size or weight restrictions</label>
                        <label><input type="radio" name="restrictions" value="breed" required> Breed restrictions</label>
                        <label><input type="radio" name="restrictions" value="none" required> None</label>
                    </div>
                </div>

                <!-- Household Information -->
                <div class="survey-container">
                    <div class="questions-row">
                        <div class="input-question">
                            <label for="household-adults">
                                How many adults are in your household?<span class="required">*</span><br>
                                <span class="example">Example: 4</span>
                            </label>
                            <input type="text" id="household-adults" name="household_adults" placeholder="Your answer" required>
                        </div>

                        <div class="input-question">
                            <label for="household-children">
                                How many children are in your household?<span class="required">*</span><br>
                                <span class="example">Example: 4</span>
                            </label>
                            <input type="text" id="household-children" name="household_children" placeholder="Your answer" required>
                        </div>
                    </div>

                    <div class="questions-row">
                        <div class="input-question">
                            <label for="children-ages">
                                If there are children in your household, what are their ages?<span class="required">*</span><br>
                                <span class="example">Example: 3 and 7 Years old</span>
                            </label>
                            <input type="text" id="children-ages" name="children_ages" placeholder="Your answer" required>
                        </div>

                        <div class="input-question">
                            <p>Have your children been around animals before?<span class="required">*</span></p>
                            <div class="radio-options">
                                <div>
                                    <input type="radio" name="children_experience" value="yes" required>
                                    <label for="home-rent">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" name="children_experience" value="no" required>
                                    <label for="home-own">No</label>
                                </div>
                                <div>
                                    <input type="radio" name="children_experience" value="na" required>
                                    <label for="home-own">N/A (If you don't have children)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Allergies Question -->
                <div class="question-container">
                    <p class="question-text">Does anyone in your home have allergies to cats or have asthma?:<span class="required">*</span></p>
                    <div class="answer-options">
                        <label><input type="radio" name="allergies" value="yes" required> Yes</label>
                        <label><input type="radio" name="allergies" value="no" required> No</label>
                    </div>
                </div>

                <div class="input-question new-question">
                    <label for="allergy-details">
                        If you answered YES in the previous question, how many are allergic to cats in your family and do they take maintenance medicine for the allergies?<span class="required">*</span><br>
                    </label>
                    <input type="text" id="allergy-details" name="allergy_details" placeholder="Your answer" required>
                </div>

                <!-- Buttons inside the form-container and centered -->
                <div class="btn-container">
                    <button type="reset" class="btn-cancel">Back</button>
                    <button type="submit" class="btn-confirm" onclick="location.href='/inquiry-form3'">Next</button>
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

  <script src="/js/inquiry-form2.js"></script>


</body>

</html>
