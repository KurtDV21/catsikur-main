<?php

namespace App\Models;

use mysqli;

class ApprovedPostsModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getUserApprovedPosts() {
        $sql = "SELECT post.id, post.cat_name, post.age, post.location, post.picture, post.sample_pictures 
                FROM post 
                INNER JOIN user ON post.user_id = user.id 
                WHERE post.approval = 'approved'";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Decode sample_pictures if stored as JSON
        foreach ($result as $key => $post) {
            if (!empty($post['sample_pictures'])) {
                // Remove any escaped slashes from the file paths
                $result[$key]['sample_pictures'] = json_decode($post['sample_pictures'], true);
            }
        }
        
        return $result;
    }
}
