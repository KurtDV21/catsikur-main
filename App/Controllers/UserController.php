<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;

class UserController {
    private $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }

    public function register($role, $name, $email, $password) {
        // Create user in the database
        $user = $this->userModel->create($role, $name, $email, $password);

        if ($user) {
            return ['success' => true, 'message' => 'User Registration successful!'];
        }

        return ['success' => false, 'message' => 'User registration failed.'];
    }

    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);

        if ($user && $user['account_activation_hash'] === NULL && $this->userModel->verifyPassword($user, $password)) {
            return $user;
        } else {
            return null;
        }
    }

    public function activate($token_hash) {
        return $this->userModel->findByActivationToken($token_hash);
    }

    public function confirmAccount($userId) {
        return $this->userModel->activateAccount($userId);
    }

    public function emailExists($email) {
        return $this->userModel->emailExists($email);
    }
}
