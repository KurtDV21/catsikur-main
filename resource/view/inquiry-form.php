<?php
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;
use App\Models\Inquiry;
use App\Controllers\InquiryController;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];  
    $user = $userModel->findUserById($userId); 
    $name = $user['name'] ?? ''; 
    $phone = $user['Phone_number'] ?? '';
    $email = $user['email'] ?? '';
} else {
    header('location:/loginto');
    exit;
}

$postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['lastSelectedCompany'] = $_POST['company'] ?? '';
    $_SESSION['has_pets'] = $_POST['has_pets'] ?? '';
    

    
    
    $name = htmlspecialchars(trim($_POST['name']));
    $lastname = htmlspecialchars(trim($_POST['lastname'])); 
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $age = $_POST['age'];
    $guardian = htmlspecialchars(trim($_POST['guardian'] ?? ''));
    $lastSelectedCompany = $_POST['company'] ?? '';
    $facebook = $_POST['fb'] ?? '';
    $housing = $_POST['housing'] ?? '';
    $housingOther = htmlspecialchars(trim($_POST['housing_other'] ?? ''));
    $has_pets = $_POST['has_pets'] ?? '';
    $outdoor_space = $_POST['outdoor_space'] ?? '';

    $name = $name . " " . $lastname;

    if ($housing === 'Other' && !empty($housingOther)) {
        $housing = $housing . ': ' . $housingOther; // e.g., "Other: My Custom Text"
    }

    $_SESSION['housing'] = $housing;

    $_SESSION['name'] = $name;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['address'] = $address;
    $_SESSION['age'] = $age;
    $_SESSION['company'] = $lastSelectedCompany;
    $_SESSION['guardian'] = $guardian;
    $_SESSION['facebook'] = $facebook;
    $_SESSION['outdoor_space'] = $outdoor_space;
    
    // Input validation
    $errors = [];

    // Validate name fields
    if (empty($name) || empty($lastname)) {
        $errors[] = "First and last name are required.";
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    // Validate phone number (simple check to ensure it’s not empty)
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }

    // Validate address
    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    // Validate age (ensure it's a valid number)
    if (empty($age) || !is_numeric($age)) {
        $errors[] = "A valid age is required.";
    }

    // Validate guardian details (only if age is 18 or below)
    if ($age <= 18 && empty($guardian)) {
        $errors[] = "Guardian details are required for applicants aged 18 or below.";
    }

    // Check if there are any validation errors
    if (count($errors) > 0) {
        // Store errors in session or display them
        $_SESSION['errors'] = $errors;
        // Redirect to the form with error messages
        header('Location: /inquiry-form'); 
        exit;
    }    

    // Redirect to the next step of the form
    header('Location: /inquiry-form2?post_id=' . $postId);
    exit;

}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/adoptform.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<header>
    <?php include("header.php"); ?>
  </header>



  <section class="form1" id="form1">
    <div class="outer-container">
        <div class="form-container">
            <div class="info">
                <h1>Cat Adoption Application Form</h1>
                <h2>Applicant Information</h2>
                <p>Rest assured that all information you will provide is strictly confidential and for adoption screening purposes only. This section will ask information on the prospective adopter. Kindly fill out everything with the correct information. <br><br>PROVIDE THE EMAIL ADDRESS OF THE CAT OWNER/RESCUER BELOW!</p>
            </div>
            <?php if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); // Clear errors after displaying them ?>
            <?php endif; ?>
            <form id="form"  method="POST" >
                <input type="hidden" name="user_id" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"> 
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">

                <div class="input-group">
                    <div class="input-box">
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($name); ?>">
                        <label>First Name</label>
                    </div>

                    <div class="input-box">
                        <input type="text" name="lastname" required placeholder=" " value="<?php echo isset($_SESSION['lastname']) ? $_SESSION['lastname'] : ''; ?>" />
                        <label>Last Name</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <label>Email Address</label>
                    </div>

                    <div class="input-box">
                        <input type="tel" name="phone" required value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" />
                        <label>Phone Number</label>
                    </div>
                </div>

                <div class="input-box">
                    <input type="text" name="address" required placeholder=" " value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>" />
                    <label>Address</label>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <input 
                            type="text" 
                            name="age" 
                            required 
                            placeholder=" " 
                            maxlength="3" 
                            value="<?php echo isset($_SESSION['age']) ? $_SESSION['age'] : ''; ?>" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);"
                            pattern="[0-9]{1,3}" 
                            title="Please enter a valid age (1-999)."
                        />
                        <label>Age</label>
                    </div>
                </div>


                <div class="guardian">
                    <p>Guardian Name - Relationship - Contact Number: (FOR 18 YRS OLD & BELOW ONLY) <br> Example: Melinda Reyes - Mother - 0917522634 | FOR 18yrs old and below ONLY.</p>
                </div>

                <div class="input-group">
                    <div class="input-box">
                    <input type="text" name="guardian" placeholder=" " value="<?php echo isset($_SESSION['guardian']) ? $_SESSION['guardian'] : ''; ?>" />
                    <label>Your Answer</label>
                    </div>
                </div>

                <div class="guardian">
                    <p>Industry of the Company You are working for:</p>
                </div>

                <div class="input-group">
                    <div class="dropdown">
                        <span class="dropbtn" onclick="toggleDropdown()" required name="company">
                            <?php echo htmlspecialchars($_SESSION['lastSelectedCompany'] ?? 'Select a company'); ?>
                            <span class="caret"></span>
                        </span>
                        <div id="dropdownContent" class="dropdown-content"></div>
                    </div>
                

                <!-- Hidden input to store selected company -->
                <input type="hidden" id="companyInput" name="company" value="<?php echo htmlspecialchars($_SESSION['lastSelectedCompany'] ?? ''); ?>">

                    <div class="input-box">
                    <input type="text" name="fb" required placeholder=" " value="<?php echo isset($_SESSION['facebook']) ? htmlspecialchars($_SESSION['facebook']) : ''; ?>" />
                    <label>Your Facebook profile link:</label>
                    </div>
                </div>

                <div class="questions-container">
                    <div class="question">
                        <p>Do you live in a:<span class="required">*</span></p>
                        <div class="radio-options">
                            <div>
                                <input type="radio" name="housing" value="House" id="house" <?php echo (isset($_SESSION['housing']) && $_SESSION['housing'] == 'House') ? 'checked' : ''; ?>>
                                <span>House</span>
                            </div>
                            <div>
                                <input type="radio" name="housing" value="Apartment" id="apartment" <?php echo (isset($_SESSION['housing']) && $_SESSION['housing'] == 'Apartment') ? 'checked' : ''; ?>>
                                <span>Apartment</span>
                            </div>
                            <div>
                                <input type="radio" name="housing" value="Condo" id="condo" <?php echo (isset($_SESSION['housing']) && $_SESSION['housing'] == 'Condo') ? 'checked' : ''; ?>>
                                <span>Condo</span>
                            </div>
                            <div>
                                <input type="radio" name="housing" value="Other" id="other" onclick="showOtherInput(true)" <?php echo (strpos($_SESSION['housing'] ?? '', 'Other:') === 0) ? 'checked' : ''; ?>>
                                <span>Other...</span>
                                <input type="text" class="other-input" name="housing_other" id="otherResidence" placeholder="Specify if Other"
                                    value="<?php echo (strpos($_SESSION['housing'] ?? '', 'Other:') === 0) ? htmlspecialchars(substr($_SESSION['housing'], 6)) : ''; ?>" />
                            </div>
                        </div>

                    </div>

                    <div class="question">
                        <p>Do you own the house you are currently residing in or are you a tenant renting the house?:<span class="required">*</span></p>
                        <div class="radio-options">
                            <div>
                                <input type="radio" name="has_pets" value="own" id="own" <?php echo (isset($_SESSION['has_pets']) && $_SESSION['has_pets'] == 'own') ? 'checked' : ''; ?> >
                                <span>Own/Landlord</span>
                            </div>
                            <div>
                                <input type="radio" name="has_pets" value="rent" id="rent" <?php echo (isset($_SESSION['has_pets']) && $_SESSION['has_pets'] == 'rent') ? 'checked' : ''; ?> >
                                <span>Rent/Tenant</span>
                            </div>
                        </div>
                    </div>

                    <div class="question">
                        <p>Are all members of your household in agreement with this adoption?:<span class="required">*</span></p>
                        <div class="radio-options">
                            <div>
                                <input type="radio" name="outdoor_space" value="Yes" id="yes_outdoor" <?php echo (isset($_SESSION['outdoor_space']) && $_SESSION['outdoor_space'] == 'Yes') ? 'checked' : ''; ?> >
                                <span>Yes</span>
                            </div>
                            <div>
                                <input type="radio" name="outdoor_space" value="No" id="no_outdoor" <?php echo (isset($_SESSION['outdoor_space']) && $_SESSION['outdoor_space'] == 'No') ? 'checked' : ''; ?> >
                                <span>No</span>
                            </div>
                            <div>
                                <input type="radio" name="outdoor_space" value="Other" id="other_outdoor" <?php echo (isset($_SESSION['outdoor_space']) && $_SESSION['outdoor_space'] == 'Other') ? 'checked' : ''; ?> >
                                <span>Don't know</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn-cancel" onclick="location.href='/cat-details?post_id=<?php echo htmlspecialchars($postId); ?>'">Back</button>

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
  

  <footer class = "footer">
    Cats Free Adoption & Rescue Philippines
  </footer>
  

  <script>
    const drop = document.querySelectorAll('.drop');

    drop.forEach(drop => {
        const select = drop.querySelector('.select');
        const caret = drop.querySelector('.caret');
        const menu = drop.querySelector('.menu');
        const options = drop.querySelectorAll('.menu li');
        const selected = drop.querySelector('.selected');

        select.addEventListener('click', () => {
            select.classList.toggle('select-clicked');
            caret.classList.toggle('caret-rotate');
            menu.classList.toggle('menu-open');
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                selected.innerText = option.innerText; // Set the selected option
                select.classList.remove('select-clicked');
                caret.classList.remove('caret-rotate');
                menu.classList.remove('menu-open');

                // Deselect all options and highlight the selected one
                options.forEach(option => {
                    option.classList.remove('active');
                });
                option.classList.add('active');
            });
        });
    });

    function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdownContent");
    dropdown.classList.toggle("show");
}

