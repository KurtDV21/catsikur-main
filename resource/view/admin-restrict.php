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
} else {
    $name = "";
    header("Location:/");
  exit; // Ensure the script stops after redirection
}



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="/css/adminadoption.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Aoboshi+One&display=swap">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!-- header nav-bar -->

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
                    <div onclick="location.href='/admin'" class="approval-card">
                        Approval
                    </div>
                    <div class="adoption-posts">
                        Adoption Total Posts
                    </div>
                    <div onclick="location.href='/admin-rescue'" class="rescue-posts">
                        Rescue Total Posts
                    </div>
                    <div onclick="location.href='/admin-rescue'" class="rescue-posts">
                        Restrict User
                    </div>
                </div>

                <div class="pending">
                <table id="myTable" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $database = new Database();
        $dbConnection = $database->connect();

        if ($dbConnection->connect_error) {
            die("Connection Failed: " . $dbConnection->connect_error);
        }

        $sql = "SELECT id, name, email, is_restricted FROM user WHERE role = 'user'";
        $result = $dbConnection->query($sql);

        if (!$result) {
            die("Invalid Query: " . $dbConnection->error);
        }

        while ($row = $result->fetch_assoc()) {
            $buttonText = $row["is_restricted"] ? "Unrestrict" : "Restrict";
            echo "<tr>
                <td>" . htmlspecialchars($row["id"]) . "</td>
                <td>" . htmlspecialchars($row["name"]) . "</td>
                <td>" . htmlspecialchars($row["email"]) . "</td>
                <td><button onclick=\"toggleRestriction(" . $row["id"] . ", " . $row["is_restricted"] . ")\">" . $buttonText . "</button></td>
                </tr>";
        }
        ?>
    </tbody>
</table>




                </div>

            </div>

        </div>
    </div>

    <!-- Footer Section -->
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

</body>

</html>