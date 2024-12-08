<?php
session_start();
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$userId = null;
$userName = null;

// Check if the user is logged in and the name exists
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
    $userId = $_SESSION['user_id'];
    $userName = $_SESSION['user_name']; // Corrected to use 'user_name'

    // Get cat details from POST request
    $catName = trim($_POST['name'] ?? '');
    if (empty($catName)) {
        echo "Cat name is required.";
        exit;
    }
    
    $age = isset($_POST['age']) ? $_POST['age'] . ' ' . ($_POST['age_unit'] ?? '') : '';    
    $location = $_POST['location'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $color = $_POST['color'] ?? '';
    $description = $_POST['description'] ?? '';
    $postType = $_POST['type'] ?? '';

    $profilePicturePath = null;
    $samplePictures = [];

    $target_dir = "uploads/cats/";
    
    // Sanitize folder names (replace spaces with underscores)
    $userFolder = $userId . '_' . str_replace(' ', '_', $userName);
    $catFolder = str_replace(' ', '_', $catName);
    $catDir = $target_dir . $userFolder . '/' . $catFolder . '/';

    if (!is_dir($catDir)) {
        mkdir($catDir, 0777, true);
    }

    // Handle profile picture upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
        $profilePicturePath = $catDir . "profile_" . basename($_FILES["picture"]["name"]);
        if (!move_uploaded_file($_FILES["picture"]["tmp_name"], $profilePicturePath)) {
            echo "Error uploading the profile picture.";
            exit;
        }
    }

    // Handle sample pictures upload
    if (isset($_FILES['extra_pictures']) && is_array($_FILES['extra_pictures']['name'])) {
        foreach ($_FILES['extra_pictures']['name'] as $index => $name) {
            if ($_FILES['extra_pictures']['error'][$index] === 0) {
                $samplePicturePath = $catDir . "sample_" . $index . "_" . basename($name);
                if (move_uploaded_file($_FILES['extra_pictures']['tmp_name'][$index], $samplePicturePath)) {
                    $samplePictures[] = $samplePicturePath;
                } else {
                    echo "Error uploading sample picture $name.";
                    exit;
                }
            } else {
                echo "Error with file $name: " . $_FILES['extra_pictures']['error'][$index];
                exit;
            }
        }
    }

    // Convert sample pictures array to JSON
    $samplePicturesJson = json_encode($samplePictures);

    // Insert the post into the database
    if ($userId) {
        $sqlInsert = "INSERT INTO post (user_id, cat_name, age, location, gender, color, Description, picture, sample_pictures, post_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbConnection->prepare($sqlInsert);

        if ($stmt === false) {
            echo "Error preparing statement: " . $dbConnection->error;
            exit;
        }

        $stmt->bind_param("isssssssss", $userId, $catName, $age, $location, $gender, $color, $description, $profilePicturePath, $samplePicturesJson, $postType);

        if ($stmt->execute()) {
            $_SESSION['post_added'] = true; // Set session variable
            header("Location: /user-homepage");
            exit;
        } else {
            echo "Error adding post: " . $stmt->error;
        }
        

        $stmt->close();
    } else {
        echo "User ID is missing.";
    }
} else {
    echo "You must be logged in to add a post.";
    exit;
}

$dbConnection->close();
?>
