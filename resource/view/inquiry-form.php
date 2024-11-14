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
} else {
  $name = ''; 
  header('location:/loginto');
}

// Form processing logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are set
    if (isset($_POST['user_id'], $_POST['post_id'], $_POST['name'], $_POST['occupation'], $_POST['address'], $_POST['email'], $_POST['phone'], $_POST['message'])) {
        $inquiryModel = new Inquiry($dbConnection);
        $inquiryController = new InquiryController($inquiryModel);
        
        // Call the method to handle the inquiry submission
        $inquiryController->submitAdoptionInquiry();
        
        // Optionally, redirect to a success page or show a success message
        header('Location: /user-homepage?user_id=' . urlencode(htmlspecialchars($userId))); // Redirect t   o the user homepage
        exit;
    } else {
        // Handle missing fields (optional)
        $errorMessage = "Please fill in all required fields.";
    }
}
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; 
    $user = $userModel->findUserById($userId); 
    $name = $user['name'] ?? ''; 
    $phone =$user['Phone_number'] ?? '';
    $email = $user['email'] ?? '';
  } else {
    $name = ''; 
    $phone = ''; 
    $email = '';
  }

  $postId = isset($_GET['post_id']) ? $_GET['post_id'] : '';

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

            <form id="form" action="javascript:void(0);" method="POST">
                <input type="hidden" name="user_id" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"> 
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">

                <div class="input-group">
                    <div class="input-box">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <label>Email Address</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($name); ?>">
                        <label>First Name</label>
                    </div>

                    <div class="input-box">
                        <input type="text" name="lastname" required placeholder=" ">
                        <label>Last Name</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <label>Email Address</label>
                    </div>

                    <div class="input-box">
                        <input type="tel" name="phone" required value="<?php echo htmlspecialchars($phone); ?>">
                        <label>Phone Number</label>
                    </div>
                </div>

                <div class="input-box">
                    <input type="text" name="address" required placeholder=" ">
                    <label>Address</label>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <input type="number" name="age" required placeholder=" ">
                        <label>Age</label>
                    </div>
                </div>

                <div class="guardian">
                    <p>Guardian Name - Relationship - Contact Number: (FOR 18 YRS OLD & BELOW ONLY) <br> Example: Melinda Reyes - Mother - 0917522634 | FOR 18yrs old and below ONLY.</p>
                </div>

                <div class="input-group">
                    <div class="input-box">
                        <input type="text" name="guardian" required placeholder=" ">
                        <label>Your Answer</label>
                    </div>
                </div>

                <div class="guardian">
                    <p>Industry of the Company You are working for:</p>
                </div>

                <div class="input-group">
                    <div class="dropdown">
                        <span class="dropbtn" onclick="toggleDropdown()" required>
                            Select a company
                            <span class="caret"></span>
                        </span>
                        <div class="dropdown-content" id="dropdownContent">
                            <ul>
                                <li><input type="radio" name="company_industry" value="Office Admin" required> Office Admin</li>
                                <li><input type="radio" name="company_industry" value="Service Crew" required> Service Crew</li>
                                <li><input type="radio" name="company_industry" value="Call Center" required> Call Center</li>
                            </ul>
                        </div>
                    </div>

                    <div class="input-box">
                        <input type="text" name="fb" required placeholder=" ">
                        <label>Your Facebook profile link:</label>
                    </div>
                </div>

                <div class="questions-container">
                    <div class="question">
                        <p>Do you live in a:<span class="required">*</span></p>
                        <div class="radio-options">
                            <div>
                                <input type="radio" name="residence" value="House" id="house" required>
                                <span>House</span>
                            </div>
                            <div>
                                <input type="radio" name="residence" value="Apartment" id="apartment" required>
                                <span>Apartment</span>
                            </div>
                            <div>
                                <input type="radio" name="residence" value="Condo" id="condo" required>
                                <span>Condo</span>
                            </div>
                            <div>
                                <input type="radio" name="residence" value="Other" id="other" onclick="showOtherInput(true)" required>
                                <span>Other...</span>
                                <input type="text" class="other-input" name="residence_other" id="otherCaregiverInput" placeholder="Specify if Other" required>
                            </div>
                        </div>
                    </div>

                    <div class="question">
                        <p>Do you own the house you are currently residing in or are you a tenant renting the house?:<span class="required">*</span></p>
                        <div class="radio-options">
                            <div>
                                <input type="radio" name="has_pets" value="own" id="own" required>
                                <span>Own/Landlord</span>
                            </div>
                            <div>
                                <input type="radio" name="has_pets" value="rent" id="rent" required>
                                <span>Rent/Tenant</span>
                            </div>
                        </div>
                    </div>

                    <div class="question">
                        <p>Are all members of your household in agreement with this adoption?:<span class="required">*</span></p>
                        <div class="radio-options">
                            <div>
                                <input type="radio" name="outdoor_space" value="Yes" id="yes_outdoor" required>
                                <span>Yes</span>
                            </div>
                            <div>
                                <input type="radio" name="outdoor_space" value="No" id="no_outdoor" required>
                                <span>No</span>
                            </div>
                            <div>
                                <input type="radio" name="outdoor_space" value="Other" id="other_outdoor" required>
                                <span>Don't know/</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="reset" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-confirm" onclick="location.href='/inquiry-form2'">Next</button>
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
  
<script src="/js/inquiry-form.js"></script>

<script>
    const drop = document.querySelectorAll('.drop');

    drop.forEach(drop=> {

        const select = drop.querySelector('.select');
        const caret = drop.querySelector('.caret');
        const menu = drop.querySelector('.menu');
        const option = drop.querySelector('.menu li');
        const selected = drop.querySelector('.selected');

        select.addEventListener('click', () => {
            select.classList('select-clicked');
            caret.classList('caret-rotate');
            menu.classList('menu-open');
        });

        option.forEach(option => {
            option.addEventListener('click', () =>{
                selected.innerText = option.innerText;
                
                select.classList.remove('select-clicked');
                caret.classList.remove('caret-rotate');
                menu.classList.remove('menu-open');

                options.forEach(option => {
                    option.classList.remove('active');
                });
                option.classList.add('active');
            });
        });
    });

</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>