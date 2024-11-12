<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/db.php';

use App\Models\Role;
use App\Models\User;

class UserController {
    public function listUsers() {
        global $pdo;  // Retrieve the global PDO instance
        $userModel = new User($pdo);  // Pass PDO to User
        $users = $userModel->getAllUsers();
        require __DIR__ . '/../../src/views/users/user-table.php';
    }


    public function storeUser($username, $password, $role_id, $name, $email, $phone, $gender, $is_active, $address) {
        global $pdo;  
        $userModel = new User($pdo);
        $userModel->storeUser($username, $password, $role_id, $name, $email, $phone, $gender, $is_active, $address);
        header("Location: /users");
        exit;
    }

    public function getUser($id)
    {
        global $pdo;  // Retrieve the global PDO instance
        $userModel = new User($pdo);  // Pass PDO to User
        $user = $userModel->getUser($id);

        $roleModel = new Role($pdo);  // Pass PDO to User
        $roles = $roleModel->getAllRoles();
        require __DIR__ . '/../../src/views/users/edit.php';
    }
    
    public function updateUser($data) {
        global $pdo;  
        $userModel = new User($pdo); 

        $userModel->updateUser(
            $data['id'],        // User ID
            $data['username'],  // Username
            $data['password'],  // Password
            $data['role_id'],   // Role ID
            $data['name'],      // Name
            $data['email'],     // Email
            $data['phone'],     // Phone
            $data['gender'],    // Gender
            isset($data['is_active']) ? 1 : 0, 
            $data['address']    // Address
        );
        header("Location: /users");
        exit;
    }

    public function showUser($id) {
        global $pdo;
        $userModel = new User($pdo);
        $user = $userModel->showUser($id);

        require_once BASE_PATH . '/src/views/users/show.php';
    }


    public function deleteUser($id)
    {
        global $pdo; 
        $userModel = new User($pdo);  
        $userModel->deleteUser($id);
        header("Location: /users");
        exit;
    }
}
?>
