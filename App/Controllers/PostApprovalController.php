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
            $action = $_POST['action'];   // Get action (approve or deny)
            
            // Set approval status based on the action
            $approvalStatus = ($action === 'approve') ? 'approved' : 'denied';

            // Call the updateApproval method to update the approval status
            if ($this->postApprovalModel->updateApproval($postId, $approvalStatus)) {
                // Redirect back to the approval page (or show a success message)
                header("location:/admin");
                exit;
            } else {
                echo "Failed to update approval status.";
            }
        }
    }
}
