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

return $router;
