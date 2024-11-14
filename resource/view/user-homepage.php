<?php
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;
use App\Models\ApprovedPostsModel;
use App\Controllers\ApprovedPostsController;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$postModel = new ApprovedPostsModel($dbConnection);
$postController = new ApprovedPostsController($postModel);

// Fetch approved posts
$approvedPosts = $postController->showApprovedPosts();

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id']; 
  $user = $userModel->findUserById($userId); 
  $name = $user['name'] ?? ''; 
} else {
  $name = ''; 
  header("Location:/loginto");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="/css/usertab.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aoboshi+One&display=swap">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Homepage</title>
</head>
<body>
<header>
    <nav class="navbar">
    <img src="/image/logo1.png" alt="logo" class="logo">

    
        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

    <ul class="nav-link">
        <li><a href="#home">HOME</a></li>
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
    </nav>
  </header>

  
  
  <section id="main" class="main">
    <div class="main-container">
    <h1 class="text1">Find Your Purr-fect<br> Companion: Adopt<br> a Cat Today!</h1>
    <button class="adopt-btn">ADOPT NOW!</button>
    </div>
  </section>

  <section id="ourcats" class="ourcats">
  <div class="container">
    <div class="buttons-container">
        <div class="dropdown">
            <button class="dropbtn">
                <span class="button-text"><b>COLOR</b></span>
                <span class="arrow-down"><ion-icon name="chevron-down"></ion-icon></span>
                <span class="paint"><ion-icon name="color-palette-outline"></ion-icon></span>
            </button>
            <div class="dropdown-content">
                <a href="#">Category 1</a>
                <a href="#">Category 2</a>
                <a href="#">Category 3</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">
                <span class="button-text"><b>GENDER</b></span>
                <span class="arrow-down-1"><ion-icon name="chevron-down"></ion-icon></span>
                <span class="gender"><ion-icon name="male-female-outline"></ion-icon></span>
            </button>
            <div class="dropdown-content">
                <a href="#">Item 1</a>
                <a href="#">Item 2</a>
                <a href="#">Item 3</a>
            </div>
        </div>

        <div class="dropdown"> 
            <li>
           <button class="addbtn" onclick="location.href='/add-post?user_id=<?php echo htmlspecialchars($userId); ?>'"> Add Post </button>
            </li>
        </div> 
 
    </div>
       <h1 class="avail"><b>AVAILABLE CATS</b></h1>
  
       <div class="cat-container">
  <?php foreach ($approvedPosts as $post): ?>
    <div class="cat-wrapper">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/cat-details?post_id=<?php echo urlencode($post['id']); ?>">
      <?php else: ?>
        <a href="/loginto">
      <?php endif; ?>
          <div class="cat-card">
            <img src="<?php echo htmlspecialchars($post['picture']); ?>" alt="Cat Image" class="cat-image">
            <h2><?php echo htmlspecialchars($post['cat_name']); ?></h2>
            <p>Age: <?php echo htmlspecialchars($post['age']); ?> years</p>
            <p>Location: <?php echo htmlspecialchars($post['location']); ?></p>
          </div>
          <button class="btn-adopt">Adopt Me</button>
        </a>
    </div>
  <?php endforeach; ?>
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

  <script src="/js/package.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
