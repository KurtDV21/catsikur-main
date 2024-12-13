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
$query = "SELECT * FROM questions WHERE page = 2"; // Adjust the page as needed
$stmt = $dbConnection->prepare($query);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$answerQuery = "SELECT * FROM answers WHERE page = 2";
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
            $formData[$inputName] = htmlspecialchars(trim($_POST[$inputName]));
        }
    }

    // Store form data in a separate session key for this page
    $_SESSION['form_data_page2'] = array_merge($_SESSION['form_data_page2'] ?? [], $formData);

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
        header('Location: /inquiry-form2?post_id=' . $postId);
        exit;
    }

    // If validation passes, redirect to the next step
    header('Location: /inquiry-form3?post_id=' . $postId);
    exit;
}

// Load previously stored values if available
$formValues = $_SESSION['form_data_page2'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Adoption</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="/css/userdropdown.css">
</head>
<body>

<header>
    <?php include("header.php"); ?>
</header>

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

                        <?php if (isset($groupedAnswers[$question['id']])): ?>
                            <div class="answer-options">
                                <?php foreach ($groupedAnswers[$question['id']] as $answer): ?>
                                    <label>
                                        <input type="radio" 
                                               name="question_<?= $question['id'] ?>" 
                                               value="<?= htmlspecialchars($answer['answer_text']) ?>"
                                               <?= isset($formValues["question_{$question['id']}"]) && $formValues["question_{$question['id']}"] === $answer['answer_text'] ? 'checked' : '' ?>>
                                        <?= htmlspecialchars($answer['answer_text']) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($question['type'] == 'number'): ?>
                            <input type="number" 
                                   name="question_<?= $question['id'] ?>" 
                                   placeholder="Ex. 4" 
                                   value="<?= htmlspecialchars($formValues["question_{$question['id']}"] ?? '') ?>" 
                                   min="0" max="999" data-type="number">
                        <?php else: ?>
                            <input type="text" 
                                   name="question_<?= $question['id'] ?>" 
                                   value="<?= htmlspecialchars($formValues["question_{$question['id']}"] ?? '') ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="btn-container">
                    <button type="button" class="btn-cancel" onclick="location.href='/inquiry-form?post_id=<?= htmlspecialchars($postId) ?>'">Back</button>
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
            <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente doloremque et dolores doloribus est animi maiores.</p>

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

<script>
// JavaScript to handle dynamic field visibility and validation
function validateNumberInput(input) {
    // Remove all non-numeric characters except for numbers
    input.value = input.value.replace(/[^0-9]/g, '');
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('input[data-type="number"]').forEach(function(input) {
        input.addEventListener('input', function(e) {
            validateNumberInput(this);
        });
    });
});
</script>

</body>
</html>
