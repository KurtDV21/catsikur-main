<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;
use App\Models\Inquiry;
use App\Controllers\InquiryController;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect(); // Use this connection for queries
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $user = $userModel->findUserById($userId);
    $name = $user['name'] ?? '';
    $phone = $user['Phone_number'] ?? '';
    $email = $user['email'] ?? '';
} else {
    header('location:/loginto');
    exit;
}

$postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve data from sessions
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $address = $_SESSION['address'];
    $age = $_SESSION['age'];
    $guardian = $_SESSION['guardian'];
    $lastSelectedCompany = $_SESSION['lastSelectedCompany'];
    $facebook = $_SESSION['facebook'];
    $housing = $_SESSION['housing'];
    $has_pets = $_SESSION['has_pets'];
    $outdoor_space = $_SESSION['outdoor_space'];

    $caregiver = $_SESSION['caregiver'] ?? '';
    $landlordPermission = $_SESSION['landlord_permission'] ?? '';
    $restrictions = $_SESSION['restrictions'] ?? '';
    $householdAdults = $_SESSION['household_adults'] ?? '';
    $householdChildren = $_SESSION['household_children'] ?? '';
    $childrenAges = $_SESSION['children_ages'] ?? '';
    $childrenExperience = $_SESSION['children_experience'] ?? '';
    $allergies = $_SESSION['allergies'] ?? '';
    $allergyDetails = $_SESSION['allergy_details'] ?? '';

    $pets = $_SESSION['pets'] ?? '';
    $spayedNeutered = $_SESSION['spayed_neutered'] ?? '';
    $status = $_SESSION['status'] ?? '';
    $adoptedBefore = $_SESSION['adopted_before'] ?? '';
    $supplies = json_encode($_SESSION['supplies'] ?? []);

    $_SESSION['hours_alone'] = $_POST['hours_alone'] ?? '';
    $_SESSION['sleep_location'] = $_POST['sleep_location'] ?? '';
    $_SESSION['stress_awareness'] = $_POST['stress_awareness'] ?? '';
    $_SESSION['work_through_issues'] = $_POST['work_through_issues'] ?? '';
    $_SESSION['spay_neuter'] = $_POST['spay_neuter'] ?? '';
    $_SESSION['commitment'] = $_POST['commitment'] ?? '';
    $_SESSION['responsibility'] = $_POST['responsibility'] ?? '';
    $_SESSION['truthfulness'] = $_POST['truthfulness'] ?? '';

    // Retrieving stored data from $_SESSION
    $hoursAlone = $_SESSION['hours_alone'] ?? '';
    $sleepLocation = $_SESSION['sleep_location'] ?? '';
    $stressAwareness = $_SESSION['stress_awareness'] ?? '';
    $workThroughIssues = $_SESSION['work_through_issues'] ?? '';
    $spayNeuter = $_SESSION['spay_neuter'] ?? '';
    $commitment = $_SESSION['commitment'] ?? '';
    $responsibility = $_SESSION['responsibility'] ?? '';
    $truthfulness = $_SESSION['truthfulness'] ?? '';

    try {
        $dbConnection->begin_transaction();

        $stmt = $dbConnection->prepare("INSERT INTO inquiries (user_id, post_id, name, age, company_industry, Guardian_details, Facebook, address, Housing, Housing_role, Household_agreement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssssssss", $userId, $postId, $name, $age, $lastSelectedCompany, $guardian, $facebook, $address, $housing, $has_pets, $outdoor_space);
        $stmt->execute();
        $stmt->close(); 

        $stmt = $dbConnection->prepare("INSERT INTO pet_adoption_inquiry (user_id, post_id, caregiver, landlord_permission, restrictions, household_adults, household_children, children_ages, children_experience, allergies, allergy_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssssssss", $userId, $postId, $caregiver, $landlordPermission, $restrictions, $householdAdults, $householdChildren, $childrenAges, $childrenExperience, $allergies, $allergyDetails);
        $stmt->execute();
        $stmt->close();

        $stmt = $dbConnection->prepare("INSERT INTO adoption_inquiry_details (user_id, post_id, pets, spayed_neutered, status, adopted_before, supplies) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $userId, $postId, $pets, $spayedNeutered, $status, $adoptedBefore, $supplies);
        $stmt->execute();
        $stmt->close();

        $stmt = $dbConnection->prepare("INSERT INTO adoption_commitment_inquiry (user_id, post_id, hours_alone, sleep_location, stress_awareness, work_through_issues, spay_neuter, commitment, responsibility, truthfulness) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssssss", $userId, $postId, $hoursAlone, $sleepLocation, $stressAwareness, $workThroughIssues, $spayNeuter, $commitment, $responsibility, $truthfulness);
        $stmt->execute();
        $stmt->close();

        $dbConnection->commit();

        session_unset();

        $_SESSION['user_id'] = $userId;

        header('Location: /user-homepage');
        exit;
    } catch (Exception $e) {
        $dbConnection->rollback();
        throw $e;
    }

    $dbConnection->close();
}