// Toggle the main dropdown
function toggleDropdown() {
    const dropdown = document.getElementById("dropdownContent");
    dropdown.classList.toggle("show");
}

// Close the dropdowns if clicked outside
function closeDropdowns(event) {
    if (!event.target.closest('.user-dropdown') && !event.target.closest('.dropdown')) {
        closeUserDropdown();
        closeMainDropdown();
    }
}

function closeUserDropdown() {
    const userDropdown = document.getElementById("userDropdownContent");
    if (userDropdown.classList.contains("show")) {
        userDropdown.classList.remove("show");
    }
}

function closeMainDropdown() {
    const mainDropdown = document.getElementById("dropdownContent");
    if (mainDropdown.classList.contains("show")) {
        mainDropdown.classList.remove("show");
    }
}

window.onclick = closeDropdowns;


function addOption(companyName) {
    const dropdownContent = document.getElementById("dropdownContent");

    const option = document.createElement("div");
    option.className = "dropdown-item";
    option.textContent = companyName;

    option.onclick = function () {
        document.querySelector(".dropbtn").textContent = companyName;
        document.getElementById('companyInput').value = companyName;
        closeMainDropdown(); // Close the dropdown when an option is selected
    };

    dropdownContent.appendChild(option);
}

function showOtherInput(isVisible) {
    const otherInput = document.getElementById('otherResidence');
    otherInput.style.display = isVisible ? 'inline-block' : 'none';
    if (!isVisible) {
        otherInput.value = ''; // Clear value when hidden
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const isOtherSelected = document.getElementById('other').checked;
    showOtherInput(isOtherSelected);
});

