<?php
use App\Core\Database;
use App\Models\User;
use App\Controllers\UserController;
use App\Models\ApprovedPostsModel;
use App\Controllers\ApprovedPostsController;
use App\Controllers\PostFilterController;
use App\Models\PostFilterModel;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);
$postFilterModel = new PostFilterModel($dbConnection);
$postFilterController = new PostFilterController($postFilterModel);

$postModel = new ApprovedPostsModel($dbConnection);
$postController = new ApprovedPostsController($postModel);

$approvedPosts = $postController->showApprovedPosts();
$postFilterController->getFilteredPosts();

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
  <link rel="stylesheet" href="/css/userdropdown.css"> <!-- Add your CSS file link if needed -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aoboshi+One&display=swap">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Homepage</title>
</head>

<body>
  <header>
    <?php include("header.php") ?>
  </header>

  <section id="main" class="main">
  <div class="slider-container">
    <div class="slider">
      <div class="slide">
        <img src="image/puspin.jpg" alt="Image 1">
      </div>
      <div class="slide">
        <img src="image/puspin1.jpg" alt="Image 2">
      </div>
      <div class="slide">
        <img src="../image/puspin2.jpg" alt="Image 3">
      </div>
      <div class="slide">
        <img src="../image/puspin3.jpg" alt="Image 3">
      </div>
    </div>
    <!-- Navigation Dots -->
    <div class="dots-container">
      <span class="dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
      <span class="dot" onclick="currentSlide(3)"></span>
      <span class="dot" onclick="currentSlide(4)"></span>
    </div>
    
    <!-- Prev and Next Buttons -->
    <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
    <button class="next" onclick="changeSlide(1)">&#10095;</button>
  </div>

  <div class="main-container">
    <h1 class="text1">Find Your Purr-fect<br> Companion: Adopt<br> a Cat Today!</h1>
    <button class="adopt-btn">ADOPT NOW!</button>
  </div>
  </section>

  <section id="sample" class="sample">
<div class="sample-container">
  <h2>Be Part of our Community by...</h2>
  
  <div class="adopting">
    <h3>Adopting</h3>
    <p>Provide a loving home for one of our community cats. Adopting a cat not only helps the animal but also creates a bond for life.</p>
    <button>Adopt</button>
  </div>
  
  <div class="rescue">
    <h3>Rescue</h3>
    <p>Help us rescue and care for stray and injured cats. Your support makes a big difference in their lives.</p>
    <button>Rescue</button>
  </div>
  
  <div class="community">
    <h3>Community</h3>
    <p>Join our community efforts to feed and care for our feline friends. Become a volunteer or contribute in other ways.</p>
    <button>Join</button>
  </div>
</div>

</section>

  <section id="ourcats" class="ourcats">
    <div class="container">
      <div class="buttons-container">
          <div class="dropdown">
              <button class="dropbtn">
                  <span class="button-text"><b>COLOR</b></span>
                  <span class="arrow-down-1"><ion-icon name="chevron-down"></ion-icon></span>
              </button>
              <div class="dropdown-content">
                  <a href="#" class="filter-option" data-filter="default-color" data-value="default-color">Default</a>
                  <a href="#" class="filter-option" data-filter="color" data-value="white">White</a>
                  <a href="#" class="filter-option" data-filter="color" data-value="brown">Brown</a>
                  <a href="#" class="filter-option" data-filter="color" data-value="orange">Orange</a>
                  <a href="#" class="filter-option" data-filter="color" data-value="grey">Grey</a>
                  <a href="#" class="filter-option" data-filter="color" data-value="black">Black</a>
                  <a href="#" class="filter-option" data-filter="color" data-value="mixed">Mixed</a>
              </div>
          </div>

          <div class="dropdown">
              <button class="dropbtn">
                  <span class="button-text"><b>GENDER</b></span>
                  <span class="arrow-down-1"><ion-icon name="chevron-down"></ion-icon></span>
                  <span class="gender"><ion-icon name="male-female-outline"></ion-icon></span>
              </button>
              <div class="dropdown-content">
                  <a href="#" class="filter-option" data-filter="default-gender" data-value="default-gender">Default</a>
                  <a href="#" class="filter-option" data-filter="gender" data-value="male">Male</a>
                  <a href="#" class="filter-option" data-filter="gender" data-value="female">Female</a>
              </div>
          </div>

        <div class="dropdown">
            <button class="dropbtn" onclick="location.href='/add-post?user_id=<?php echo htmlspecialchars($userId); ?>'">
              Add Post </button>
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
                  <p>Age: <?php echo htmlspecialchars($post['age']); ?> </p>
                  <p>Location: <?php echo htmlspecialchars($post['location']); ?></p>
                </div>
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
        <h3>COMMUNITY RULES</h3>
            <p><a href="/rules">Rules and Regulations</a></p>
            <p><a href="/faq">FAQs</a></p>
            
            
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

  <script src="/js/filter.js"></script>
  <script src="/js/package.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script>
     
  </script>
</body>
</html>