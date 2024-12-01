<?php
session_start();

// Handle typing status update (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['typing_status'])) {
    $isTyping = $_POST['typing_status'] === 'true' ? true : false;
    $_SESSION['is_typing'] = $isTyping;
    echo json_encode(['status' => 'success']);
    exit;
}

// Handle typing status check (GET) 
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check_typing'])) {
    $isTyping = isset($_SESSION['is_typing']) ? $_SESSION['is_typing'] : false;
    echo json_encode(['is_typing' => $isTyping]);
    exit;
}


?>
