<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/user-profile.css">
    <link rel="stylesheet" href="/css/catdeetstab.css">
    <link rel="stylesheet" href="/css/userdropdown.css"> <!-- Add your CSS file link if needed -->

    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>

<?php
// Include necessary files
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../vendor/autoload.php';

// Initialize Database and Models
$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $user = $userModel->findUserById($userId);
} else {
    header("Location:/loginto");
    exit; // Ensure the script stops after redirection
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $name = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $city = $_POST['city'] ?? '';
    $profile_image_path = $user['profile_image_path']; // Default to existing path

    echo "Received POST data:<br>";
    echo "First Name: $firstName<br>";
    echo "Last Name: $lastName<br>";
    echo "Name: $name<br>";
    echo "Email: $email<br>";
    echo "Phone Number: $phoneNumber<br>";
    echo "City: $city<br>";

    // Handle profile picture upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = realpath(__DIR__ . '/../../public/uploads/users/');
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);  // Create the directory if it doesn't exist
        }

        $targetFile = $targetDir . '/' . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $profile_image_path = 'uploads/users/' . basename($_FILES['profile_image']['name']);
        } else {
            echo "Error uploading the profile image.";
        }
    }

    if ($firstName && $lastName && $name && $email && $phoneNumber && $city) {
        $sqlUpdate = "UPDATE user SET first_name = ?, last_name = ?, name = ?, email = ?, Phone_number = ?, CIty = ?, profile_image_path = ? WHERE id = ?";
        $stmt = $dbConnection->prepare($sqlUpdate);

        if ($stmt === false) {
            echo "Error preparing statement: " . $dbConnection->error;
            exit;
        }

        $stmt->bind_param("sssssssi", $firstName, $lastName, $name, $email, $phoneNumber, $city, $profile_image_path, $userId);

        if ($stmt->execute()) {
            // Refresh the page to show updated information
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "Error updating profile: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid input.";
    }
}


if ($user):
?>

<!-- HEADER -->
<header>
    <nav class="navbar">
        <div class="img">
            <img src="/image/logo1.png" alt="logo" class="logo">
            <h2 class="title"><a href="">User Profile</a></h2>
        </div>
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-link">
            <li><a href="/user-homepage">HOME</a></li>
            <li><a href="#profile">PROFILE</a></li>
            <li><a href="#">ABOUT</a></li>
            <li><a href="#">FAQs</a></li>
            <li>
                <div class="user-dropdown">
                    <button class="user-dropdown-button" onclick="toggleUserDropdown()">
                        <img src="<?= htmlspecialchars($user['profile_image_path']); ?>" alt="Profile Image" class="profile-image">
                    </button>
                    <div class="user-dropdown-content" id="userDropdownContent">
                        <a href="/MyProfile">My Profile</a>
                        <a href="/MyPost">My Post</a>
                        <a href="/logout">Logout</a>
                    </div>
                </div>
            </li>
        </ul> 
    </nav>
</header>

<section id="main">
    <div class="parent-container">
        <!-- User Profile Card -->
        <div class="cat-card">
            <div class="cat-container">
                <div class="extra-info-box">
                    <h3>User Information
                        <img src="/image/edit-profile.png" alt="Edit" class="edit-icon" onclick="enableEditing()">
                    </h3>
                    <div class="cat-image">
                        <img id="profileImage" src="<?= htmlspecialchars($user['profile_image_path']); ?>" alt="Profile Image">
                    </div>

                    <div class="cat-info">
                    <form method="POST" action="" enctype="multipart/form-data" id="userForm">
                        <h2 id="userName"><strong><?= htmlspecialchars($user['name']); ?></strong></h2>
                        <div class="cat-details">
                            <div><strong>Username:</strong> <span id="username"><?= htmlspecialchars($user['name']); ?></span></div>
                            <div><strong>First Name:</strong> <span id="first_name"><?= htmlspecialchars($user['first_name']); ?></span></div>
                            <div><strong>Last Name:</strong> <span id="last_name"><?= htmlspecialchars($user['last_name']); ?></span></div>
                            <div><strong>Email:</strong> <span id="email"><?= htmlspecialchars($user['email']); ?></span></div>
                            <div><strong>Phone Number:</strong> <span id="phone_number"><?= htmlspecialchars($user['Phone_number']); ?></span></div>
                            <div><strong>City:</strong> <span id="city"><?= htmlspecialchars($user['CIty']); ?></span></div>
                        </div>
                    </div>
                    <div class="cat-image" id="imageUploadContainer" style="display:none;">
                    <strong>Change Profile pic: </strong><input type="file" name="profile_image" accept="image/*">
                    </div>
                </div>
                <div id="updateButtonContainer" style="display:none;">
                    <button type="submit">Update Profile</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
else:
    echo "<p>User profile not found!</p>";
endif;
?>

<script>

function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");
}

// Close the dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.closest('.user-dropdown')) {
        const dropdown = document.getElementById("userDropdownContent");
        if (dropdown.classList.contains("show")) {
            dropdown.classList.remove("show");
        }
    }
}

let isEditing = false;

function enableEditing() {
    const fields = ['username', 'first_name', 'last_name', 'email', 'phone_number', 'city'];

    if (isEditing) {
        // Save changes and revert to static display
        fields.forEach(function(field) {
            const inputElement = document.getElementById(field + '_input');
            const value = inputElement.value;

            const spanElement = document.createElement('span');
            spanElement.setAttribute('id', field);
            spanElement.textContent = value;

            // Replace input field with static span
            const parentDiv = inputElement.parentNode;
            parentDiv.replaceChild(spanElement, inputElement);
        });

        // Hide the update button
        const updateButtonContainer = document.getElementById('updateButtonContainer');
        updateButtonContainer.style.display = 'none';

        // Hide the image upload input
        const imageUploadContainer = document.getElementById('imageUploadContainer');
        imageUploadContainer.style.display = 'none';

        isEditing = false;
    } else {
        // Switch to editable state
        fields.forEach(function(field) {
            const spanElement = document.getElementById(field);
            const value = spanElement.textContent || spanElement.innerText;

            // Create input field
            const inputElement = document.createElement('input');
            inputElement.type = 'text';
            inputElement.value = value;
            inputElement.setAttribute('id', field + '_input');
            inputElement.setAttribute('name', field);

            // Insert input field dynamically next to the label
            spanElement.parentNode.appendChild(inputElement);
            spanElement.style.display = 'none'; // Hide static span
        });

        // Show the update button
        const updateButtonContainer = document.getElementById('updateButtonContainer');
        updateButtonContainer.style.display = 'block';

        // Show the image upload input
        const imageUploadContainer = document.getElementById('imageUploadContainer');
        imageUploadContainer.style.display = 'block';

        isEditing = true;
    }
}
</script>

</body>
</html>
