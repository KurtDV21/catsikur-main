<?php

use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    // Check if the email already exists in the database
    $sqlCheckEmail = "SELECT * FROM user WHERE email = ?";
    $stmt = $dbConnection->prepare($sqlCheckEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, set the error message
        $response['error'] = "Email is already registered. Please use a different email.";
    } else {
        $role = 'user';
        // Register the user and get the activation hash
        $activation_hash = $userController->register($role, $_POST["name"], $_POST["email"], $_POST["password"]);
        
        // Retrieve the newly registered user's ID
        $newUserId = $dbConnection->insert_id;

        // Send activation email with the activation link
        $activation_link = "http://localhost/activate-account.php?token=" . $activation_hash; // Update your domain
        // Use PHPMailer or similar library to send the email here

        // Set success response
        $response['success'] = "Registration successful. Please check your email for activation link.";
        $response['redirect'] = "/profile2?user_id=" . $newUserId;
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
