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
    $email = $_POST["email"];
    $role = 'user';

    // Check if email is already registered
    if ($userModel->isEmailRegistered($email)) {
        header("Location: /register-form?error=email_exists"); // Redirect with error parameter
        exit; // Stop further execution
    }

    // Register the user and get the activation hash
    if ($userModel->create($role, $_POST["name"], $email, $_POST["password"])) {
        // Retrieve the newly registered user's ID
        $newUserId = $dbConnection->insert_id;

        // Redirect to profile creation page, passing user ID
        header("Location: /profile2?user_id=" . $newUserId);
        exit;
    } else {
        echo "Error: Registration failed.";
        exit; // Stop further execution
    }
}
?>
