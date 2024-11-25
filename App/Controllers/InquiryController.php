<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Inquiry;

class InquiryController {
    private $inquiryModel;

    public function __construct(Inquiry $inquiryModel) {
        $this->inquiryModel = $inquiryModel;
    }

    public function submitAdoptionInquiry() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve the post_id from the request
            $post_id = $_POST['post_id'];  // Get post_id from POST data
            $user_id = $_POST['user_id'];  // Get user_id from POST data
            
            // Validate input
            $name = trim($_POST['name']);
            $occupation = trim($_POST['occupation']);
            $address = trim($_POST['address']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $message = trim($_POST['message']);
            
            // Simple validation
            if (empty($name) || empty($email) || empty($message)) {
                echo "Please fill in all required fields.";
                return; // Stop execution if validation fails
            }

           
        }
    }
}
