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
    $name = $user['name'] ?? '';
    $phone = $user['Phone_number'] ?? '';
    $email = $user['email'] ?? '';
} else {
    header('Location: /loginto');
    exit;
}

// Get the post ID from the URL
$postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
if (!$postId) {
    header('Location: /error-page');
    exit;
}

// Fetch questions and answers from the database
$query = "SELECT * FROM questions WHERE page = 3"; // Adjust the page as needed
$stmt = $dbConnection->prepare($query);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$answerQuery = "SELECT * FROM answers WHERE page = 3";
$answerStmt = $dbConnection->prepare($answerQuery);
$answerStmt->execute();
$answers = $answerStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Group answers by question_id
$groupedAnswers = [];
foreach ($answers as $answer) {
    $groupedAnswers[$answer['question_id']][] = $answer;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store dynamic form inputs in session
    $formData = [];
    foreach ($questions as $question) {
        $questionId = $question['id'];
        $inputName = "question_{$questionId}";
        if (isset($_POST[$inputName])) {
            if (is_array($_POST[$inputName])) {
                $formData[$inputName] = array_map('htmlspecialchars', array_map('trim', $_POST[$inputName]));
            } else {
                $formData[$inputName] = htmlspecialchars(trim($_POST[$inputName]));
            }
        } else {
            // Ensure checkboxes are stored as empty arrays if not checked
            if ($question['type'] == 'checkbox') {
                $formData[$inputName] = [];
            }
        }
    }

    // Store form data in a separate session key for this page
    $_SESSION['form_data_page3'] = array_merge($_SESSION['form_data_page3'] ?? [], $formData);

    // Form validation
    $errors = [];
    foreach ($questions as $question) {
        if (!$question['is_optional'] && empty($_POST["question_{$question['id']}"])) {
            $errors[] = "Please answer question: " . htmlspecialchars($question['question']);
        }
    }

    // If validation fails, redirect back to the form with error messages
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: /inquiry-form3?post_id=' . $postId);
        exit;
    }

    // If validation passes, redirect to the next step (if any)
    header('Location: /inquiry-form4?post_id=' . $postId);
    exit;
}

// Load previously stored values if available
$formValues = $_SESSION['form_data_page3'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/form1.css">
    <link rel="stylesheet" href="/css/userdropdown.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Adoption</title>
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

            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="form1" method="POST">
                <?php foreach ($questions as $question): ?>
                    <div class="question-container">
                        <p class="question-text">
                            <?= htmlspecialchars($question['question']) ?>
                            <?= $question['is_optional'] ? '' : '<span class="required">*</span>' ?>
                        </p>

                        <?php if ($question['type'] == 'text'): ?>
                            <?php if (stripos($question['question'], 'list any pets you own') !== false): ?>
                                <input type="text" name="question_<?= $question['id'] ?>" class="other-input" placeholder="Format: NAME-SPECIES-BREED-AGE. Type N/A if you don't have any pets in the past five (5) years" value="<?= htmlspecialchars($formValues["question_{$question['id']}"] ?? '') ?>" required>
                            <?php elseif (stripos($question['question'], 'kapon') !== false): ?>
                                <input type="text" name="question_<?= $question['id'] ?>" class="other-input-kapon" placeholder="Type N/A if you don't have any pets" value="<?= htmlspecialchars($formValues["question_{$question['id']}"] ?? '') ?>" required>
                            <?php else: ?>
                                <input type="text" name="question_<?= $question['id'] ?>" class="other-input" placeholder="Your answer" value="<?= htmlspecialchars($formValues["question_{$question['id']}"] ?? '') ?>" required>
                            <?php endif; ?>
                        
                        <?php elseif ($question['type'] == 'radio'): ?>
                            <div class="answer-options">
                                <?php foreach ($groupedAnswers[$question['id']] as $answer): ?>
                                    <label>
                                        <input type="radio" name="question_<?= $question['id'] ?>" value="<?= htmlspecialchars($answer['answer_text'], ENT_QUOTES) ?>" <?= isset($formValues["question_{$question['id']}"]) && $formValues["question_{$question['id']}"] === $answer['answer_text'] ? 'checked' : '' ?> required>
                                        <?= htmlspecialchars($answer['answer_text']) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        
                        <?php elseif ($question['type'] == 'checkbox'): ?>
                            <div class="answer-options">
                                <?php foreach ($groupedAnswers[$question['id']] as $answer): ?>
                                    <label>
                                        <input type="checkbox" name="question_<?= $question['id'] ?>[]" value="<?= htmlspecialchars($answer['answer_text'], ENT_QUOTES) ?>" <?= isset($formValues["question_{$question['id']}"]) && in_array($answer['answer_text'], $formValues["question_{$question['id']}"]) ? 'checked' : '' ?> class="option-checkbox">
                                        <?= htmlspecialchars($answer['answer_text']) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="btn-container">
                    <button type="button" class="btn-cancel" onclick="location.href='/inquiry-form2?post_id=<?= htmlspecialchars($postId) ?>'">Back</button>
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

  <script>
document.addEventListener("DOMContentLoaded", function() {
    // Select All checkboxes functionality
    document.querySelectorAll('input[type="checkbox"][value="Select All"]').forEach(function(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const container = this.closest('.answer-options');
            const checkboxes = container.querySelectorAll('input[type="checkbox"]:not([value="Select All"]):not([value="None as of the moment"])');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });

    // Ensure all other checkboxes maintain their checked state from the session
    <?php if (!empty($formValues)): ?>
        <?php foreach ($formValues as $key => $value): ?>
            <?php if (is_array($value)): ?>
                <?php foreach ($value as $item): ?>
                    document.querySelectorAll('input[name="<?= $key ?>[]"][value="<?= htmlspecialchars($item, ENT_QUOTES) ?>"]').forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                    console.log('Checked: ', '<?= $key ?>', '<?= htmlspecialchars($item, ENT_QUOTES) ?>');
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
});
</script>

  <script src="/js/inquiry-form3.js"></script>

</body>

</html>
