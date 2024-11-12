<?php


use App\Controllers\AuthController;


function isAuthenticated() {
    session_start();
    return isset($_SESSION['user_id']); 
}

function hasPermissions($permission) {
    session_start();
    return in_array($permission , $_SESSION['permissions']);
}

function requirePermission($permission) {
    if (!hasPermissions($permission)) {
        header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - You do not have permission to access this resource.";
    exit;
    }
}

