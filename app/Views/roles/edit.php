<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Role</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/roles" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card col-md-8">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/roles/update" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <input type="hidden" name="id" value="<?= $role['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Role Name</label>
                <input type="text" name="role_name" class="form-control" value="<?= htmlspecialchars($role['role_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($role['slug']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($role['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?= $role['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $role['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Role</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
