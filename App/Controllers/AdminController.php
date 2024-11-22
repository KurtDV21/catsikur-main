<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Admin;

class AdminController {
    private $adminModel;

    public function __construct(Admin $adminModel) {
        $this->adminModel = $adminModel; // Corrected here to use $this->adminModel
    }

    // Register a new admin
    public function register($role, $name, $email, $password) {
        // Ensure the 'role' is set to 'admin' in the creation process
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Always hash the password
        $admin = $this->adminModel->create($role, $name, $email, $hashedPassword);

        if ($admin) {
            return ['success' => true, 'message' => 'Admin Registration successful!'];
        }

        return ['success' => false, 'message' => 'Admin registration failed.'];
    }

    // Admin login

    public function login($email, $password) {
        $admin = $this->adminModel->findByEmail($email);
        
        if (!$admin) {
            echo "No account found for the email.";
            return null;
        }
        
        if ($admin['account_activation_hash'] !== NULL) {
            echo "Account not activated.";
            return null;
        }
        
        if ($this->adminModel->verifyPassword($admin, $password)) {
            return $admin;
        } else {
            return null;
        }
    }
    

    public function activate($token_hash) {
        return $this->adminModel->findByActivationToken($token_hash);
    }

    public function confirmAccount($userId) {
        return $this->adminModel->activateAccount($userId);
    }
}
