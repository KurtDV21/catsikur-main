<?php

namespace App\Models;

use mysqli;

class PostApprovalModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function updateApproval($postId, $status,  $postStatus) {
        $sql = "UPDATE post SET approval = ?, post_status = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssi", $status, $postStatus, $postId); // "si" for string (status) and integer (postId)
        return $stmt->execute();
    }
}
