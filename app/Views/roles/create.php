<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Role</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/roles" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card col-md-8">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/roles/store" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <div class="mb-3">
                <label class="form-label">Role Name</label>
                <input type="text" name="role_name" class="form-control" required placeholder="e.g. Manager">
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" required placeholder="e.g. manager">
                <small class="text-muted">Unique identifier for internal use.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Role</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
