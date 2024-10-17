<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\PostByIdModel;

class PostDetailsController {

    private $postByIdModel;

    public function __construct(PostByIdModel $postByIdModel) {
        $this->postByIdModel = $postByIdModel;
    }

    public function showSelectedPost() {
        $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

        if ($post_id > 0) {
            $post = $this->postByIdModel->getPostById($post_id);
        
            if ($post) {
                // Sanitize data
                $cat_name = htmlspecialchars($post['cat_name']);
                $age = htmlspecialchars($post['age']);
                $location = htmlspecialchars($post['location']);
                $picture = htmlspecialchars($post['picture']);
                return $post;
            } else {
                echo 'Post not found!';
                exit;
            }
        } else {
            echo 'Invalid post ID!';
            exit;
        }
    }
}
