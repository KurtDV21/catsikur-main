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

$chatId = $_GET['chat_id'] ?? null;

if (!$chatId) {
    die('Chat ID is missing.');
}

$stmt = $dbConnection->prepare("
    SELECT m.id, m.message, m.created_at, u.name AS sender_name
    FROM chat_messages m
    JOIN user u ON m.sender_id = u.id
    WHERE m.chat_id = ?
    ORDER BY m.created_at ASC
");
$stmt->bind_param("i", $chatId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    // Remove newline and carriage return characters
    $cleanMessage = preg_replace('/\r|\n/', '', $row['message']);
    $row['message'] = $cleanMessage;

    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
exit;
?>
