<?php

use Core\Router;

$router = new Router();

// Auth Routes
$router->add('GET', '/login', 'AuthController@showLogin');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/register', 'AuthController@showRegister');
$router->add('POST', '/register', 'AuthController@register');
$router->add('GET', '/logout', 'AuthController@logout');

// Dashboard & Profile Routes
$router->add('GET', '/', 'DashboardController@index');
$router->add('GET', '/dashboard', 'DashboardController@index');
$router->add('GET', '/profile', 'ProfileController@index');
$router->add('POST', '/profile/update', 'ProfileController@update');
$router->add('POST', '/profile/avatar', 'ProfileController@updateAvatar');
$router->add('POST', '/profile/password', 'ProfileController@updatePassword');

// User Management
$router->add('GET', '/users', 'UserController@index');
$router->add('GET', '/users/edit-role', 'UserController@editRole');
$router->add('POST', '/users/update-role', 'UserController@updateRole');

// Role Management
$router->add('GET', '/roles', 'RoleController@index');
$router->add('GET', '/roles/create', 'RoleController@create');
$router->add('POST', '/roles/store', 'RoleController@store');
$router->add('GET', '/roles/edit', 'RoleController@edit');
$router->add('POST', '/roles/update', 'RoleController@update');
$router->add('POST', '/roles/delete', 'RoleController@delete');
$router->add('GET', '/roles/permissions', 'RoleController@permissions');
$router->add('POST', '/roles/permissions/update', 'RoleController@updatePermissions');

// Permission Management
$router->add('GET', '/permissions', 'PermissionController@index');
$router->add('GET', '/permissions/create', 'PermissionController@create');
$router->add('POST', '/permissions/store', 'PermissionController@store');
$router->add('GET', '/permissions/edit', 'PermissionController@edit');
$router->add('POST', '/permissions/update', 'PermissionController@update');
$router->add('POST', '/permissions/delete', 'PermissionController@delete');

return $router;
