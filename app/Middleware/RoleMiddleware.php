<?php

namespace App\Middleware;

use App\Helpers\Session;

class RoleMiddleware {
    public static function requirePermission($permissionKey) {
        AuthMiddleware::handle();
        
        if (!can($permissionKey)) {
            header("HTTP/1.1 403 Forbidden");
            require_once __DIR__ . '/../Views/errors/403.php';
            exit;
        }
    }

    public static function requireRole($roleSlug) {
        AuthMiddleware::handle();
        
        if (!hasRole($roleSlug)) {
            header("HTTP/1.1 403 Forbidden");
            require_once __DIR__ . '/../Views/errors/403.php';
            exit;
        }
    }
}
