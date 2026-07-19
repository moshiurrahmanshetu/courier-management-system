<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Change User Role</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/users" class="btn btn-secondary btn-sm">Back to Users</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card col-md-6">
    <div class="card-body">
        <h5 class="card-title mb-4">User: <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</h5>
        
        <form action="<?= $_ENV['APP_URL'] ?>/users/update-role" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            
            <div class="mb-4">
                <label class="form-label fw-bold">Select Role</label>
                <?php foreach ($roles as $role): ?>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="role_id" 
                           value="<?= $role['id'] ?>" id="role<?= $role['id'] ?>"
                           <?= ($userRole && $userRole['id'] === $role['id']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="role<?= $role['id'] ?>">
                        <strong><?= htmlspecialchars($role['role_name']) ?></strong>
                        <br><small class="text-muted"><?= htmlspecialchars($role['description']) ?></small>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Update User Role</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
