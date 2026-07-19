<?php

/**
 * Courier Management System - Entry Point
 */

// Define project root
define('ROOT_PATH', dirname(__DIR__));

// Load custom Bootstrap
require_once ROOT_PATH . '/core/Bootstrap.php';

// Initialize the application
Core\Bootstrap::init();

// Initialize Router
$router = require_once ROOT_PATH . '/routes/web.php';

// Dispatch Request
$url = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($url, $method);
