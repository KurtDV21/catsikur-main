<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/catdeets.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
// Include the PostController
use App\Core\Database;
use App\Models\PostByIdModel;
use App\Controllers\PostDetailsController;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$database = new Database();
$dbConnection = $database->connect();
$postModel = new PostByIdModel($dbConnection);
$postController = new PostDetailsController($postModel);
$post = $postController->showSelectedPost(); // Get selected cat post

session_start();

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id']; 
  $user = $userModel->findUserById($userId); 
  $name = $user['name'] ?? ''; 
} else {
  $name = ''; 
}
if ($post): // If a valid post is found
?>

    <!-- HEADER -->
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
          <li><a href="#">ABOUT</a></li>
          <li><a href="#">FAQs</a></li>
          <li><label><?php echo htmlspecialchars($name); ?></label></li>
        </ul> 
      </div>
    </nav>
  </header>


<section>
  <div class="parent-container">
    <div class="cat-container"> 
      <div class="cat-image">
        <img src="<?php echo htmlspecialchars($post['picture']); ?>" alt="Cat Image">
      </div>
      <div class="cat-info">
        <h2>Meet <?php echo htmlspecialchars($post['cat_name']); ?></h2>
        <div class="cat-details">
          <p><strong>Age:</strong> <?php echo htmlspecialchars($post['age']); ?> Months</p>
          <p><strong>Gender:</strong> <?php echo htmlspecialchars($post['gender']); ?></p>
          <p><strong>Color:</strong> <?php echo htmlspecialchars($post['color']); ?></p>
          <button class="inquire-btn" onclick="location.href='/inquiry-form?post_id=<?php echo htmlspecialchars($_GET['post_id']);?>'">Inquire</button>
        </div>
      </div>  
    </div>
  </div>
</section>

<section>

<div class="details-wrapper">
    <h2>More Details about Cat:</h2>
    <div class="cat-details-container">
        <div class="cat-detail-box">CAT DETAILS</div>
        <div class="cat-detail-box">CAT DETAILS</div>
        <div class="cat-detail-box">CAT DETAILS</div>
    </div>
</div>
</section>

<?php
else:
    echo "<p>Cat details not found!</p>";
endif;
?>
</body>


<footer class="footer">
        <div class="footer-container">
    <!-- About Section -->
    <div class="footer-about">
        <h3>ABOUT COMPANY</h3>
        <div class="para">
        <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente<br> doloremque et dolores doloribus est animi maiores. Ut fugiat <br> molestiae nam quia earum qui aliquid aliquid ab corrupti officiis. Et<br> temporibus quia 33 incidunt adipisci ea deleniti vero 33<br> reprehenderit repellat.</p>
        </div>
      
       
    </div>
 <!-- Social Media Section -->
    <div class="fb">
      <img src="facebook.png" alt="" class="fb-icon">
    </div>

    <div class="mess">
      <img src="messenger.png" alt="" class="mess-icon">
    </div>

   

</div>
 
<div class="details-container">
<div class="details">
<div class="place">
    <img src="place.png" alt="" class="place-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>

  <div class="phone">
    <img src="phone.png" alt="" class="phone-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>

  <div class="email">
    <img src="email.png" alt="" class="email-icon">
    <p>ASDWQDASDASDASDASDASDASDASD</p>

  </div>
  </div>
  </div>


  



    <!-- Left Bottom Text -->
    <div class="footer-left-text">
      <p>Cat Free Adoption & Rescue Philippines</p>
    </div>
  </div>
  
  
</footer>
</html>