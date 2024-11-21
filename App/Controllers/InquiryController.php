<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Inquiry;

class InquiryController {
    private $inquiryModel;

    public function __construct(Inquiry $inquiryModel) {
        $this->inquiryModel = $inquiryModel;
    }

    // Process the first part of the form submission
    public function storeTemporaryData($formData) {
        $this->inquiryModel->storeTemporaryData($formData);
    }

    // Process the second part of the form and save it to the database
    public function submitFormPart2() {
        // Retrieve temporarily stored form data from the model
        $formData = $this->inquiryModel->getTemporaryData();

        if ($formData) {
            // Save the data to the database
            $this->inquiryModel->saveInquiry($formData);

            // Clear the temporary data after saving
            $this->inquiryModel->clearTemporaryData();

            // Redirect to a success page or thank you page
            header('Location: /inquiry-success');
            exit;
        } else {
            // If no form data is in session, redirect back to the form
            header('Location: /form-page');
            exit;
        }
    }




    // public function submitAdoptionInquiry() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         // Retrieve the post_id from the request
    //         $post_id = $_POST['post_id'];  // Get post_id from POST data
    //         $user_id = $_POST['user_id'];  // Get user_id from POST data
            
    //         // Validate input
    //         $name = trim($_POST['name']);
    //         $occupation = trim($_POST['occupation']);
    //         $address = trim($_POST['address']);
    //         $email = trim($_POST['email']);
    //         $phone = trim($_POST['phone']);
    //         $message = trim($_POST['message']);
            
    //         // Simple validation
    //         if (empty($name) || empty($email) || empty($message)) {
    //             echo "Please fill in all required fields.";
    //             return; // Stop execution if validation fails
    //         }

    //         // Call the model method to insert the data into the inquiries table
    //         if ($this->inquiryModel->createAdoptionInquiry($user_id, $post_id, $name, $occupation, $address, $email, $phone, $message)) {
    //             // Redirect to success page or show success message
    //             echo "Inquiry submitted successfully!";
    //             // Optionally: header('Location: success.php'); // Redirect to a success page
    //         } else {
    //             echo "Failed to submit inquiry.";
    //         }
    //     }
    // }
}