document.addEventListener("DOMContentLoaded", () => {
    const savedCompany = '<?php echo htmlspecialchars($_SESSION['lastSelectedCompany'] ?? ''); ?>';

    if (savedCompany) {
        document.querySelector(".dropbtn").textContent = savedCompany;
        document.getElementById('companyInput').value = savedCompany;
    }

    const companyOptions = [
        "Homestay/HouseWife/HouseHusband",
        "Student - High School/Senior Highschool",
        "Student - College",
        "Accountancy, banking and finance",
        "Aerospace",
        "Architecture, creative arts and design",
        "Business, consulting and management",
        "Charity and volunteer work",
        "Education",
        "Electronics, robotics, and mechanics",
        "Energy and Utilities",
        "Engineering, manufacturing and construction",
        "Environment and agriculture",
        "Food, food manufacturing",
        "Healthcare",
        "Hospitality and event management",
        "Information technology & computer",
        "Law",
        "Law enforcement and security",
        "Leisure, entertainment, sports and tourism",
        "Marketing, advertising and PR",
        "Media, news and internet",
        "Mining",
        "Public Services and administration",
        "Recruitment and HR",
        "Retail",
        "Sales and E-commerce",
        "Science and Pharmaceuticals",
        "Social Care",
        "Telecommunication and BPO",
        "Transport and logistics",
    ];

    companyOptions.forEach(option => addOption(option));
});

