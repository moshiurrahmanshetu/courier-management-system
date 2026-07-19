<?php

use App\Helpers\Session;
use App\Models\User;

if (!function_exists('auth')) {
    function auth() {
        return Session::has('user_id');
    }
}

if (!function_exists('user')) {
    function user() {
        if (!auth()) return null;
        static $user = null;
        if ($user === null) {
            $userModel = new User();
            $user = $userModel->findById(Session::get('user_id'));
        }
        return $user;
    }
}

if (!function_exists('hasRole')) {
    function hasRole($roleSlug) {
        if (!auth()) return false;
        $userModel = new User();
        $role = $userModel->getRole(Session::get('user_id'));
        return $role && $role['slug'] === $roleSlug;
    }
}

if (!function_exists('hasAnyRole')) {
    function hasAnyRole(array $roleSlugs) {
        if (!auth()) return false;
        $userModel = new User();
        $role = $userModel->getRole(Session::get('user_id'));
        return $role && in_array($role['slug'], $roleSlugs);
    }
}

if (!function_exists('can')) {
    function can($permissionKey) {
        if (!auth()) return false;
        
        // Super Admin has all permissions
        if (hasRole('super-admin')) return true;

        static $permissions = null;
        if ($permissions === null) {
            $userModel = new User();
            $permissions = $userModel->getPermissions(Session::get('user_id'));
        }
        return in_array($permissionKey, $permissions);
    }
}

if (!function_exists('cannot')) {
    function cannot($permissionKey) {
        return !can($permissionKey);
    }
}
