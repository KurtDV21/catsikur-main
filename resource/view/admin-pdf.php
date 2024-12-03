<?php

use App\Core\Database;
use App\Models\User;
use App\Models\Posts;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/fpdf/fpdf.php';

session_start();

$database = new Database();
$dbConnection = $database->connect();

$userModel = new User($dbConnection);
$postModel = new Posts($dbConnection);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $user = $userModel->findUserById($userId);
    $name = $user['name'] ?? '';
} else {
    header('Location: /loginto');
    exit;
}

$action = $_GET['action'] ?? '';

function generatePDF($inquiry)
{
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(40, 10, 'Inquiry Details');
    $pdf->Ln();
    foreach ($inquiry as $key => $value) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, "$key: $value");
        $pdf->Ln();
    }

    $pdf->Output('I', 'inquiry_details.pdf');
    exit;
}

if ($action === 'deny' && isset($_GET['inquiry_id'])) {
    $inquiryId = $_GET['inquiry_id'];

    // Update the inquiry status to denied in the database
    $updateInquiry = $dbConnection->prepare("
        UPDATE inquiries SET status = 'denied' WHERE id = ?
    ");
    if ($updateInquiry === false) {
        echo "Failed to prepare query: " . $dbConnection->error;
        exit;
    }
    $updateInquiry->bind_param("i", $inquiryId);
    $updateInquiry->execute();
    $updateInquiry->close();

    // Display the denial modal
    echo "
        <div id='denyModal' style='display: block;'>
            <div style='background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%;'>
                <div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; text-align: center;'>
                    <h2>Inquiry Denied</h2>
                    <p>The inquiry has been denied.</p>
                    <div style='display: flex; justify-content: center; margin-top: 20px;'>
                        <button onclick='document.getElementById(\"denyModal\").style.display=\"none\";' style='padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 5px;'>Close</button>
                    </div>
                </div>
            </div>
        </div>
    ";
}


if ($action === 'approve' && isset($_GET['inquiry_id'])) {
    $inquiryId = $_GET['inquiry_id'];

    // Fetch inquiry details
    $query = $dbConnection->prepare("
        SELECT inquiries.user_id AS inquirer_id, post.user_id AS author_id, inquiries.post_id, inquiries.status
        FROM inquiries
        JOIN post ON inquiries.post_id = post.id
        WHERE inquiries.id = ?
    ");
    if ($query === false) {
        echo "Failed to prepare query: " . $dbConnection->error;
        exit;
    }
    $query->bind_param("i", $inquiryId);
    $query->execute();
    $inquiry = $query->get_result()->fetch_assoc();
    $query->close();

    if ($inquiry) {
        $authorId = $inquiry['author_id'];
        $inquirerId = $inquiry['inquirer_id'];
        $postId = $inquiry['post_id']; // Fetch the post_id from the inquiry

        if ($inquirerId && $authorId && $postId) {
            $checkChat = $dbConnection->prepare("
                SELECT id FROM chats WHERE inquirer_id = ? AND author_id = ?
            ");
            $checkChat->bind_param("ii", $inquirerId, $authorId);
            $checkChat->execute();
            $existingChat = $checkChat->get_result()->fetch_assoc();
            $checkChat->close();

            if (!$existingChat) {
                $createChat = $dbConnection->prepare("
                    INSERT INTO chats (inquirer_id, author_id, post_id) VALUES (?, ?, ?)
                ");
                if ($createChat === false) {
                    echo "Failed to prepare statement: " . $dbConnection->error;
                    exit;
                }
                $createChat->bind_param("iii", $inquirerId, $authorId, $postId); // Include post_id in the query
                $createChat->execute();

                if ($createChat->affected_rows > 0) {
                    $chatId = $createChat->insert_id;
                    $chatMessage = "Chat created with ID: {$chatId}";
                }
                $createChat->close();
            } else {
                $chatMessage = "Chat already exists with ID: {$existingChat['id']}";
            }

            $updateInquiry = $dbConnection->prepare("
                UPDATE inquiries SET status = 'approved' WHERE id = ?
            ");
            if ($updateInquiry === false) {
                echo "Failed to prepare query: " . $dbConnection->error;
                exit;
            }
            $updateInquiry->bind_param("i", $inquiryId);
            $updateInquiry->execute();
            $updateInquiry->close();

            // Output dialog for the combined message
            echo "
                <div id='chatDialog' style='display: block;'>
                    <div style='background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%;'>
                        <div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; text-align: center;'>
                            <h2>Inquiry Approved</h2>
                            <p>Inquiry has been approved and chat triggered. {$chatMessage}</p>
                            <div style='display: flex; justify-content: center; margin-top: 20px;'>
                                <button onclick='document.getElementById(\"chatDialog\").style.display=\"none\";' style='padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px;'>Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        } else {
            echo "Invalid inquirer or author ID.";
        }
    } else {
        echo "Inquiry not found.";
    }




} elseif ($action === 'generate_pdf' && isset($_GET['inquiry_id'])) {
    $inquiryId = $_GET['inquiry_id'];

    // Fetch the inquiry details with the necessary joins
    $stmt = $dbConnection->prepare("
        SELECT * 
        FROM inquiries
        INNER JOIN pet_adoption_inquiry ON inquiries.id = pet_adoption_inquiry.id
        INNER JOIN adoption_inquiry_details ON inquiries.id = adoption_inquiry_details.id
        INNER JOIN adoption_commitment_inquiry ON inquiries.id = adoption_commitment_inquiry.id
        WHERE inquiries.id = ?
    ");
    if ($stmt === false) {
        echo "Failed to prepare statement: " . $dbConnection->error;
        exit;
    }
    $stmt->bind_param("i", $inquiryId);
    $stmt->execute();
    $inquiryResult = $stmt->get_result();
    $inquiry = $inquiryResult->fetch_assoc();
    $stmt->close();

    if ($inquiry) {
        // Generate PDF if inquiry found
        generatePDF($inquiry);
    } else {
        echo "Inquiry not found.";
    }
    exit;
}

// Fetch inquiries for display
$stmt = $dbConnection->prepare("
    SELECT inquiries.id, inquiries.user_id, inquiries.status, user.name AS user_name, inquiries.post_id, post.user_id AS post_name 
    FROM inquiries 
    JOIN user ON inquiries.user_id = user.id 
    JOIN post ON inquiries.post_id = post.id
");
if ($stmt) {
    $stmt->execute();
    $inquiriesResult = $stmt->get_result();
    $inquiries = [];
    while ($row = $inquiriesResult->fetch_assoc()) {
        $inquiries[] = $row;
    }
    $stmt->close();
} else {
    echo "Failed to prepare the statement: " . $dbConnection->error;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/css/adminpdf.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Details</title>
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
                            <a href="/admin-adoption">Adoption Total Posts</a>
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
                <div class="table-container">
                    <div class="pending">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Inquirer ID</th>
                                    <th>Inquirer Name</th>
                                    <th>Post ID</th>
                                    <th>Author ID</th>
                                    <th>PDF</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inquiries as $inquiry): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($inquiry['user_id']); ?></td>
                                        <td><?php echo htmlspecialchars($inquiry['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($inquiry['post_id']); ?></td>
                                        <td><?php echo htmlspecialchars($inquiry['post_name']); ?></td>
                                        <td><a href="?action=generate_pdf&inquiry_id=<?php echo $inquiry['id']; ?>"
                                                target="_blank" class="view-pdf">View PDF</a></td>
                                        <td class="action-cell">
                                            <?php if ($inquiry['status'] === 'approved' || $inquiry['status'] === 'denied'): ?>
                                                <span class="disabled-btn">Approved or Denied</span>
                                            <?php else: ?>
                                                <a href="?action=approve&inquiry_id=<?php echo $inquiry['id']; ?>"
                                                    class="approve-btn">Approve</a>
                                                <a href="?action=deny&inquiry_id=<?php echo $inquiry['id']; ?>"
                                                    class="deny-btn">Deny</a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($inquiry['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                <p>Lorem ipsum dolor sit amet. Ex officiis molestias et sapiente<br> doloremque et dolores doloribus est
                    animi maiores. Ut fugiat <br> molestiae nam quia earum qui aliquid aliquid ab corrupti officiis.
                    Et<br> temporibus quia 33 incidunt adipisci ea deleniti vero 33<br> reprehenderit repellat.</p>


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

    <script src="/js/admin.js"></script>
</body>

</html>