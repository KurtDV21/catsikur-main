<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\PostFilterController;
use App\Models\PostFilterModel;
use App\Core\Database;

try {
    $database = new Database();
    $dbConnection = $database->connect();

    if (!$dbConnection) {
        throw new Exception('Database connection failed.');
    }

    $postFilterModel = new PostFilterModel($dbConnection);
    $postFilterController = new PostFilterController($postFilterModel);

    $postFilterController->getFilteredPosts();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['error' => 'An unexpected error occurred.']);
}
