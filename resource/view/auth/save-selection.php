<?php
// Start the session to store data in the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])) {
    // Store the selected company in the session
    $_SESSION['lastSelectedCompany'] = $_POST['company'];
    
    // Optionally, you can echo a success message
    echo "Company selection saved: " . htmlspecialchars($_POST['company']);
}
?>
