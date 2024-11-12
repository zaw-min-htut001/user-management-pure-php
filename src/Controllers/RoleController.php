<?php

namespace App\Controllers;

require_once __DIR__ . '/../../config/db.php';

use App\Models\Role;
use App\Models\Permission;

class RoleController {
    public function listRoles() {
        global $pdo;  
        $roleModel = new Role($pdo);  
        $roles = $roleModel->getAllRoles();
        require __DIR__ . '/../../src/views/users/create.php';
    }

    public function getAllRoles() {
        global $pdo;  
        $roleModel = new Role($pdo); 
        $roles = $roleModel->getAllRoles();
        require __DIR__ . '/../../src/views/roles/role-table.php';
    }

    public function storeRole($roleName, $permissions) {
        global $pdo;  
        $roleModel = new Role($pdo);
        $roleModel->storeRole($roleName, $permissions);
    }
    
    public function deleteRole($id)
    {
        global $pdo;  
        $roleModel = new Role($pdo);  
        $roleModel->deleteRole($id);
        header("Location: /roles");
        exit;
    }

    public function editRole($roleId) {
        global $pdo;
        $roleModel = new Role($pdo);

        $role = $roleModel->getRoleById($roleId);
        
        $permissionsModel = new Permission($pdo);
        $rolesModel = new Role($pdo);

        $permissions = $permissionsModel->getAllPermissionsToEdit();

        $userPermissions = $rolesModel->getAdminUserPermissions($roleId);

        require_once BASE_PATH . '/src/views/roles/edit.php';

    }
    
    public function updateRole($roleId, $roleName, $permissions) {
        global $pdo;
        $roleModel = new Role($pdo);
        $roleModel->updateRole($roleId, $roleName, $permissions);
    }
}
?>
