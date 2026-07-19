<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Profile Settings</h1>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <img src="<?= $user['avatar'] ? $_ENV['APP_URL'] . '/' . $user['avatar'] : 'https://via.placeholder.com/150' ?>" 
                     alt="Avatar" class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h5 class="fw-bold"><?= htmlspecialchars($user['name']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($user['role']) ?></p>
                
                <form action="<?= $_ENV['APP_URL'] ?>/profile/avatar" method="POST" enctype="multipart/form-data">
                    <?= \App\Helpers\CSRF::field() ?>
                    <div class="mb-3">
                        <input type="file" name="avatar" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Upload Avatar</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white fw-bold">Personal Information</div>
            <div class="card-body">
                <form action="<?= $_ENV['APP_URL'] ?>/profile/update" method="POST">
                    <?= \App\Helpers\CSRF::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white fw-bold">Change Password</div>
            <div class="card-body">
                <form action="<?= $_ENV['APP_URL'] ?>/profile/password" method="POST">
                    <?= \App\Helpers\CSRF::field() ?>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
