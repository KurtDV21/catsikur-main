<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\PostApprovalModel;

class PostApprovalController {

    private $postApprovalModel;

    public function __construct(PostApprovalModel $postApprovalModel) {
        $this->postApprovalModel = $postApprovalModel;
    }

    public function updateApproval() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = $_POST['post_id'];
            $action = $_POST['action']; 
            
            $approvalStatus = ($action === 'approve') ? 'approved' : 'denied';
            $postStatus = ($action === 'available') ? 'available' : 'denied';
            
            if ($this->postApprovalModel->updateApproval($postId, $approvalStatus, $postStatus)) {
                header("location:/admin");
                exit;
            } else {
                echo "Failed to update approval status.";
            }
        }
    }
}
