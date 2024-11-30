<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/catdeets.css">
    <link rel="stylesheet" href="/css/catdeetstab.css">
    <link rel="stylesheet" href="/css/sample-picture.css">  
    <link rel="stylesheet" href="/css/userdropdown.css"> <!-- Add your CSS file link if needed -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat Details</title>
</head>
<body>

<?php
session_start();

// Include the necessary files and initialize database and models
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Database;
use App\Models\PostByIdModel;
use App\Controllers\PostDetailsController;
use App\Models\User;
use App\Controllers\UserController;

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$postModel = new PostByIdModel($dbConnection);
$postController = new PostDetailsController($postModel);
$post = $postController->showSelectedPost();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $user = $userModel->findUserById($userId);
    $name = $user['name'] ?? '';
} else {
    $name = '';
    header("Location:/loginto");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture POST data
    $status = $_POST['status'] ?? '';
    $type = $_POST['type'] ?? '';
    $age = $_POST['age'] ?? '';
    $ageUnit = $_POST['age_unit'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $color = $_POST['color'] ?? '';
    $description = $_POST['description'] ?? '';
    $postId = $_POST['post_id'] ?? '';

    // Debugging: Log the captured POST data
    var_dump($_POST);

    if ($status && $type && $age && $ageUnit && $gender && $color && $description && $postId) {
        // Concatenate age and age unit correctly
        $concatenatedAge = $age . ' ' . $ageUnit;

        // Prepare SQL update query
        $sqlUpdate = "UPDATE post SET post_status = ?, post_type = ?, age = ?, gender = ?, color = ?, Description = ? WHERE id = ?";
        $stmt = $dbConnection->prepare($sqlUpdate);
        
        if ($stmt === false) {
            echo "Error preparing statement: " . $dbConnection->error;
            exit;
        }
        
        // Bind the parameters (all string types except postId which is integer)
        $stmt->bind_param("ssssssi", $status, $type, $concatenatedAge, $gender, $color, $description, $postId);
        
        if ($stmt->execute()) {
            // Refresh the page to show updated status
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "Error updating status: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Invalid request.";
    }
}

if ($post): // If a valid post is found
?>

<!-- HEADER -->
<header>
    <?php include("header.php"); ?>
</header>

<section id="main">
    <div class="parent-container">
        <!-- Cat Card -->
        <div class="cat-card">
            <div class="cat-container">
                <!-- Cat Image -->
                <div class="cat-image">
                    <img src="<?php echo htmlspecialchars($post['picture']); ?>" alt="Cat Image">
                </div>

                <!-- Cat Info -->
                <div class="cat-info">
                    <h2>Meet <span class="cat-name-wrapper"><?php echo htmlspecialchars($post['cat_name']); ?>
                        <img src="/image/edit.png" alt="Edit" class="edit-icon" onclick="enableEditing()">
                    </span></h2>
                    <form id="update-form" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($_GET['post_id']); ?>">
                    <div class="cat-details">
                        <p><strong>Status:</strong> <span id="status-text"><?php echo htmlspecialchars($post['post_status']); ?></span></p>
                        <p><strong>Type:</strong> <span id="type-text"><?php echo htmlspecialchars($post['post_type']); ?></span></p>
                        <p><strong>Age:</strong> <span id="age-text"><?php echo htmlspecialchars($post['age']); ?></span></p>
                        <p><strong>Gender:</strong> <span id="gender-text"><?php echo htmlspecialchars($post['gender']); ?></span></p>
                        <p><strong>Color:</strong> <span id="color-text"><?php echo htmlspecialchars($post['color']); ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Additional Information Box -->
            <div class="extra-info-box">
                <h3>Additional Information</h3>
                <p><strong>Description:</strong> <span id="description-text"><?php echo htmlspecialchars($post['Description']); ?></span></p>
                <div id="description-edit-box" style="display:none;">
                    <textarea id="description" name="description" rows="10" cols="80" required><?php echo htmlspecialchars($post['Description']); ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div id="confirm-button-container"></div>
    </form>
</section>

<!-- Display Sample Pictures -->
<?php if (!empty($post['sample_pictures'])): ?>
    <section id="ourcats">
        <div class="details-wrapper">
            <h2>More Pictures of <?php echo htmlspecialchars($post['cat_name']); ?></h2>
            <div class="cat-details-container">
                <?php foreach ($post['sample_pictures'] as $index => $samplePicture): ?>
                    <div class="cat-detail-box">
                        <img src="<?php echo htmlspecialchars($samplePicture); ?>" alt="Sample Picture <?php echo $index + 1; ?>" class="sample-picture" onclick="openModal('<?php echo htmlspecialchars($samplePicture); ?>')">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php else: ?>
    <p>No sample pictures available for <?php echo htmlspecialchars($post['cat_name']); ?>.</p>
<?php endif; ?>

<!-- Modal Structure -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<!-- About Section -->
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

<footer class="footer">
    Cats Free Adoption & Rescue Philippines
</footer>

<?php
else:
    echo "<p>Cat details not found!</p>";
endif;
?>



<script src="/js/cat-details.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed");
});

function toggleMenu() {
    const navLinks = document.querySelector('.nav-link');
    navLinks.classList.toggle('active');
}

let isEditing = false;

