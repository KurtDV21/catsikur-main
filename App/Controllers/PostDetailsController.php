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
                $post['cat_name'] = htmlspecialchars($post['cat_name']);
                $post['age'] = htmlspecialchars($post['age']);
                $post['location'] = htmlspecialchars($post['location']);
                $post['gender'] = htmlspecialchars($post['gender']);
                $post['color'] = htmlspecialchars($post['color']);
                $post['picture'] = htmlspecialchars($post['picture']);
                $post['Description'] = htmlspecialchars($post['Description']); // Corrected to use 'Description'                
                $post['post_type'] = htmlspecialchars($post['post_type']);
                $post['post_status'] = htmlspecialchars($post['post_status']);



                // Decode the JSON string into an array
                $post['sample_pictures'] = isset($post['sample_pictures']) ? json_decode($post['sample_pictures'], true) : [];
                
                // Check for JSON errors
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo "Error decoding sample pictures: " . json_last_error_msg();
                    $post['sample_pictures'] = [];
                }

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
?>
