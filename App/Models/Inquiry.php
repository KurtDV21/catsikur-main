<?php

namespace App\Models;

use mysqli;

class Inquiry {
    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    // Method to insert a new inquiry
    public function createAdoptionInquiry($user_id, $post_id, $name, $occupation, $address, $email, $phone, $message) {
        // Prepare the SQL statement
        $query = "INSERT INTO inquiries (user_id, post_id, name, occupation, address, email, phone, message) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query); // Use $this->mysqli

        if (!$stmt) {
            // Handle errors
            die('Query preparation failed: ' . $this->mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param('iissssss', $user_id, $post_id, $name, $occupation, $address, $email, $phone, $message);

        // Execute the statement and return the result
        $result = $stmt->execute();

        // Check for execution errors
        if (!$result) {
            die('Execution failed: ' . $stmt->error);
        }

        return $result;
    }

    // Method to fetch all inquiries
    public function getAllInquiries() {
        $sql = "SELECT * FROM inquiries";
        $result = $this->mysqli->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Method to find inquiry by ID
    public function findInquiryById($id) {
        $sql = "SELECT * FROM inquiries WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die('Query preparation failed: ' . $this->mysqli->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
