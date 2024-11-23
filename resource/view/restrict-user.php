<?php
use App\Core\Database;
use App\Models\User;

require_once __DIR__ . '/../../vendor/autoload.php';

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'];
$isRestricted = $data['is_restricted'];

$sql = "UPDATE user SET is_restricted = ? WHERE id = ?";
$stmt = $dbConnection->prepare($sql);
$stmt->bind_param("ii", $isRestricted, $userId);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
?>
