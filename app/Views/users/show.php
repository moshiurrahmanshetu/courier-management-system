<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">User Details: <?= htmlspecialchars($user['name']) ?></h1>
    <a href="<?= $_ENV['APP_URL'] ?>/users" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <img src="<?= $user['avatar'] ? $_ENV['APP_URL'] . '/' . $user['avatar'] : 'https://via.placeholder.com/150' ?>" 
                     class="rounded-circle mb-3 border p-1" style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?= htmlspecialchars($user['name']) ?></h4>
                <p class="text-muted">@<?= htmlspecialchars($user['username']) ?></p>
                <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($role['role_name'] ?? 'No Role') ?></span>
                <br>
                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($user['status']) ?>
                </span>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Account Status</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Joined Date
                    <span><?= date('Y-m-d', strtotime($user['created_at'])) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Last Login
                    <span><?= $user['last_login_at'] ? date('Y-m-d H:i', strtotime($user['last_login_at'])) : 'Never' ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Last IP
                    <span><?= htmlspecialchars($user['last_login_ip'] ?? 'N/A') ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Email Verified
                    <span class="text-<?= $user['email_verified_at'] ? 'success' : 'warning' ?>">
                        <?= $user['email_verified_at'] ? 'Verified' : 'Pending' ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Basic Information</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Email</div>
                    <div class="col-sm-8"><?= htmlspecialchars($user['email']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Mobile</div>
                    <div class="col-sm-8"><?= htmlspecialchars($user['mobile'] ?: 'N/A') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Gender</div>
                    <div class="col-sm-8"><?= ucfirst($user['gender']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Date of Birth</div>
                    <div class="col-sm-8"><?= $user['dob'] ?: 'N/A' ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Address</div>
                    <div class="col-sm-8"><?= nl2br(htmlspecialchars($user['address'] ?: 'N/A')) ?></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">Permissions Summary</div>
            <div class="card-body">
                <?php if (empty($permissions)): ?>
                    <p class="text-muted small">No specific permissions assigned to this user's role.</p>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($permissions as $p): ?>
                            <span class="badge border text-dark bg-light"><?= htmlspecialchars($p['permission_key']) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
