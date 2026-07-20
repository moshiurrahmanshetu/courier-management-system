<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['APP_NAME'] ?? 'Courier MS' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.08); }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
        .sidebar { min-height: 100vh; background: #212529; color: #fff; }
        .sidebar .nav-link { color: rgba(255,255,255,.75); }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
    </style>
</head>
<body>
<?php if (\App\Helpers\Session::has('user_id')): ?>
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary" href="<?= $_ENV['APP_URL'] ?>/dashboard">
            <i class="bi bi-truck"></i> Courier MS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars(user()['name']) ?> 
                        <span class="badge bg-light text-dark ms-1 small"><?= htmlspecialchars(user()['role']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="<?= $_ENV['APP_URL'] ?>/profile"><i class="bi bi-person me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= $_ENV['APP_URL'] ?>/logout"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?>/dashboard">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    
                    <?php if (can('users.view')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/users') !== false ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?>/users">
                            <i class="bi bi-people me-2"></i> Users
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (can('roles.view') || can('permissions.view')): ?>
                    <li class="nav-item mt-3">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase small fw-bold">
                            <span>RBAC Management</span>
                        </h6>
                    </li>
                    <?php if (can('roles.view')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/roles') !== false ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?>/roles">
                            <i class="bi bi-shield-check me-2"></i> Roles
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (can('permissions.view')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/permissions') !== false ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?>/permissions">
                            <i class="bi bi-key me-2"></i> Permissions
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (can('activity_logs.view')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/activity-logs') !== false ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?>/activity-logs">
                            <i class="bi bi-journal-text me-2"></i> Activity Logs
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>

                    <li class="nav-item mt-3">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase small fw-bold">
                            <span>Account</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/profile') !== false ? 'active' : '' ?>" href="<?= $_ENV['APP_URL'] ?>/profile">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
<?php endif; ?>
