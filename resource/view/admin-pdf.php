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
    header('Location: /login');
    exit;
}

function generatePDF($inquiry) {
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

if (isset($_GET['action']) && $_GET['action'] === 'generate_pdf' && isset($_GET['inquiry_id'])) {
    $inquiryId = $_GET['inquiry_id'];

    $stmt = $dbConnection->prepare("
        SELECT * 
        FROM inquiries
        INNER JOIN pet_adoption_inquiry ON inquiries.id = pet_adoption_inquiry.id
        INNER JOIN adoption_inquiry_details ON inquiries.id = adoption_inquiry_details.id
        INNER JOIN adoption_commitment_inquiry ON inquiries.id = adoption_commitment_inquiry.id
        WHERE inquiries.id = ?
    ");
    if ($stmt) {
        $stmt->bind_param("i", $inquiryId);
        $stmt->execute();
        $inquiryResult = $stmt->get_result();
        $inquiry = $inquiryResult->fetch_assoc();
        $stmt->close();

        if ($inquiry) {
            generatePDF($inquiry);
        } else {
            echo "Inquiry not found.";
        }
    } else {
        echo "Failed to prepare the statement: " . $dbConnection->error;
    }
    exit;
}

$stmt = $dbConnection->prepare("SELECT inquiries.id, inquiries.user_id, user.name AS user_name, inquiries.post_id, post.user_id AS post_name FROM inquiries JOIN user ON inquiries.user_id = user.id JOIN post ON inquiries.post_id = post.id");
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
    <link rel="stylesheet" href="/css/adminadoption.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Details</title>
</head>
<body>
<div class="container-admin">
    <div class="background-card">
        <div class="header">
            <div class="image-placeholder">
                <img src="<?php echo htmlspecialchars($user['profile_image_path']); ?>" alt="Admin Profile">
                <h2>ADMIN</h2>
            </div>
        </div>
        <div class="sherwin">
                <div class="container">
                    <div onclick="location.href='/admin'" class="approval-card">
                        Approval
                    </div>  
                    <div onclick="location.href='/admin-adoption'" class="adoption-posts">
                        Adoption Total Posts
                    </div>
                    <div class="rescue-posts">
                        Rescue Total Posts
                    </div>
                    <div onclick="location.href='/admin-restrict'" class="rescue-posts">
                        Restrict User
                    </div>
                    <div onclick="location.href='/admin-pdf'" class="rescue-posts">
                        Form Approval
                    </div>
                </div>
            <div class="pending">
                <table id="inquiryTable" class="table">
                    <thead>
                        <tr>
                            <th>Inquirer ID</th>
                            <th>Inquirer Name</th>
                            <th>Post ID</th>
                            <th>Author ID</th>
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
                                <td><a href="?action=generate_pdf&inquiry_id=<?php echo $inquiry['id']; ?>" target="_blank">View PDF</a></td>
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
