<?php

namespace App\Models;

use mysqli;

class Inquiry {
    private $mysqli;
    private $table = 'inquiries';

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }


    // Store temporary data in session
    public function storeTemporaryData($formData) {
        session_start();
        $_SESSION['form_data'] = $formData;
    }

    // Retrieve temporarily stored data from session
    public function getTemporaryData() {
        session_start();
        return isset($_SESSION['form_data']) ? $_SESSION['form_data'] : null;
    }

    // Clear temporary data from session
    public function clearTemporaryData() {
        session_start();
        unset($_SESSION['form_data']);
    }

    // Method to save data to the database (for when the final form is submitted)
    public function saveInquiry($formData) {
        $stmt = $this->mysqli->prepare("INSERT INTO {$this->table} 
        (user_id, post_id, name, email, phone, address, age, guardian_details, company_industry, facebook, residence, residence_other, housing_status, household_agreement) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param(
            'iissssssssssss', 
            $formData['user_id'], 
            $formData['post_id'], 
            $formData['name'], 
            $formData['email'], 
            $formData['phone'], 
            $formData['address'], 
            $formData['age'], 
            $formData['guardian_details'], 
            $formData['company_industry'], 
            $formData['facebook'], 
            $formData['residence'], 
            $formData['residence_other'], 
            $formData['housing_status'], 
            $formData['household_agreement']
        );

        // Execute the query and return the result
        return $stmt->execute();
    }

    // // Method to insert a new inquiry
    // public function createAdoptionInquiry($user_id, $post_id, $name, $occupation, $address, $email, $phone, $message) {
    //     // Prepare the SQL statement
    //     $query = "INSERT INTO inquiries (user_id, post_id, name, occupation, address, email, phone, message) 
    //               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = $this->mysqli->prepare($query); // Use $this->mysqli

    //     if (!$stmt) {
    //         // Handle errors
    //         die('Query preparation failed: ' . $this->mysqli->error);
    //     }

    //     // Bind parameters
    //     $stmt->bind_param('iissssss', $user_id, $post_id, $name, $occupation, $address, $email, $phone, $message);

    //     // Execute the statement and return the result
    //     $result = $stmt->execute();

    //     // Check for execution errors
    //     if (!$result) {
    //         die('Execution failed: ' . $stmt->error);
    //     }

    //     return $result;
    // }

    // // Method to fetch all inquiries
    // public function getAllInquiries() {
    //     $sql = "SELECT * FROM inquiries";
    //     $result = $this->mysqli->query($sql);
    //     return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    // }

    // // Method to find inquiry by ID
    // public function findInquiryById($id) {
    //     $sql = "SELECT * FROM inquiries WHERE id = ?";
    //     $stmt = $this->mysqli->prepare($sql);
    //     if (!$stmt) {
    //         die('Query preparation failed: ' . $this->mysqli->error);
    //     }

    //     $stmt->bind_param('i', $id);
    //     $stmt->execute();
        
    //     // Fetch the result
    //     $result = $stmt->get_result();
    //     return $result->fetch_assoc();
    // }
}