$hoursAlone = $_SESSION['hours_alone'] ?? '';
$sleepLocation = $_SESSION['sleep_location'] ?? '';
$stressAwareness = $_SESSION['stress_awareness'] ?? '';
$workThroughIssues = $_SESSION['work_through_issues'] ?? '';
$spayNeuter = $_SESSION['spay_neuter'] ?? '';
$commitment = $_SESSION['commitment'] ?? '';
$responsibility = $_SESSION['responsibility'] ?? '';
$truthfulness = $_SESSION['truthfulness'] ?? '';
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

  <section class="form" id="form">
    <div class="outer-container">
      <div class="form-container">
        <div class="text">
          <h1>Cat Adoption Application Form</h1>
        </div>

        <form id="form1"  method="POST">

        <div class="question-container">
            <p class="question-text">How many hours each day will your new cat be home alone?<span class="required">*</span></p>
            <span class="example">Example: 8-10 hours | Estimat   the usual hours the cat will be all alone in his/her new home.</span>
            <div class="answer-options">
            <input type="text" name="hours_alone" class="other-input" placeholder="Specify" value="<?= htmlspecialchars($hoursAlone) ?>" required>
            </label>  
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Where will your new cat sleep at night?:<span class="required">*</span></p>
            <div class="answer-options">
              <label><input type="radio" name="sleep_location" value="bedroom" <?= $sleepLocation === 'bedroom' ? 'checked' : '' ?> required> In our bedroom</label>
              <label><input type="radio" name="sleep_location" value="cage" <?= $sleepLocation === 'cage' ? 'checked' : '' ?>> In their own cage</label>
              <label><input type="radio" name="sleep_location" value="anywhere" <?= $sleepLocation === 'anywhere' ? 'checked' : '' ?>> Anywhere around the house as they are free to roam</label>
              <label><input type="radio" name="sleep_location" value="outside" <?= $sleepLocation === 'outside' ? 'checked' : '' ?>> Just outside our house but inside our lot</label>
              <label class="other-option">
                  <input type="radio" name="sleep_location" value="other" <?= $sleepLocation === 'other' ? 'checked' : '' ?> onclick="showOtherInput(true)"> Other...
                  <input type="text" class="other" placeholder="Specify" id="otherCaregiverInput" style="display: none;" value="<?= htmlspecialchars($sleepLocation === 'other' ? $sleepLocation : '') ?>" aria-placeholder="Please Specify:">
              </label>
          </div>

          </div>

          <div class="question-container">
            <p class="question-text">Are you aware that a new environment is stressful for your new cat, and they may exhibit uncharacteristic behavior?<span class="required">*</span></p>
            <div class="answer-options">
            <label><input type="radio" name="stress_awareness" value="yes" <?= $stressAwareness === 'yes' ? 'checked' : '' ?> > Yes</label>
            <label><input type="radio" name="stress_awareness" value="no" <?= $stressAwareness === 'no' ? 'checked' : '' ?>> No</label>
        </div>

          </div>

          <div class="question-container">
            <p class="question-text">Are you willing to work through your new cat's issues, if any?<span class="required">*</span></p>
            <div class="answer-options">
            <label><input type="radio" name="work_through_issues" value="yes" <?= $workThroughIssues === 'yes' ? 'checked' : '' ?> > Yes</label>
            <label><input type="radio" name="work_through_issues" value="no" <?= $workThroughIssues === 'no' ? 'checked' : '' ?>> No</label>
        </div>
          </div>

          <div class="question-container">
            <p class="question-text">Are you willing to have your new cat spayed/neutered (kapon) to reduce unwanted kittens, overpopulation and for the over-all health & behavior of the cat?*
            Please declare the truth.<span class="required">*</span></p>
            <div class="answer-options">
            <label><input type="radio" name="spay_neuter" value="yes" <?= $spayNeuter === 'yes' ? 'checked' : '' ?> > Yes</label>
            <label><input type="radio" name="spay_neuter" value="no" <?= $spayNeuter === 'no' ? 'checked' : '' ?>> No</label>
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Do you understand that a cat can be a 20-year commitment, and will be very costly especially for vet care?<span class="required">*</span></p>
            <div class="answer-options">
            <input type="text" name="commitment" class="other-input" placeholder="Specify" value="<?= htmlspecialchars($commitment) ?>" required>
            </label>  
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Do you understand that you assume full responsibility for the welfare of this cat from the date of adoption?<span class="required">*</span></p>
            <div class="answer-options">
            <input type="text" name="responsibility" class="other-input" placeholder="Specify" value="<?= htmlspecialchars($responsibility) ?>" required>
            </label>  
            </div>
          </div>

          <div class="question-container">
            <p class="question-text">Do you swear that you have provided correct information for the questions above in this form?<span class="required">*</span></p>
            <div class="answer-options">
            <input type="text" name="truthfulness" class="other-input" placeholder="Specify" value="<?= htmlspecialchars($truthfulness) ?>" required>
            </label>  
            </div>
          </div>
          
          <div class="btn-container">
            <button type="reset" class="btn-cancel" onclick="location.href='/inquiry-form3?post_id=<?php echo htmlspecialchars($postId); ?>'">Back</button>
            <button type="submit" class="btn-confirm">Confirm</button>
          </div>

        </form>
      </div>
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

  <footer class="footer">
    Cats Free Adoption & Rescue Philippines
  </footer>

  <script src="/js/inquiry-form4.js"></script>

</body>

</html>
