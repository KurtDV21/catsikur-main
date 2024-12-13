<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

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

// Fetch questions and answers from the database for page 4
$query = "SELECT * FROM questions WHERE page = 4"; // Adjust the page number if needed
$stmt = $dbConnection->prepare($query);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$answerQuery = "SELECT * FROM answers WHERE page = 4";
$answerStmt = $dbConnection->prepare($answerQuery);
$answerStmt->execute();
$answers = $answerStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Group answers by question_id
$groupedAnswers = [];
foreach ($answers as $answer) {
    $groupedAnswers[$answer['question_id']][] = $answer;
}

// Collect session data from all previous pages
$allFormData = array_merge(
    $_SESSION['dynamic_answers'] ?? [],
    $_SESSION['form_data_page2'] ?? [],
    $_SESSION['form_data_page3'] ?? [],
    $_SESSION['form_data_page4'] ?? []
);

// Output session data for debugging
echo '<pre>';
print_r($allFormData);
echo '</pre>';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data from page 4
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

    // Save data from this page into the session
    $_SESSION['form_data_page4'] = $formData;

    // Merge data from all pages
    $allFormData = array_merge($allFormData, $formData);

    // Here you would save the $allFormData to your database or send it as needed
    
    // Extract necessary data using the question ids
    $name = ($allFormData['question_1'] ?? '') . ' ' . ($allFormData['question_2'] ?? '');
    $email = $allFormData['question_3'] ?? '';
    $phone = $allFormData['question_4'] ?? '';
    $address = $allFormData['question_5'] ?? '';
    $age = $allFormData['question_6'] ?? '';
    $company_industry = $allFormData['question_7'] ?? '';
    $guardian = $allFormData['question_8'] ?? '';
    $facebook = $allFormData['question_9'] ?? '';
    $housing = $allFormData['question_10'] ?? '';
    $housing_role = $allFormData['question_11'] ?? '';
    $household_agreement = $allFormData['question_12'] ?? '';

    // Insert the dynamic answers data into the database
    $stmt = $dbConnection->prepare("INSERT INTO inquiries (user_id, post_id, name, age, company_industry, Guardian_details, Facebook, address, Housing, Housing_role, Household_agreement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iisssssssss",
        $userId,
        $postId,
        $name,
        $age,
        $company_industry,
        $guardian,
        $facebook,
        $address,
        $housing,
        $housing_role,
        $household_agreement
    );
    $stmt->execute();
    $stmt->close();


    // Extract necessary data for pet_adoption_inquiry using the question ids
    $caregiver = $allFormData['question_13'] ?? '';
    $landlordPermission = $allFormData['question_14'] ?? '';
    $restrictions = $allFormData['question_15'] ?? '';
    $householdAdults = $allFormData['question_16'] ?? '';
    $householdChildren = $allFormData['question_17'] ?? '';
    $childrenAges = $allFormData['question_18'] ?? '';
    $childrenExperience = $allFormData['question_19'] ?? '';
    $allergies = $allFormData['question_20'] ?? '';
    $allergyDetails = $allFormData['question_21'] ?? '';

    // Insert the pet_adoption_inquiry data into the database
    $stmt = $dbConnection->prepare("INSERT INTO pet_adoption_inquiry (user_id, post_id, caregiver, landlord_permission, restrictions, household_adults, household_children, children_ages, children_experience, allergies, allergy_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iisssssssss",
        $userId,
        $postId,
        $caregiver,
        $landlordPermission,
        $restrictions,
        $householdAdults,
        $householdChildren,
        $childrenAges,
        $childrenExperience,
        $allergies,
        $allergyDetails
    );
    $stmt->execute();
    $stmt->close();
    // Redirect to a confirmation or summary page
    header('Location: /user-homepage'); // Adjust this URL to your confirmation page
    exit;
}

 // Extract necessary data for adoption_inquiry_details using the question ids
 $pets = $allFormData['question_22'] ?? '';
 $spayedNeutered = $allFormData['question_23'] ?? '';
 $status = $allFormData['question_24'] ?? '';
 $adoptedBefore = $allFormData['question_25'] ?? '';
 $suppliesArray = $allFormData['question_26'] ?? [];
 $supplies = json_encode($suppliesArray);

 // Insert the adoption_inquiry_details data into the database
 $stmt = $dbConnection->prepare("INSERT INTO adoption_inquiry_details (user_id, post_id, pets, spayed_neutered, status, adopted_before, supplies) VALUES (?, ?, ?, ?, ?, ?, ?)");
 $stmt->bind_param(
     "iisssss",
     $userId,
     $postId,
     $pets,
     $spayedNeutered,
     $status,
     $adoptedBefore,
     $supplies
 );
 $stmt->execute();
 $stmt->close();

 // Extract necessary data for adoption_commitment_inquiry using the question ids
 $hoursAlone = $allFormData['question_27'] ?? '';
 $sleepLocation = $allFormData['question_28'] ?? '';
 $stressAwareness = $allFormData['question_29'] ?? '';
 $workThroughIssues = $allFormData['question_30'] ?? '';
 $spayNeuter = $allFormData['question_31'] ?? '';
 $commitment = $allFormData['question_32'] ?? '';
 $responsibility = $allFormData['question_33'] ?? '';
 $truthfulness = $allFormData['question_34'] ?? '';

 // Insert the adoption_commitment_inquiry data into the database
 $stmt = $dbConnection->prepare("INSERT INTO adoption_commitment_inquiry (user_id, post_id, hours_alone, sleep_location, stress_awareness, work_through_issues, spay_neuter, commitment, responsibility, truthfulness) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
 $stmt->bind_param(
     "iissssssss",  
     $userId,
     $postId,
     $hoursAlone,
     $sleepLocation,
     $stressAwareness,
     $workThroughIssues,
     $spayNeuter,
     $commitment,
     $responsibility,
     $truthfulness
 );
 $stmt->execute();
 $stmt->close();


// Load previously stored values if available
$formValues = $_SESSION['form_data_page4'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/form1.css">
    <link rel="stylesheet" href="/css/userdropdown.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Adoption - Page 4</title>
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
                <h1>Cat Adoption Application Form - Final Step</h1>
            </div>

            <form id="form4" method="POST">
                <?php foreach ($questions as $question): ?>
                    <div class="question-container">
                        <p class="question-text">
                            <?= htmlspecialchars($question['question']) ?>
                            <?= $question['is_optional'] ? '' : '<span class="required">*</span>' ?>
                        </p>

                        <?php if ($question['type'] == 'text'): ?>
                            <input type="text" name="question_<?= $question['id'] ?>" class="other-input" value="<?= htmlspecialchars($formValues["question_{$question['id']}"] ?? '') ?>" required>
                        
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
                    <button type="button" class="btn-cancel" onclick="location.href='/inquiry-form3?post_id=<?= htmlspecialchars($postId) ?>'">Back</button>
                    <button type="submit" class="btn-confirm">Submit</button>
                </div>
            </form>
        </div>
    </div>
  </section>

  <footer class="footer">
    Cats Free Adoption & Rescue Philippines
  </footer>

</body>
</html>