function enableEditing() {
    console.log("EnableEditing function triggered"); // Basic log to ensure function is called

    const statusElement = document.getElementById('status-text');
    const typeElement = document.getElementById('type-text');
    const ageElement = document.getElementById('age-text');
    const genderElement = document.getElementById('gender-text');
    const colorElement = document.getElementById('color-text');
    const descriptionElement = document.getElementById('description-text');
    const descriptionEditBox = document.getElementById('description-edit-box');

    // Basic logging for elements
    console.log("Status Element:", statusElement);
    console.log("Type Element:", typeElement);
    console.log("Age Element:", ageElement);
    console.log("Gender Element:", genderElement);
    console.log("Color Element:", colorElement);
    console.log("Description Element:", descriptionElement);
    console.log("Description Edit Box:", descriptionEditBox);

    if (!statusElement || !typeElement || !ageElement || !genderElement || !colorElement || !descriptionElement) {
        console.error("One or more elements are missing");
        return;
    }

    if (isEditing) {
        console.log("Switching to non-editable state");

        // Capture the values from input fields
        const statusInput = document.getElementById('status').value.trim();
        const typeInput = document.getElementById('type').value.trim();
        const ageInput = document.getElementById('age').value.trim();
        const ageUnitInput = document.getElementById('age_unit').value.trim();
        const genderInput = document.getElementById('gender').value.trim();
        const colorInput = document.getElementById('color').value.trim();
        const descriptionInput = document.getElementById('description').value.trim();

        // Debugging: Log captured values
        console.log("Captured Values:", { statusInput, typeInput, ageInput, ageUnitInput, genderInput, colorInput, descriptionInput });

        statusElement.innerHTML = statusInput;
        typeElement.innerHTML = typeInput;
        ageElement.innerHTML = `${ageInput} ${ageUnitInput}`;
        genderElement.innerHTML = genderInput;
        colorElement.innerHTML = colorInput;
        descriptionElement.innerHTML = descriptionInput;

        descriptionEditBox.style.display = 'none';
        descriptionElement.style.display = 'block';

        const confirmButton = document.getElementById('confirm-button');
        if (confirmButton) {
            confirmButton.remove();
        }

        isEditing = false;
    } else {
        console.log("Switching to editable state");

        const statusText = statusElement.textContent.trim();
        const postTypeText = typeElement.textContent.trim();
        const ageText = ageElement.textContent.trim();
        const genderText = genderElement.textContent.trim();
        const colorText = colorElement.textContent.trim();
        const descriptionText = descriptionElement.textContent.trim();

        let statusOptions = '';
        if (postTypeText === "Adoption" || postTypeText === "Rescue") {
            statusOptions = `
                <option value="Available" ${statusText === 'Available' ? 'selected' : ''}>Available</option>
                <option value="Rehomed" ${statusText === 'Rehomed' ? 'selected' : ''}>Rehomed</option>
            `;
        }

        const ageMatch = ageText.match(/(\d+)\s*(months|years)/i);
        const ageValue = ageMatch ? ageMatch[1] : '';
        const ageUnit = ageMatch ? ageMatch[2] : '';

        statusElement.innerHTML = `
            <select id="status" name="status" required>
                ${statusOptions}
            </select>
        `;

        typeElement.innerHTML = `
            <select id="type" name="type" required>
                <option value="Adoption" ${postTypeText === 'Adoption' ? 'selected' : ''}>Adoption</option>
                <option value="Rescue" ${postTypeText === 'Rescue' ? 'selected' : ''}>Rescue</option>
            </select>
        `;

        ageElement.innerHTML = `
            <div class="input-box">
                <input type="text" id="age" name="age" value="${ageValue}" maxlength="2" required 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);"
                    pattern="[0-9]{1,2}" title="Please enter a valid age between 1 and 99">
                <select id="age_unit" name="age_unit" required>
                    <option value="" disabled ${!ageUnit ? 'selected' : ''}>Select age unit</option>
                    <option value="months" ${ageUnit.toLowerCase() === 'months' ? 'selected' : ''}>Months</option>
                    <option value="years" ${ageUnit.toLowerCase() === 'years' ? 'selected' : ''}>Years</select>
            </div>
        `;

        genderElement.innerHTML = `
            <select id="gender" name="gender" required>
                <option value="" disabled ${!genderText ? 'selected' : ''}>Select Gender</option>
                <option value="Male" ${genderText === 'Male' ? 'selected' : ''}>Male</option>
                <option value="Female" ${genderText === 'Female' ? 'selected' : ''}>Female</option>
            </select>
        `;

        colorElement.innerHTML = `
            <select id="color" name="color" required>
                <option value="" disabled ${!colorText ? 'selected' : ''}>Select Color</option>
                <option value="White" ${colorText === 'White' ? 'selected' : ''}>White</option>
                <option value="Brown" ${colorText === 'Brown' ? 'selected' : ''}>Brown</option>
                <option value="Orange" ${colorText === 'Orange' ? 'selected' : ''}>Orange</option>
                <option value="Black" ${colorText === 'Black' ? 'selected' : ''}>Black</option>
                <option value="Grey" ${colorText === 'Grey' ? 'selected' : ''}>Grey</option>
                <option value="Mixed" ${colorText === 'Mixed' ? 'selected' : ''}>Mixed</option>
            </select>
        `;

        descriptionElement.style.display = 'none';
        descriptionEditBox.innerHTML = `
            <textarea id="description" name="description" rows="10" cols="80" required>${descriptionText}</textarea>
        `;
        descriptionEditBox.style.display = 'block';

        console.log("Description Element updated to textarea:", descriptionEditBox.innerHTML);

        // Create the confirm button only if it doesn't already exist
        if (!document.getElementById('confirm-button')) {
            const confirmButton = document.createElement('button');
            confirmButton.type = 'submit';
            confirmButton.id = 'confirm-button';
            confirmButton.textContent = 'Confirm';
            confirmButton.style.margin = '20px auto';
            confirmButton.style.display = 'block';
            confirmButton.style.width = '100px';
            confirmButton.style.textAlign = 'center';

            document.getElementById('update-form').appendChild(confirmButton);
        }

        isEditing = true;
    }
}
</script>

</body>
</html>
