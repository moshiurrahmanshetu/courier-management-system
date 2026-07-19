<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold">Welcome, <?= htmlspecialchars($user['name']) ?>!</h5>
                <p class="card-text">You are logged in as a <strong><?= htmlspecialchars($user['role']) ?></strong>.</p>
                <p class="text-muted small">Member since: <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold">Profile Summary</h5>
                <ul class="list-unstyled">
                    <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
                    <li><strong>Account Status:</strong> <span class="badge bg-success">Active</span></li>
                </ul>
                <a href="<?= $_ENV['APP_URL'] ?>/profile" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
