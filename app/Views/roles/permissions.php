<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Assign Permissions: <?= htmlspecialchars($role['role_name']) ?></h1>
    <a href="<?= $_ENV['APP_URL'] ?>/roles" class="btn btn-secondary btn-sm">Back to Roles</a>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/roles/permissions/update" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <input type="hidden" name="role_id" value="<?= $role['id'] ?>">
            
            <div class="row">
                <?php foreach ($groupedPermissions as $module => $permissions): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light fw-bold"><?= $module ?></div>
                        <div class="card-body">
                            <?php foreach ($permissions as $p): ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" 
                                       value="<?= $p['id'] ?>" id="p<?= $p['id'] ?>"
                                       <?= in_array($p['id'], $rolePermissions) ? 'checked' : '' ?>>
                                <label class="form-check-input-label" for="p<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['permission_name']) ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($p['permission_key']) ?></small>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Permissions</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
