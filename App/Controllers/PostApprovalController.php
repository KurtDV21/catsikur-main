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
            $postId = $_POST['post_id'] ?? null;
            $action = $_POST['action'] ?? null;
    
            if (!$postId || !$action) {
                echo json_encode(['success' => false, 'message' => 'Invalid request parameters.']);
                exit;
            }
    
            $approvalStatus = ($action === 'approve') ? 'approved' : 'denied';
            $postStatus = ($action === 'approve') ? 'available' : 'denied';
    
            if ($this->postApprovalModel->updateApproval($postId, $approvalStatus, $postStatus)) {
                echo json_encode(['success' => true, 'message' => 'Post updated successfully.']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update post status.']);
                exit;
            }
        }
    
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        exit;
    }
    
    }
