<?php

require_once __DIR__ . '/vendor/autoload.php';

$dsn = 'mysql:host=localhost;dbname=user-management';
$username = 'smth';
$password = 'smth';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully to the database.\n";

    // 1. Seed Features 
    $features = [
        ['name' => 'User'],
        ['name' => 'Role'],
        ['name' => 'Product']
    ];

    foreach ($features as $feature) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM features WHERE name = :name");
        $stmt->execute(['name' => $feature['name']]);
        $featureExists = $stmt->fetchColumn() > 0;

        if (!$featureExists) {
            $stmt = $db->prepare("INSERT INTO features (name) VALUES (:name)");
            $stmt->execute($feature);
            echo "Seeded feature: {$feature['name']}\n";
        } else {
            echo "Feature {$feature['name']} already exists. Skipping insert.\n";
        }
    }

    echo "Seeded features successfully.\n";

    // 2. Seed Permissions 
    $crudOperations = ['create', 'read', 'update', 'delete'];

    $stmt = $db->query("SELECT id, name FROM features");
    $features = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($features as $feature) {
        foreach ($crudOperations as $operation) {
            $permissionName = strtolower(str_replace(' ', '_', $feature['name'])). '_' .$operation;

            // Check if the permission already exists
            $stmt = $db->prepare("SELECT COUNT(*) FROM permissions WHERE name = :name AND feature_id = :feature_id");
            $stmt->execute(['name' => $permissionName, 'feature_id' => $feature['id']]);
            $permissionExists = $stmt->fetchColumn() > 0;

            if (!$permissionExists) {
                // Insert permission with associated feature_id into permissions table
                $stmt = $db->prepare("INSERT INTO permissions (name, feature_id) VALUES (:name, :feature_id)");
                $stmt->execute([
                    'name' => $permissionName,
                    'feature_id' => $feature['id']
                ]);
                echo "Seeded permission: $permissionName for feature: {$feature['name']}\n";
            } else {
                echo "Permission $permissionName already exists for feature: {$feature['name']}. Skipping insert.\n";
            }
        }
    }

    echo "All CRUD permissions seeded successfully.\n";

    // 3. Seed Roles 
    $roles = [
        ['name' => 'Admin'],
        ['name' => 'Editor'],
        ['name' => 'Viewer']
    ];

    foreach ($roles as $role) {
        // Check if role already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM roles WHERE name = :name");
        $stmt->execute(['name' => $role['name']]);
        $roleExists = $stmt->fetchColumn() > 0;

        if (!$roleExists) {
            $stmt = $db->prepare("INSERT INTO roles (name) VALUES (:name)");
            $stmt->execute($role);
            echo "Seeded role: {$role['name']}\n";
        } else {
            echo "Role {$role['name']} already exists. Skipping insert.\n";
        }
    }

    // Fetch role IDs
    $stmt = $db->prepare("SELECT id FROM roles WHERE name = :name");
    $stmt->execute(['name' => 'Admin']);
    $adminRoleId = $stmt->fetchColumn();

    $users = [
        [
            'name' => 'Admin User',
            'username' => 'admin',
            'role_id' => $adminRoleId,  
            'phone' => '1234567890',
            'address' => '123 Admin St, City, Country',
            'is_active' => 1,
            'gender' => 'Male',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'email' => 'admin@example.com'
        ]
    ];

    foreach ($users as $user) {
        // Check if user already exists by unique username or email
        $stmt = $db->prepare("SELECT COUNT(*) FROM admin_users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $user['username'], 'email' => $user['email']]);
        $userExists = $stmt->fetchColumn() > 0;

        if (!$userExists) {
            $stmt = $db->prepare(
                "INSERT INTO admin_users 
                (name, username, role_id, phone, address, is_active, gender, password, email) 
                VALUES 
                (:name, :username, :role_id, :phone, :address, :is_active, :gender, :password, :email)
            ");
            $stmt->execute($user);
            echo "Seeded user: {$user['username']}\n";
        } else {
            echo "User {$user['username']} or email {$user['email']} already exists. Skipping insert.\n";
        }
    }
    
     // 4. Assign All Permissions to Admin Role
     $stmt = $db->query("SELECT id FROM permissions");
     $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
 
     foreach ($permissions as $permissionId) {
         $stmt = $db->prepare("SELECT COUNT(*) FROM role_permissions WHERE role_id = :role_id AND permission_id = :permission_id");
         $stmt->execute(['role_id' => $adminRoleId, 'permission_id' => $permissionId]);
         if ($stmt->fetchColumn() == 0) {
             $stmt = $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
             $stmt->execute(['role_id' => $adminRoleId, 'permission_id' => $permissionId]);
             echo "Assigned permission ID $permissionId to Admin role.\n";
         }
     }
 
     echo "All permissions successfully assigned to Admin role.\n";

    echo "Data seeding completed without duplicates.\n";

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
