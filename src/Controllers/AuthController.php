<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Role.php'; 

use App\Models\User;
use App\Models\Role;

class AuthController {
    
    public function login($username, $password) {
        global $pdo;  
        $userModel = new User($pdo);
        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id']; 
            $_SESSION['username'] = $user['username']; 

            $permissions = $this->getPermissionsByRoleId($user['role_id']);
            $_SESSION['permissions'] = $permissions; 

            header("Location: /dashboard"); 
            exit;
        } else {
            die('Invalid');
        }
    }

    public function getPermissionsByRoleId($roleId) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.name 
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            WHERE rp.role_id = :role_id
        ");
        $stmt->execute(['role_id' => $roleId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN); 
    }

    public function logout() {
        session_unset();  // Unset session variables
        session_destroy();  // Destroy session
    
        header("Location: /");  // Redirect after logout
        exit;
    }

}

