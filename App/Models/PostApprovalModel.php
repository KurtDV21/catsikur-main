<?php

namespace App\Models;

use mysqli;

class PostApprovalModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function updateApproval($postId, $status) {
        $sql = "UPDATE post SET approval = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("si", $status, $postId); // "si" for string (status) and integer (postId)
        return $stmt->execute();
    }
}
