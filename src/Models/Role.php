<?php

namespace App\Models;

class Role {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPermissionsByRoleId($roleId) {
        $stmt = $this->pdo->prepare("SELECT p.name 
            FROM permissions p
            INNER JOIN role_permissions rp ON rp.permission_id = p.id
            WHERE rp.role_id = :role_id
        ");
        $stmt->execute(['role_id' => $roleId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN); // Returns an array of permission names
    }

    public function getAllRoles() {
        $stmt = $this->pdo->query(
            "SELECT * FROM roles"
        );
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function storeRole($roleName, $permissions) {
        try {
            // Begin transaction
            $this->pdo->beginTransaction();
    
            // Insert new role
            $stmt = $this->pdo->prepare("INSERT INTO roles (name) VALUES (:name)");
            $stmt->execute(['name' => $roleName]);
            $role_id = $this->pdo->lastInsertId(); 
    
            // Insert permissions for the role
            $stmt = $this->pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
    
            foreach ($permissions as $feature_id => $feature_permissions) {
                foreach ($feature_permissions as $perm_type => $checked) {
                    // Find permission_id for each feature and permission type
                    $perm_stmt = $this->pdo->prepare("SELECT id FROM permissions WHERE feature_id = :feature_id AND name = :permission_name");
                    $perm_stmt->execute(['feature_id' => $feature_id, 'permission_name' => $perm_type]);
                    $permission_id = $perm_stmt->fetchColumn();
    
                    if ($permission_id) {
                        // Link the role to the permission
                        $stmt->execute(['role_id' => $role_id, 'permission_id' => $permission_id]);
                    }
                }
            }
    
            // Commit the transaction
            $this->pdo->commit();
            $_SESSION['success'] = 'Role created successfully!';
            header('Location: /roles');
            exit;
        } catch (Exception $e) {
            // Rollback transaction if something goes wrong
            $this->pdo->rollBack();
            $_SESSION['errors'][] = 'Failed to create role: ' . $e->getMessage();
            header('Location: /roles');
            exit;
        }
    }

    public function deleteRole($id) {
        $stmt = $this->pdo->prepare('DELETE FROM roles WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }


    // Get a role by its ID
    public function getRoleById($roleId) {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateRole($roleId, $roleName, $permissions) {
        global $pdo;
    
        try {
            // Start the transaction
            $this->pdo->beginTransaction();
    
            // Update role name
            $stmt = $pdo->prepare("UPDATE roles SET name = :name WHERE id = :id");
            $stmt->execute(['name' => $roleName, 'id' => $roleId]);
    
            // Remove all old permissions
            $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = :role_id");
            $stmt->execute(['role_id' => $roleId]);
    
            // Prepare insert statement for new permissions
            $insertStmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");

            // Loop through the permissions array to add new permissions
            foreach ($permissions as $featureId => $permissionIds) {
                foreach ($permissionIds as $permissionId) {
                    // Insert each permission for this role
                    $insertStmt->execute(['role_id' => $roleId, 'permission_id' => $permissionId]);
                }
            }
    
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
        }
    }
    
    public function getAdminUserPermissions($roleId) {
        global $pdo;
    
        $stmt = $pdo->prepare("SELECT 
                                    rp.role_id, 
                                    rp.permission_id, 
                                    f.id AS feature_id,
                                    f.name AS feature_name,
                                    p.id AS permission_id,
                                    p.name AS permission_name
                                FROM role_permissions AS rp
                                JOIN permissions AS p ON rp.permission_id = p.id
                                JOIN features AS f ON p.feature_id = f.id
                                WHERE rp.role_id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
    
        // Step 3: Store the permissions in an array, categorized by feature
        $permissions = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $featureId = $row['feature_id'];
            $permissionName = $row['permission_id'];
    
            // Structure the result in a way that allows easy checking of permissions
            if (!isset($permissions[$featureId])) {
                $permissions[$featureId] = [];
            }
    
            // Assign permissions to the feature
            $permissions[$featureId][] = $permissionName;
        }
    
        return $permissions;
    }
    
    
}
?>
