<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST["password"] === $_POST["confirm-password"]) {
        // Check if the email already exists
        if ($userController->emailExists($_POST["email"])) {
            // Redirect back to the registration form with an error message
            $error_message = urlencode("Email is already used");
            header("Location: /register-form?error=" . $error_message);
            exit;
        }

        $role = 'user';
        // Register the user and get the activation hash
        $activation_result = $userController->register($role, $_POST["name"], $_POST["email"], $_POST["password"]);

        if ($activation_result['success']) {
            // Retrieve the newly registered user's ID
            $newUserId = $dbConnection->insert_id;

            // Send activation email with the activation link
            $activation_link = "http://localhost/activate-account.php?token=" . $activation_hash; // Update your domain
            // Use PHPMailer or similar library to send the email here

            // Redirect to profile creation page, passing user ID
            header("Location: /profile2?user_id=" . $newUserId);
            exit;
        } else {
            // Handle registration failure
            $error_message = urlencode("Registration failed. Please try again.");
            header("Location: /register-form?error=" . $error_message);
            exit;
        }
    } else {
        // Redirect back to the registration form with an error message
        $error_message = urlencode("Passwords do not match");
        header("Location: /register-form?error=" . $error_message);
        exit;
    }
}
?>
