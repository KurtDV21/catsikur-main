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
// Include the PostController
use App\Core\Database;
use App\Models\PostByIdModel;
use App\Controllers\PostDetailsController;
use App\Models\User;
use App\Controllers\UserController;

require_once __DIR__ . '/../../vendor/autoload.php';

// Initialize Database and Models
$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);
$userController = new UserController($userModel);

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
    header("Location:/loginto");
  exit; // Ensure the script stops after redirection
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
                    <h2>Meet <?php echo htmlspecialchars($post['cat_name']); ?></h2>
                    <div class="cat-details">
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($post['post_type']); ?></p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($post['age']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($post['gender']); ?></p>
                        <p><strong>Color:</strong> <?php echo htmlspecialchars($post['color']); ?></p>
                    </div>
                    <button class="inquire-btn" onclick="location.href='/inquiry-form?post_id=<?php echo htmlspecialchars($_GET['post_id']); ?>'">
                        Inquire
                    </button>
                </div>
            </div>

            <!-- Additional Information Box -->
            <div class="extra-info-box">
                <h3>Additional Information</h3>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($post['Description']); ?></p>
               </div>
        </div>
    </div>
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
  function toggleMenu() {
    const navLinks = document.querySelector('.nav-link');
    navLinks.classList.toggle('active');
}
</script>

</body>
</html>
