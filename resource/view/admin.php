<?php

use App\Core\Database;
use App\Models\Posts;
use App\Models\User;
use App\Controllers\UserController;
use App\Models\PostApprovalModel; // Include your PostApproval model
use App\Controllers\PostApprovalController; // Include your controller

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();

$userModel = new User($dbConnection);
$userController = new UserController($userModel);

$postsModel = new Posts($dbConnection);
$postApprovalModel = new PostApprovalModel($dbConnection);
$postApprovalController = new PostApprovalController($postApprovalModel);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $user = $userModel->findUserById($userId);
    $name = $user['name'] ?? '';
    $showPic = $userModel->findUserById($userId);
    $pic = $userModel->findUserById($userId);
} else {
    header('Location:/loginto');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postApprovalController->updateApproval();
}

$limit = 2;
$totalPosts = $postsModel->getTotalPosts();
$totalPages = ceil($totalPosts / $limit);
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, min($page, $totalPages));

$offset = ($page - 1) * $limit;

$posts = $postsModel->getPosts($limit, $offset);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/css/adminstyle.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aoboshi+One&display=swap">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!-- header nav-bar -->
    <header>
        <nav class="navbar">
            <img src="/image/logo1.png" alt="logo" class="logo">
            <div class="nav-container">
                <ul class="nav-link">
                    <li><a href="/admin-homepage">HOME</a></li>
                    <li><a href="">OUR CATS</a></li>
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

    <div class="container-admin">
        <div class="background-card">
            <div class="header">
                <div class="image-placeholder">
                    <img src="<?php echo htmlspecialchars($showPic['profile_image_path']); ?>" alt="Admin Profile">
                    <h2>ADMIN</h2>
                </div>
            </div>

            <div class="sherwin">
                <div class="container">
                    <div class="approval-card">
                        Approval
                    </div>
                    <div onclick="location.href='/admin-adoption'" class="adoption-posts">
                        Adoption Total Posts
                    </div>
                    <div onclick="location.href='/admin-rescue'" class="rescue-posts">
                        Rescue Total Posts
                    </div>
                </div>
                <div class="approval-list">
                <?php foreach ($posts as $post): ?>
                    <div class="approval-item-container">
                        <div class="adopter-info">
                            <div class="image-placeholder">
                                <img src="<?php echo htmlspecialchars($post['profile_image_path']); ?>" alt="User Pic">
                            </div>
                            <p class="username"><?php echo htmlspecialchars($post['name']); ?></p>
                        </div>

                        <div class="approval-item">
                            <div class="application-info">
                                <div class="cat-profile">
                                    <img src="<?php echo htmlspecialchars($post['picture']); ?>" alt="Cat Image">
                                    <div class="cat-details">
                                        <p><?php echo htmlspecialchars($post['cat_name']); ?></p>
                                        <p><?php echo htmlspecialchars($post['location']); ?></p>
                                    </div>
                                </div>
                                <div class="actions">
                                    <form action="" method="POST">
                                        <input type="hidden" name="post_id"
                                            value="<?php echo htmlspecialchars($post['id']); ?>">

                                        <button type="submit" name="action" onclick="openPopup()" value="approve"
                                            class="approve-button">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <div class="popup1" id="popup">
                                            <img src="/image/check.png" alt="">
                                            <h2>Approved!</h2>
                                            <p>Post has been successfully approved!</p>
                                            <button class="popbtn" onclick="closePopup()">OK</button>
                                        </div>

                                        <button type="submit" name="action" onclick="openPopup1()" value="deny"
                                            class="deny-button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="popup" id="popup1">
                                            <img src="/image/deny.png" alt="">
                                            <h2>Denied!</h2>
                                            <p>Post has been successfully denied!</p>
                                            <button class="popbtn" onclick="closePopup1()">OK</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="prev-button">Prev</a>
                    <?php endif; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="next-button">Next</a>
                    <?php endif; ?>
                </div>
        </div>
        </div>
                            
    <div class="footer">
        <div class="footer-container">
            <div class="footer-section other-section">
                <h3></h3>
                <p></p>
                <ul class="social-media2">
                    <li><a href="#"><img src="pin.png" alt="pin"></a></li>
                    <li><a href="#"><img src="call.png" alt="call"></a></li>
                    <li><a href="#"><img src="email.png" alt="email"></a></li>
                </ul>
            </div>

            <div class="footer-section about-company">
                <h3>About the Company</h3>
                <p>Lorem ipsum dolor sit amet...</p>
                <ul class="social-media">
                    <li><a href="#"><img src="facebook.png" alt="Facebook"></a></li>
                    <li><a href="#"><img src="messenger.png" alt="messenger"></a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom-container">
            <div class="footer-bottom">
                <p>&copy; All rights reserved.</p>
            </div>

            <div class="footer-bottom-name">
                <p>Cat Free Adoption & Rescue Philippines</p>
            </div>
        </div>
    </div>

    <script>
        let popup = document.getElementById("popup");

        function openPopup() {
            popup.classList.add("open-popup")
        }

        function closePopup() {
            popup.classList.remove("open-popup")
        }

        let popup1 = document.getElementById("popup1");

        function openPopup1() {
            popup1.classList.add("open-popup1")
        }

        function closePopup1() {
            popup1.classList.remove("open-popup1")
        }
    </script>
    <script src="/js/admin.js"></script>
</body>

</html>