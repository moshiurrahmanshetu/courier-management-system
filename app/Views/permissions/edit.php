<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Permission</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/permissions" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card col-md-8">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/permissions/update" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <input type="hidden" name="id" value="<?= $permission['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Permission Name</label>
                <input type="text" name="permission_name" class="form-control" value="<?= htmlspecialchars($permission['permission_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Permission Key</label>
                <input type="text" name="permission_key" class="form-control" value="<?= htmlspecialchars($permission['permission_key']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Module</label>
                <input type="text" name="module" class="form-control" value="<?= htmlspecialchars($permission['module']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($permission['description']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Permission</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
