<?php

namespace App\Controllers;

class PostFilterController {

    private $postFilterModel;

    public function __construct($postFilterModel) {
        $this->postFilterModel = $postFilterModel;
    }

    public function getFilteredPosts() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $color = $data['color'] ?? null;
            $gender = $data['gender'] ?? null;

            $posts = $this->postFilterModel->filterPosts($color, $gender);
            echo json_encode($posts);
        } else {
            echo json_encode(['error' => 'Invalid request method.']);
        }
    }
}
