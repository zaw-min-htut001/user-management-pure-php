<?php

namespace App\Models;

class Permission {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllPermissions() {
        $stmt = $this->pdo->query(
            "SELECT 
                features.id AS feature_id, 
                features.name AS feature_name, 
                permissions.name AS permission_name 
            FROM 
                features
            JOIN 
                permissions ON features.id = permissions.feature_id"
        );

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllPermissionsToEdit() {
        $stmt = $this->pdo->query(
            "SELECT 
                features.id AS feature_id, 
                features.name AS feature_name, 
                permissions.id AS permission_id, 
                permissions.name AS permission_name 
            FROM 
                features
            JOIN 
                permissions ON features.id = permissions.feature_id
            JOIN 
                role_permissions AS rp ON rp.permission_id = permissions.id"
        );
    
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $features = [];
        
        foreach ($data as $entry) {
            $feature_id = $entry['feature_id'];
            $feature_name = $entry['feature_name'];
            $permission_id = $entry['permission_id'];
            $permission_name = $entry['permission_name'];
        
            if (!isset($features[$feature_id])) {
                $features[$feature_id] = [
                    'feature_name' => $feature_name,
                    'permissions' => []  
                ];
            }
        
            $features[$feature_id]['permissions'][$permission_id] = [
                'permission_id' => $permission_id,
                'name' => $permission_name,
            ];
        }
        
        return $features;
    }
    

    
}

?>
