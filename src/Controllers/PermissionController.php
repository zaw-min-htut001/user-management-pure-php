<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/db.php';

use App\Models\Role;
use App\Models\Permission;

class PermissionController {
    public function getAllPermissions() {
        global $pdo;  
        $permissionModel = new Permission($pdo);  
        $data = $permissionModel->getAllPermissions();

        $features = [];
        foreach ($data as $entry) {
            $feature_id = $entry['feature_id'];
            $feature_name = $entry['feature_name'];
            $permission_name = $entry['permission_name'];
            
            if (!isset($features[$feature_id])) {
                $features[$feature_id] = [
                    'feature_name' => $feature_name,
                    'permissions' => []
                ];
            }
        }    
        $features[$feature_id]['permissions'][] = $permission_name;
        
        require __DIR__ . '/../../src/views/roles/create.php';
    }

   
}
?>
