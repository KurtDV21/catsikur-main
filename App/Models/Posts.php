<?php
namespace App\Models;

use mysqli;

class Posts {
    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    // Get posts with pagination
    
    public function getPosts($limit, $offset) {
        $sql = "SELECT post.id, post.cat_name, post.location, post.picture, user.name, user.profile_image_path 
                FROM post 
                INNER JOIN user ON post.user_id = user.id 
                WHERE post.approval NOT IN ('approved', 'denied') 
                LIMIT ? OFFSET ?";

        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getTotalPosts() {
        $sql = "SELECT COUNT(*) AS total FROM post WHERE approval NOT IN ('approved', 'denied')";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
}