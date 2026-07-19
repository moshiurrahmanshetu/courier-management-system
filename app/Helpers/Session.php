<?php

namespace App\Helpers;

class Session {
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
    }

    public static function flash($key, $message = null) {
        if ($message) {
            self::set("flash_$key", $message);
        } else {
            $msg = self::get("flash_$key");
            self::remove("flash_$key");
            return $msg;
        }
    }
}
