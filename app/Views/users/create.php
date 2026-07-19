<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Create User</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/users" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/users/store" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="johndoe">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" required placeholder="+1234567890">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_slug" class="form-select" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['slug'] ?>" <?= $role['slug'] === 'general-user' ? 'selected' : '' ?>>
                                <?= $role['role_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="active" id="statusSwitch" checked>
                    <label class="form-check-label" for="statusSwitch">Active</label>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> A temporary password will be generated automatically and the user will be required to change it upon first login.
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
