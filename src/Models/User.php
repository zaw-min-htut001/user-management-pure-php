<?php

namespace App\Models;

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin_users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query(
            "SELECT admin_users.*, roles.name as role_name
            FROM admin_users 
            JOIN roles ON admin_users.role_id = roles.id"
        );
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function storeUser($username, $password, $role_id, $name, $email, $phone, $gender, $is_active, $address) {
        $stmt = $this->pdo->prepare("INSERT INTO admin_users (username, password, role_id, name, email, phone, gender, is_active, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, password_hash($password, PASSWORD_BCRYPT), $role_id, $name, $email, $phone, $gender, $is_active, $address ]);
    }

    public function getUser($id) {
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM admin_users 
             WHERE id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Use fetch() to get a single user instead of fetchAll()
    }

    public function updateUser($id, $username, $password, $role_id, $name, $email, $phone, $gender, $is_active, $address) {
        $sql = "UPDATE admin_users 
                SET username = :username, role_id = :role_id, name = :name, email = :email, phone = :phone, 
                    gender = :gender, is_active = :is_active, address = :address";
        
        if (!empty($password)) {
            $sql .= ", password = :password";  
        }
    
        $sql .= " WHERE id = :id";
    
        $params = [
            ':id' => $id,
            ':username' => $username,
            ':role_id' => $role_id,
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':gender' => $gender,
            ':is_active' => $is_active,
            ':address' => $address,
        ];
    
        if (!empty($password)) {
            $params[':password'] = password_hash($password, PASSWORD_BCRYPT);
        }
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }
    

    public function showUser($id) {
        $stmt = $this->pdo->prepare(
            "SELECT admin_users.*, roles.name AS role_name
            FROM admin_users 
            JOIN roles ON admin_users.role_id = roles.id
            WHERE admin_users.id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deleteUser($userId) {
        $stmt = $this->pdo->prepare('DELETE FROM admin_users WHERE id = :id');
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }
    
}

?>
