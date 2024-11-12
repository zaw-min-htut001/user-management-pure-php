<?php

use App\Controllers\AuthController;
use App\Controllers\RoleController;
use App\Controllers\UserController;
use App\Controllers\PermissionController;

require_once BASE_PATH . '/config/db.php';
require_once BASE_PATH . '/middleware.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// ====== Authentication Routes ====== //
// Login and Logout routes
if ($requestUri === '/' && $requestMethod === 'GET') {
    $viewPath = BASE_PATH . '/src/views/auth/login.php';
    require_once $viewPath;
} 
elseif ($requestUri === '/login' && $requestMethod === 'POST') {
    $controller = new AuthController();
    $controller->login($_POST['username'], $_POST['password']);
} 
elseif ($requestUri === '/logout' && $requestMethod === 'POST') {
    $controller = new AuthController();
    $controller->logout();
}

// ==== //
// routes for dashboard //
elseif ($requestUri === '/dashboard') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    $viewPath = BASE_PATH . '/src/views/app.php';
    require_once $viewPath;
}

// ==== //
// routes for user and managements //
elseif ($requestUri === '/users') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    require_once BASE_PATH . '/src/Controllers/UserController.php';
    requirePermission('user_read');
    $controller = new UserController();
    $controller->listUsers();
}
elseif ($requestUri === '/users/create') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    require_once BASE_PATH . '/src/Controllers/RoleController.php';
    requirePermission('user_create');

    $controller = new RoleController();
    $controller->listRoles();
    $viewPath = BASE_PATH . '/src/views/users/create.php';  
    require_once $viewPath; 
}
elseif ($requestUri === '/user/store' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('user_create');
    $controller = new UserController($pdo);
    $controller->storeUser(
        $_POST['username'], 
        $_POST['password'], 
        $_POST['role_id'],
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['gender'],
        isset($_POST['is_active']) ? 1 : 0,
        $_POST['address'],
    );
} 
elseif ($requestUri === '/user/edit' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('user_update');

    require_once BASE_PATH . '/src/Controllers/UserController.php';
    if (isset($_POST['id'])) {
        $controller = new UserController($pdo);
        $controller->getUser($_POST['id']);
    }
    $viewPath = BASE_PATH . '/src/views/users/edit.php';  
    require_once $viewPath; 
}
elseif ($requestUri === '/user/update' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('user_create');

    require_once BASE_PATH . '/src/Controllers/UserController.php';
    if (isset($_POST['id'])) {
        $controller = new UserController($pdo);
        $controller->updateUser($_POST);  
    }
    $viewPath = BASE_PATH . '/src/views/users/edit.php';  
    require_once $viewPath;
}
elseif ($requestUri === '/user/show' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('user_read');

    require_once BASE_PATH . '/src/Controllers/UserController.php';
    if (isset($_POST['id'])) {
        $controller = new UserController($pdo);
        $controller->showUser($_POST['id']);  // Pass the user ID from the query parameter
    } 
    $viewPath = BASE_PATH . '/src/views/users/show.php';  
    require_once $viewPath; 
}
elseif ($requestUri === '/user/delete' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('user_delete');

    if (isset($_POST['id'])) {
        $controller = new UserController($pdo);
        $controller->deleteUser($_POST['id']);
    }
}

// ==== //
// routes for roles and permissions //
elseif ($requestUri === '/roles') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    require_once BASE_PATH . '/src/Controllers/RoleController.php';
    requirePermission('role_read');

    $controller = new RoleController();
    $controller->getAllRoles();
}
elseif ($requestUri === '/roles/create') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('role_create');

    require_once BASE_PATH . '/src/Controllers/PermissionController.php';
    $controller = new PermissionController();
    $controller->getAllPermissions();
    $viewPath = BASE_PATH . '/src/views/roles/create.php';  
    require_once $viewPath; 
}
elseif ($requestUri === '/role/store' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('role_create');

    $roleName = $_POST['name'];
    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];
    $controller = new RoleController($pdo);
    $controller->storeRole($roleName, $permissions);
} 
elseif ($requestUri === '/role/edit' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('role_update');
    
    require_once BASE_PATH . '/src/Controllers/RoleController.php';

    if (isset($_POST['id'])) {
        $controller = new RoleController($pdo);
        $controller->editRole($_POST['id']);
    }
    // Load the edit view with the fetched data
    $viewPath = BASE_PATH . '/src/views/roles/edit.php';
    require_once $viewPath;
}
elseif ($requestUri === '/role/update' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('role_update');

    require_once BASE_PATH . '/src/Controllers/RoleController.php';
    $roleController = new RoleController($pdo);
    if (isset($_POST['id'], $_POST['name'], $_POST['permissions'])) {
        $roleController->updateRole($_POST['id'], $_POST['name'], $_POST['permissions']);
    }
    header('Location: /roles');
    exit;
}
elseif ($requestUri === '/role/delete' && $requestMethod === 'POST') {
    if (!isAuthenticated()) {
        header("Location: /");
        exit;
    }
    requirePermission('role_delete');

    if (isset($_POST['id'])) {
        $controller = new RoleController($pdo);
        $controller->deleteRole($_POST['id']);
    }
}


else 
{
    echo "404 Not Found";
}
