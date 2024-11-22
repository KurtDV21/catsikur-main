<?php
use App\Core\Database;
use App\Models\User;

require_once __DIR__ . '/../../../vendor/autoload.php';

$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
if (!$token) {
    die("Token is missing.");
}

$token_hash = hash("sha256", $token);

$database = new Database();
$dbConnection = $database->connect();
$userModel = new User($dbConnection);

$sql = "SELECT * FROM user WHERE reset_token_hash = ?";
$stmt = $dbConnection->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found.");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Reset Password</h1>
    <form action="process-reset-password" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        
        <label for="password">New password</label>
        <input type="password" name="password" id="password" required>

        <label for="password_confirmation">Confirm password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>

        <button>Confirm</button>
    </form>
</body>
</html>
