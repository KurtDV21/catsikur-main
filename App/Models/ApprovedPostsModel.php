<?php

namespace App\Models;

use mysqli;

class ApprovedPostsModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getUserApprovedPosts() {
        $sql = "SELECT post.id, post.cat_name, post.age, post.location, post.picture, post.color
                FROM post 
                INNER JOIN user ON post.user_id = user.id 
                WHERE post.approval = 'approved'";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostsByUser($userId) {
        $sql = "SELECT post.id, post.cat_name, post.age, post.location, post.picture, post.color
                FROM post 
                INNER JOIN user ON post.user_id = user.id 
                WHERE post.user_id = ? AND post.approval = 'approved'";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    
}
