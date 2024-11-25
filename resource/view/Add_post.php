<?php
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;
use App\Components\Navbar;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id']; 
  $user = $userModel->findUserById($userId); 
  if ($user['is_restricted']) { 
    echo "<script>alert('You are restricted from posting.'); window.location.href = 'user-homepage';</script>"; 
    exit; 
  }
  $name = $user['name'] ?? ''; 
} else {
  header("Location:/loginto");
}

    
$logo = "/image/logo1.png";
$name = $user['name'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cat Adoption Post</title>
    <link rel="stylesheet" href="/css/add_post.css"> <!-- Add your CSS file link if needed -->
    <style>
        
    </style>
</head>
<body>
<header>
  <nav class="navbar">
    <img src="/image/logo1.png" alt="logo" class="logo">

    <div class="nav-container">
      <div class="hamburger" onclick="toggleMenu(this)">
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
      </div>

      <ul class="nav-link">
        <li><a href="/user-homepage">HOME</a></li>
        <li><a href="#ourcat">OUR CATS</a></li>
        <li><a href="">ABOUT</a></li>
        <li><a href="">FAQs</a></li>
        <li>
            <div class="user-dropdown">
              <button class="user-dropdown-button" onclick="toggleUserDropdown()">
                  <?php echo htmlspecialchars($name); ?>
              </button>
              <div class="user-dropdown-content" id="userDropdownContent">
                  <a href="/logout">Logout</a>
              </div>
            </div>
        </li>
      </ul>
    </div>
  </nav>
</header>


<section id="addpost">
  <div class="parent-container">
<div class="form-container">
    <h2>Add Cat Adoption Post</h2>
    <form method="POST" enctype="multipart/form-data"  onsubmit="return openPopup(event)">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>"> <!-- Include the user ID -->
    
        <div class="input-box">
            <label for="name">Cat Name:</label>
            <input type="text" id="name" name="name"  required>
        </div>
        
        
        <div class="input-box">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>
        </div>

        <div class="input-box">
  <label for="age">Age:</label>
  <input 
    type="text" 
    id="age" 
    name="age" 
    maxlength="2" 
    required 
    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);"
    pattern="[0-9]{1,2}" 
    title="Please enter a valid age between 1 and 99"
  >
</div>


        <div class="kurt">

        <label for="age_unit">Age unit:</label>
          <select id="age_unit" name="age_unit" required>
              <option value="" disabled selected>Select age unit</option>
              <option value="months">Months</option>
              <option value="years">Years</option>
            </select>

        <label for="type">Post Type:</label>
            <select id="type" name="type" required>
                <option value="" disabled selected>Selct Post Type</option>
                <option value="Adoption" >Adoption</option>
                <option value="Rescue" >Rescue</option>
            </select>

          <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male" >Male</option>
                <option value="Female" >Female</option>
            </select>


            <label for="color">Color:</label>
            <select id="color" name="color" required>
                <option value="" disabled selected>Select Color</option>
                <option value="White" >White</option>
                <option value="Brown" >Brown</option>
                <option value="Orange" >Orange</option>
                <option value="Black" >Black</option>
                <option value="Grey" >Grey</option>
                <option value="Mixed" >Mixed</option>
            </select>
        </div>

        <div class="input-box">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
        </div>

        <div class="upload-box ">
            <label for="picture">Upload Cat Profile Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*" required>
        </div>

        <div class="upload-box">
            <label for="extra_pictures">Upload More Pictures (Up to 3):</label>
            <input type="file" id="extra_pictures" name="extra_pictures[]" accept="image/*" multiple required>
            <small>Max 3 files allowed.</small>
          </div>

        

        <button type="submit" class="btn">Submit Post</button>
        <div class="popup" id="popup">
            <img src="/image/check.png" alt="">
            <h2>Thank You!</h2>
            <p>Your post has been successfully submitted, Thanks!</p>
            <button type="button" class="popbtn" onclick="submitForm()">Confirm and Submit</button>
            <button type="button" class="popbtn" onclick="closePopup()">Cancel</button>
        </div>
    </form>
</div>
</div>
</section>


<script src="/js/add-post.js"></script>
</body>
</html>