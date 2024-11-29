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

if ($action === 'approve' && isset($_GET['inquiry_id'])) {
    $inquiryId = $_GET['inquiry_id'];

    // Fetch inquiry details
    $query = $dbConnection->prepare("
        SELECT inquiries.user_id AS inquirer_id, post.user_id AS author_id, inquiries.post_id
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
            // Check if chat exists between the inquirer and the author
            $checkChat = $dbConnection->prepare("
                SELECT id FROM chats WHERE inquirer_id = ? AND author_id = ?
            ");
            $checkChat->bind_param("ii", $inquirerId, $authorId);
            $checkChat->execute();
            $existingChat = $checkChat->get_result()->fetch_assoc();
            $checkChat->close();

            if (!$existingChat) {
                // Log the parameters before attempting to insert
                echo "Attempting to create chat with inquirer_id: $inquirerId, author_id: $authorId, post_id: $postId.<br>";

                // Create a new chat
                $createChat = $dbConnection->prepare("
                    INSERT INTO chats (inquirer_id, author_id, post_id) VALUES (?, ?, ?)
                ");
                if ($createChat === false) {
                    echo "Failed to prepare statement: " . $dbConnection->error;
                    exit;
                }
                $createChat->bind_param("iii", $inquirerId, $authorId, $postId); // Include post_id in the query
                $createChat->execute();

                // Check if the chat was created successfully
                if ($createChat->affected_rows > 0) {
                    // Get the chat ID
                    $chatId = $createChat->insert_id; // Get the last inserted chat_id
                    echo "Chat created with ID: " . $chatId;
                } else {
                    echo "Failed to create chat. No rows affected. MySQL Error: " . $dbConnection->error;
                }

                $createChat->close();
            } else {
                echo "Chat already exists with ID: " . $existingChat['id'];
            }

            // Update inquiry status to 'approved'
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

            echo "Inquiry approved and chat triggered.";
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
    SELECT inquiries.id, inquiries.user_id, user.name AS user_name, inquiries.post_id, post.user_id AS post_name 
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
                            <a href="">Rescue Total Posts</a>
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
                <d<div class="pending">
    <table class="table">
        <thead>
            <tr>
                <th>Inquirer ID</th>
                <th>Inquirer Name</th>
                <th>Post ID</th>
                <th>Author ID</th>
                <th>PDF</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inquiries as $inquiry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inquiry['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['post_id']); ?></td>
                    <td><?php echo htmlspecialchars($inquiry['post_name']); ?></td>
                    <td><a href="?action=generate_pdf&inquiry_id=<?php echo $inquiry['id']; ?>" target="_blank" class="view-pdf">View PDF</a></td>
                    <td><a href="?action=approve&inquiry_id=<?php echo $inquiry['id']; ?>" class="approve-btn">Approve</a></td>
                    <td><a href="?action=deny&inquiry_id=<?php echo $inquiry['id']; ?>" class="deny-btn">Deny</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
            </div>
        </div>
    </div>
    <script src="/js/admin.js"></script>
</body>

</html>
