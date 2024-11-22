<?php
use App\Core\Database;
use App\Models\User;

require_once __DIR__ . '/../../../vendor/autoload.php';

if (isset($_POST["token"])) {
    $token = $_POST["token"];
} else {
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

$is_invalid = false;
$error_message = "";

if ($user === null) {
    $is_invalid = true; 
    $error_message = "Token not found.";
}

if (!$is_invalid && strtotime($user["reset_token_expires_at"]) <= time()) {
    $is_invalid = true; 
    $error_message = "Token has expired.";
}

if (!$is_invalid && strlen($_POST["password"]) < 8) {
    $is_invalid = true; 
    $error_message = "Password must be at least 8 characters.";
}

if (!$is_invalid && !preg_match("/[a-z]/i", $_POST["password"])) {
    $is_invalid = true; 
    $error_message = "Password must contain at least one letter.";
}

if (!$is_invalid && !preg_match("/[0-9]/", $_POST["password"])) {
    $is_invalid = true; 
    $error_message = "Password must contain at least one number.";
}

if (!$is_invalid && $_POST["password"] !== $_POST["password_confirmation"]) {
    $is_invalid = true; 
    $error_message = "Passwords must match.";
}

if (!$is_invalid) {
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "UPDATE user SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";
    $stmt = $dbConnection->prepare($sql);
    $stmt->bind_param("ss", $password_hash, $user["id"]);

    if ($stmt->execute()) {
        header("Location: /changepass-success");
        exit;
    } else {
        echo "Error updating password. Please try again later.";
    }
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
    <form method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        
        <label for="password">New password</label>
        <input type="password" name="password" id="password" required>

        <label for="password_confirmation">Confirm password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>

        <button>Confirm</button>

        <?php if ($is_invalid): ?> 
            <p style="color: red;"><em><?= htmlspecialchars($error_message) ?></em></p> 
        <?php endif ?>
    </form>
</body>
</html>
