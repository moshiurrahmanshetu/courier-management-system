<?php

namespace App\Helpers;

class CSRF {
    public static function generate() {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function verify($token) {
        return Session::has('csrf_token') && hash_equals(Session::get('csrf_token'), $token);
    }

    public static function field() {
        $token = self::generate();
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"$token\">";
    }
}
