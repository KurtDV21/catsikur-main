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
  $userPosts = $postController->getUserPosts($userId);
  $name = htmlspecialchars($user['name'] ?? '');

} else {
  $userPosts = [];
  header("Location:/loginto");
  exit; 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aoboshi+One&display=swap">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Post</title>
</head>

<body>
    <header>
        <?php include("header.php"); ?>
    </header>

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
                        <a href="#" class="filter-option" data-filter="color" data-value="orange">Orange</a>
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
                    <button class="addbtn" onclick="location.href='/add-post?user_id=<?php echo urlencode($userId); ?>'">
                        Add Post
                    </button>
                </div>
            </div>
            <h1 class="avail"><b>My Post</b></h1>

            <div class="cat-container">
                <?php foreach ($userPosts as $post): ?>
                    <div class="cat-wrapper">
                        <a href="/MyPost-catdetails?post_id=<?php echo urlencode($post['id']); ?>">
                            <div class="cat-card">
                                <img src="<?php echo htmlspecialchars($post['picture']); ?>" alt="Cat Image" class="cat-image">
                                <h2><?php echo htmlspecialchars($post['cat_name']); ?></h2>
                                <p>Color: <?php echo htmlspecialchars($post['color']); ?></p>
                                <p>Age: <?php echo htmlspecialchars($post['age']); ?> years</p>
                                <p>Location: <?php echo htmlspecialchars($post['location']); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
              </div>
    </section>

    <section id="about" class="about">
        <div class="footer-container">
            <div class="about-company">
                <div class="info-item">
                    <img src="/image/place.png" alt="Location Icon" class="place-icon">
                    <p><a href="">9A Masambong St. Bahay Toro, Quezon City</a></p>
                </div>
                <div class="info-item">
                    <img src="/image/phone.png" alt="Phone Icon" class="phone-icon">
                    <p><a href="tel:09123456789">09123456789</a></p>
                </div>
                <div class="info-item">
                    <img src="/image/email.png" alt="Email Icon" class="email-icon">
                    <p><a href="mailto:catfreeadopt@email.com">catfreeadopt@email.com</a></p>
                </div>
            </div>

            <div class="details">
                <h3>ABOUT COMPANY</h3>
                <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente doloremque et dolores doloribus...</p>

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

    <script src="/js/filter.js"></script>
    <script src="/js/package.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
