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
$router->add('GET', '/users/create', 'UserController@create');
$router->add('POST', '/users/store', 'UserController@store');
$router->add('GET', '/users/show', 'UserController@show');
$router->add('GET', '/users/edit', 'UserController@edit');
$router->add('POST', '/users/update', 'UserController@update');
$router->add('POST', '/users/delete', 'UserController@delete');

// Activity Logs
$router->add('GET', '/activity-logs', 'ActivityLogController@index');

// Customer Management
$router->add('GET', '/customers', 'CustomerController@index');
$router->add('GET', '/customers/create', 'CustomerController@create');
$router->add('POST', '/customers/store', 'CustomerController@store');
$router->add('GET', '/customers/show', 'CustomerController@show');
$router->add('GET', '/customers/edit', 'CustomerController@edit');
$router->add('POST', '/customers/update', 'CustomerController@update');
$router->add('POST', '/customers/delete', 'CustomerController@delete');
$router->add('POST', '/customers/restore', 'CustomerController@restore');

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
