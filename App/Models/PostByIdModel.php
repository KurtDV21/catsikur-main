<?php

namespace App\Models;

use mysqli;

class PostByIdModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getPostById($post_id) {
        $sql = "SELECT post.cat_name,
            post.age,
            post.location,
            post.gender,
            post.color,
            post.picture,
            post.sample_pictures,
            post.Description,
            post.post_type
                FROM post 
                WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();

         // Decode the sample_pictures if it's stored as JSON
    if (isset($result['sample_pictures'])) {
        $result['sample_pictures'] = json_decode($result['sample_pictures'], true);  // Convert JSON to array
    }
    
    return $result;
    }
}
