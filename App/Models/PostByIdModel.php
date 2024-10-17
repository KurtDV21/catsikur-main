<?php

namespace App\Models;

use mysqli;

class PostByIdModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getPostById($post_id) {
        $sql = "SELECT post.cat_name, post.age, post.location, post.gender, post.color, post.picture 
                FROM post 
                WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
