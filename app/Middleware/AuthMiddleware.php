<?php

namespace App\Middleware;

use App\Helpers\Session;

class AuthMiddleware {
    public static function handle() {
        if (!Session::has('user_id')) {
            header("Location: " . ($_ENV['APP_URL'] ?? '') . "/login");
            exit;
        }
    }

    public static function guest() {
        if (Session::has('user_id')) {
            header("Location: " . ($_ENV['APP_URL'] ?? '') . "/dashboard");
            exit;
        }
    }
}
