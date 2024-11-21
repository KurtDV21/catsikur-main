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

        return $stmt->execute();
    }

   
}
