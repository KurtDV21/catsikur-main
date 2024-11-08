<?php

use App\Core\Database;
use App\Models\Admin;
use App\Controllers\AdminController;

require_once __DIR__ . '/../../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$adminModel = new Admin($dbConnection);  // Corrected from 'User' to 'Admin'
$adminController = new AdminController($adminModel);  // Corrected from 'UserController' to 'AdminController'

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = 'admin'; // Set the role to 'admin'
    
    // Register the admin and get the activation hash
    $activation_hash = $adminController->register($role, $_POST["name"], $_POST["email"], $_POST["password"]);
    
    // Retrieve the newly registered admin's ID
    $newAdminId = $dbConnection->insert_id;

    // Send activation email with the activation link
    $activation_link = "http://localhost/activate-account.php?token=" . $activation_hash; // Update your domain
    // Use PHPMailer or similar library to send the email here

    // Redirect to profile creation page, passing admin ID
    header("Location: /profile2?user_id=" . $newAdminId);
    exit;
}
?>