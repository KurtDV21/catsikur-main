<?php
use App\Core\Database;

require_once __DIR__ . '/../../vendor/autoload.php';
session_start();

$database = new Database();
$dbConnection = $database->connect();

if (!isset($_SESSION['user_id'])) {
    header('Location: /loginto');
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['chat_id'], $data['message'])) {
    $chatId = (int) $data['chat_id'];
    $message = $dbConnection->real_escape_string($data['message']);

    $stmt = $dbConnection->prepare("
        INSERT INTO chat_messages (chat_id, sender_id, message, created_at) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("iis", $chatId, $userId, $message);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success']);
} else {    
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
exit;
?>
