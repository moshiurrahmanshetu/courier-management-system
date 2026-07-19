<?php

/**
 * Courier Management System - Entry Point
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Session Regeneration
if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = time();
} elseif (time() - $_SESSION['created_at'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created_at'] = time();
}

// Initialize Router
$router = require_once __DIR__ . '/../routes/web.php';

// Dispatch Request
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($url, $method);
