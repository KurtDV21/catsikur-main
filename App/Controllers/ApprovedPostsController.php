<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\ApprovedPostsModel;

class ApprovedPostsController {

    private $approvedPostsModel;

    public function __construct(ApprovedPostsModel $approvedPostsModel) {
        $this->approvedPostsModel = $approvedPostsModel;
    }

    public function showApprovedPosts() {
        return $this->approvedPostsModel->getUserApprovedPosts();
    }
}