function toggleOtherInput() {
    const otherInput = document.getElementById('otherResidence');
    const otherRadio = document.querySelector('input[name="housing"][value="Other"]');
    otherInput.style.display = otherRadio.checked ? 'inline-block' : 'none';
    if (!otherRadio.checked) {
        otherInput.value = ''; // Clear value when hidden
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleOtherInput();
    const radioButtons = document.querySelectorAll('input[name="housing"]');
    radioButtons.forEach((radio) => {
        radio.addEventListener('change', toggleOtherInput);
    });
});

document.getElementById('form').addEventListener('submit', function (event) {
    let isValid = true;
    const errorMessages = [];

    const firstName = document.querySelector('input[name="name"]').value.trim();
    const lastName = document.querySelector('input[name="lastname"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();
    const address = document.querySelector('input[name="address"]').value.trim();
    const age = document.querySelector('input[name="age"]').value.trim();
    const guardian = document.querySelector('input[name="guardian"]').value.trim();
    const housing = document.querySelector('input[name="housing"]:checked');
    const hasPets = document.querySelector('input[name="has_pets"]:checked');
    const outdoorSpace = document.querySelector('input[name="outdoor_space"]:checked');

    if (!firstName) {
        isValid = false;
        errorMessages.push("First name is required.");
    }

    if (!lastName) {
        isValid = false;
        errorMessages.push("Last name is required.");
    }

    if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
        isValid = false;
        errorMessages.push("Valid email is required.");
    }

    if (!phone || !/^\d{10,15}$/.test(phone)) {
        isValid = false;
        errorMessages.push("Phone number must be 10-15 digits.");
    }

    if (!address) {
        isValid = false;
        errorMessages.push("Address is required.");
    }

    if (!age) {
        isValid = false;
        errorMessages.push("Age is required.");
    }

    if (age < 18 && !guardian) {
        isValid = false;
        errorMessages.push("Guardian details are required for applicants under 18.");
    }

    if (!housing) {
        isValid = false;
        errorMessages.push("Please select a housing type.");
    }

    if (!hasPets) {
        isValid = false;
        errorMessages.push("Please indicate your housing status.");
    }

    if (!outdoorSpace) {
        isValid = false;
        errorMessages.push("Please indicate your household agreement.");
    }

    if (!isValid) {
        event.preventDefault();
        alert(errorMessages.join('\n'));
    }
});

</script>


<script src="/js/inquiry-form.js"></script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>