<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">My Profile</h1>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <img src="<?= $user['avatar'] ? $_ENV['APP_URL'] . '/' . $user['avatar'] : 'https://via.placeholder.com/150' ?>" 
                     class="rounded-circle mb-3 border p-1" style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?= htmlspecialchars($user['name']) ?></h4>
                <p class="text-muted">@<?= htmlspecialchars($user['username']) ?></p>
                <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($user['role']) ?></span>
                <br>
                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($user['status']) ?>
                </span>
                
                <form action="<?= $_ENV['APP_URL'] ?>/profile/avatar" method="POST" enctype="multipart/form-data" class="mt-4">
                    <?= \App\Helpers\CSRF::field() ?>
                    <div class="mb-3">
                        <input class="form-control form-control-sm" type="file" name="avatar" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Update Avatar</button>
                </form>
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
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Personal Information</div>
            <div class="card-body">
                <form action="<?= $_ENV['APP_URL'] ?>/profile/update" method="POST">
                    <?= \App\Helpers\CSRF::field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($user['mobile']) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="male" <?= $user['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $user['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= $user['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value="<?= $user['dob'] ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold text-danger">Change Password</div>
            <div class="card-body">
                <form action="<?= $_ENV['APP_URL'] ?>/profile/password" method="POST">
                    <?= \App\Helpers\CSRF::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
