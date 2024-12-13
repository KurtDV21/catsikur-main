<?php
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;
use App\Models\Inquiry;
use App\Controllers\InquiryController;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

// Database connection
$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];  
    $user = $userModel->findUserById($userId); 
    $firstname = $user['first_name'] ?? ''; 
    $lastname = $user['last_name'] ?? ''; 
    $phone = $user['Phone_number'] ?? '';
    $email = $user['email'] ?? '';
} else {
    header('location:/loginto');
    exit;
}

// Get the post ID from the URL
$postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : ''; // Sanitize the post_id

// Fetch questions and answers from the database
$query = "SELECT * FROM questions WHERE page = 1";
$stmt = $dbConnection->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

$answerQuery = "SELECT * FROM answers";
$answerStmt = $dbConnection->prepare($answerQuery);
$answerStmt->execute();
$answerResult = $answerStmt->get_result();
$answers = $answerResult->fetch_all(MYSQLI_ASSOC);

// Group answers by question_id
$groupedAnswers = [];
foreach ($answers as $answer) {
    $groupedAnswers[$answer['question_id']][] = $answer;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Store dynamic form inputs in session
    $formData = [];
    $dynamicAnswers = [];
    foreach ($questions as $question) {
        $questionId = $question['id']; // Use the correct column name for question ID
        $inputName = "question_{$questionId}";
        if (isset($_POST[$inputName])) {
            $formData[$inputName] = htmlspecialchars(trim($_POST[$inputName]));
        }
    }

    // Store form data in session
    $_SESSION = array_merge($_SESSION, $formData); // Merge form data with session

    // Store dynamic answers in session
    $_SESSION['dynamic_answers'] = $formData;

    // Form validation
    $errors = [];

    // Dynamic fields validation
    foreach ($questions as $question) {
        $questionId = $question['id'];
        $inputName = "question_{$questionId}";
        if (!$question['is_optional'] && empty($formData[$inputName])) {
            $errors[] = "The question '{$question['question']}' is required.";
        }
    }

    // If validation fails, redirect back to the form with error messages
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header('Location: /inquiry-form');
        exit;
    }

    // If validation passes, redirect to the next step
    header('Location: /inquiry-form2?post_id=' . $postId);
    exit;
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/adoptform.css">
    <link rel="stylesheet" href="/css/userdropdown.css"> <!-- Add your CSS file link if needed -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header>
    <?php include("header.php"); ?>
  </header>


  <section class="form1" id="form1">
    <div class="outer-container">
        <div class="form-container">
            <div class="info">
                <h1>Cat Adoption Application Form</h1>
                <h2>Applicant Information</h2>
                <p>Rest assured that all information you will provide is strictly confidential and for adoption screening purposes only. This section will ask information on the prospective adopter. Kindly fill out everything with the correct information. <br><br>PROVIDE THE EMAIL ADDRESS OF THE CAT OWNER/RESCUER BELOW!</p>
            </div>
            <?php if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); // Clear errors after displaying them ?>
            <?php endif; ?>
            <form id="form" method="POST">
                <input type="hidden" name="user_id" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"> 
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">

                <div class="questions-container">
                    <?php foreach ($questions as $question): ?>
                    <div class="question <?php echo stripos($question['question'], 'guardian') !== false ? 'guardian' : ''; ?>" id="question_<?php echo $question['id']; ?>">
                        <p><?php echo htmlspecialchars($question['question']); ?><?php echo $question['is_optional'] ? '' : '<span class="required">*</span>'; ?></p>
                        <?php if ($question['type'] == 'radio' || $question['type'] == 'checkbox'): ?>
                        <div class="options">
                            <?php foreach ($groupedAnswers[$question['id']] as $answer): ?>
                            <div>
                                <input type="<?php echo $question['type']; ?>" name="question_<?php echo $question['id']; ?>" value="<?php echo htmlspecialchars($answer['answer_text']); ?>" id="answer_<?php echo $answer['id']; ?>"
                                <?php if(isset($_SESSION["question_{$question['id']}"]) && $_SESSION["question_{$question['id']}"] == $answer['answer_text']) echo 'checked'; ?>>
                                <span><?php echo htmlspecialchars($answer['answer_text']); ?></span>
                            </div>
                            <?php endforeach; ?>    
                        </div>
                        <?php elseif ($question['type'] == 'number' && stripos($question['question'], 'age') !== false): ?>
                        <div class="input-box-age">
                            <input type="number" name="question_<?php echo $question['id']; ?>" id="ageInput" class="input-short" value="<?php echo isset($_SESSION['dynamic_answers']["question_{$question['id']}"]) ? htmlspecialchars($_SESSION['dynamic_answers']["question_{$question['id']}"]) : ''; ?>" required="<?php echo !$question['is_optional']; ?>" min="0" max="120" data-type="number">
                        </div>
                        <?php elseif ($question['type'] == 'text' && stripos($question['question'], 'phone') !== false): ?>
                        <div class="input-box">
                            <input type="text" name="question_<?php echo $question['id']; ?>" value="<?php echo isset($_SESSION['dynamic_answers']["question_{$question['id']}"]) ? htmlspecialchars($_SESSION['dynamic_answers']["question_{$question['id']}"]) : '+63'; ?>" required="<?php echo !$question['is_optional']; ?>" pattern="^\+63[0-9]{10}$" title="Phone number must start with +63 and contain 10 additional digits." oninput="validatePhoneNumber(this)">
                        </div>
                        <?php elseif ($question['type'] == 'text'): ?>
                        <div class="input-box">
                            <input type="text" name="question_<?php echo $question['id']; ?>" value="<?php echo isset($_SESSION['dynamic_answers']["question_{$question['id']}"]) ? htmlspecialchars($_SESSION['dynamic_answers']["question_{$question['id']}"]) : ''; ?>" required="<?php echo !$question['is_optional']; ?>">
                        </div>
                        <?php elseif ($question['type'] == 'dropdown'): ?>
                        <div class="dropdown">
                            <select name="question_<?php echo $question['id']; ?>" required="<?php echo !$question['is_optional']; ?>">
                                <?php foreach ($groupedAnswers[$question['id']] as $answer): ?>
                                <option value="<?php echo htmlspecialchars($answer['answer_text']); ?>" <?php if(isset($_SESSION['dynamic_answers']["question_{$question['id']}"]) && $_SESSION['dynamic_answers']["question_{$question['id']}"] == $answer['answer_text']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($answer['answer_text']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn-cancel" onclick="location.href='/cat-details?post_id=<?php echo htmlspecialchars($postId); ?>'">Back</button>
                    <button type="submit" class="btn-confirm">Next</button>
                </div>
            </form>
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
        <h3>ABOUT COMPANY</h3>
            <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente<br> doloremque et dolores doloribus est animi maiores. Ut fugiat <br> molestiae nam quia earum qui aliquid aliquid ab corrupti officiis. Et<br> temporibus quia 33 incidunt adipisci ea deleniti vero 33<br> reprehenderit repellat.</p>
            
            
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
  

  <script>
function validatePhoneNumber(input) {
    // Ensure input value starts with '+63'
    if (!input.value.startsWith('+63')) {
        input.value = '+63';
    }
    
    // Remove all non-numeric characters except '+'
    input.value = '+63' + input.value.slice(3).replace(/\D/g, '').slice(0, 10);
}

function toggleGuardianFields() {
    const ageInput = document.getElementById('ageInput');
    const guardianFields = document.querySelectorAll('.question.guardian');

    function toggle() {
        const age = parseInt(ageInput.value);
        guardianFields.forEach(function(field) {
            if (age <= 18) {
                field.style.display = 'block';
                field.querySelector('input').setAttribute('required', 'required');
            } else {
                field.style.display = 'none';
                field.querySelector('input').removeAttribute('required');
            }
        });
    }

    ageInput.addEventListener('input', toggle);
    toggle();  // Call it initially in case there is a pre-filled age
}

document.addEventListener("DOMContentLoaded", function() {
    toggleGuardianFields();

    document.querySelectorAll('input[data-type="number"]').forEach(function(input) {
        input.addEventListener('input', function(e) {
            // Remove all non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
});

    function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");
}

// Toggle the main dropdown
function toggleDropdown() {
    const dropdown = document.getElementById("dropdownContent");
    dropdown.classList.toggle("show");
}

// Close the dropdowns if clicked outside
function closeDropdowns(event) {
    if (!event.target.closest('.user-dropdown') && !event.target.closest('.dropdown')) {
        closeUserDropdown();
        closeMainDropdown();
    }
}

function closeUserDropdown() {
    const userDropdown = document.getElementById("userDropdownContent");
    if (userDropdown.classList.contains("show")) {
        userDropdown.classList.remove("show");
    }
}

function closeMainDropdown() {
    const mainDropdown = document.getElementById("dropdownContent");
    if (mainDropdown.classList.contains("show")) {
        mainDropdown.classList.remove("show");
    }
}

window.onclick = closeDropdowns;




document.getElementById('form').addEventListener('submit', function (event) {
    let isValid = true;
    const errorMessages = [];

    const firstName = document.querySelector('input[name="name"]').value.trim();
    const lastName = document.querySelector('input[name="lastname"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();
    const address = document.querySelector('input[name="address"]').value.trim();
    const age = document.querySelector('input[name="age"]').value.trim();
    const guardian = document.querySelector('input[name="guardian"]').value.trim();
    const housing = document.querySelector('input[name="housing"]:checked');
    const hasPets = document.querySelector('input[name="has_pets"]:checked');
    const outdoorSpace = document.querySelector('input[name="outdoor_space"]:checked');

    if (!firstName) {
        isValid = false;
        errorMessages.push("First name is required.");
    }

    if (!lastName) {
        isValid = false;
        errorMessages.push("Last name is required.");
    }

    if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
        isValid = false;
        errorMessages.push("Valid email is required.");
    }


    if (!address) {
        isValid = false;
        errorMessages.push("Address is required.");
    }

    if (!age) {
        isValid = false;
        errorMessages.push("Age is required.");
    }

    if (age < 18 && !guardian) {
        isValid = false;
        errorMessages.push("Guardian details are required for applicants under 18.");
    }

    if (!housing) {
        isValid = false;
        errorMessages.push("Please select a housing type.");
    }

    if (!hasPets) {
        isValid = false;
        errorMessages.push("Please indicate your housing status.");
    }

    if (!outdoorSpace) {
        isValid = false;
        errorMessages.push("Please indicate your household agreement.");
    }

    if (!isValid) {
        event.preventDefault();
        alert(errorMessages.join('\n'));
    }
});

</script>


<script src="/js/inquiry-form.js"></script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>