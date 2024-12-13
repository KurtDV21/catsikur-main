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
<header>
        <nav class="navbar">
        <div class="img">
    <img src="/image/logo1.png" alt="logo" class="logo">
    <h2 class="title"><a href="">Cat Free Adoption</a></h2>
    </div>

        <div class="hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

      <ul class="nav-link">
        <li><a href="/user-homepage">HOME</a></li>
        <li><a href="/ourcats">OUR CATS</a></li>
        <li><a href="#about">ABOUT</a></li>
        <li><a href="#faq">FAQs</a></li>
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

    <!-- header nav-bar -->
<!-- header nav-bar -->
<section id="main">
    <div class="container-admin">
        <div class="sidebar">
            <div class="user-profile">
                <div class="image-placeholder">
                    <img src="<?php echo htmlspecialchars($showPic['profile_image_path']); ?>" alt="Admin Profile">
                    <h2>ADMIN</h2>
                </div>
            </div>

            <div class="navigation">
                <div class="container">
                    <div onclick="location.href='/admin'" class="approval-card">
                        <a href="/admin">Approval</a>
                    </div>
                    <div class="adoption-posts">
                        <a href="">Adoption Total Posts</a>
                    </div>
                    <div onclick="location.href='/admin-rescue'" class="rescue-posts">
                        <a href="/admin-rescue">Rescue Total Posts</a>
                    </div>
                    <div onclick="location.href='/admin-restrict'" class="rescue-posts">
                        <a href="/admin-restrict">Restrict User</a>
                    </div>
                    <div onclick="location.href='/admin-pdf'" class="rescue-posts">
                        <a href="/admin-pdf">Form Approval</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="pending">
                <form method="POST" action="">
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th>Question ID</th>
                                <th>Question</th>
                                <th>Page</th>
                                <th>Type</th>
                                <th>Additional Info</th>
                                <th>Is Optional</th>
                                <th>Answer ID</th>
                                <th>Answer Text</th>
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

                            // Join questions and answers tables
                            $sql = "
                                SELECT q.id AS question_id, q.question, q.page, q.type, q.additional_info, q.is_optional, 
                                       a.id AS answer_id, a.answer_text
                                FROM questions q
                                LEFT JOIN answers a ON q.id = a.question_id
                            ";
                            $result = $dbConnection->query($sql);

                            if (!$result) {
                                die("Invalid Query: " . $dbConnection->error);
                            }

                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td><input type='text' name='question_id[]' value='" . htmlspecialchars($row["question_id"]) . "' readonly></td>
                                    <td><input type='text' name='question[]' value='" . htmlspecialchars($row["question"]) . "'></td>
                                    <td><input type='text' name='page[]' value='" . htmlspecialchars($row["page"]) . "'></td>
                                    <td><input type='text' name='type[]' value='" . htmlspecialchars($row["type"]) . "'></td>
                                    <td><input type='text' name='additional_info[]' value='" . htmlspecialchars($row["additional_info"]) . "'></td>
                                    <td><input type='text' name='is_optional[]' value='" . htmlspecialchars($row["is_optional"]) . "'></td>
                                    <td><input type='text' name='answer_id[]' value='" . htmlspecialchars($row["answer_id"]) . "' readonly></td>
                                    <td><input type='text' name='answer_text[]' value='" . htmlspecialchars($row["answer_text"]) . "'></td>
                                    <td><button type='submit' name='save' value='" . htmlspecialchars($row["question_id"]) . "'>Save</button></td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $questionId = $_POST['save'];
    $questions = $_POST['question'];
    $pages = $_POST['page'];
    $types = $_POST['type'];
    $additionalInfos = $_POST['additional_info'];
    $isOptionals = $_POST['is_optional'];
    $answerIds = $_POST['answer_id'];
    $answerTexts = $_POST['answer_text'];

    // Prepare update statements
    foreach ($questions as $key => $question) {
        if ($questionId == $_POST['question_id'][$key]) {
            $page = $pages[$key];
            $type = $types[$key];
            $additionalInfo = $additionalInfos[$key];
            $isOptional = $isOptionals[$key];
            $answerId = $answerIds[$key];
            $answerText = $answerTexts[$key];

            // Update question
            $stmt = $dbConnection->prepare("UPDATE questions SET question = ?, page = ?, type = ?, additional_info = ?, is_optional = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $question, $page, $type, $additionalInfo, $isOptional, $questionId);
            $stmt->execute();
            $stmt->close();

            // Update answer if answer_id is not empty
            if ($answerId) {
                $stmt = $dbConnection->prepare("UPDATE answers SET answer_text = ? WHERE id = ?");
                $stmt->bind_param("si", $answerText, $answerId);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Redirect to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>





    <!-- Footer Section -->
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