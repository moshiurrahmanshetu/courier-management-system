<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Role Management</h1>
    <?php if (can('roles.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/roles/create" class="btn btn-primary btn-sm">Create New Role</a>
    <?php endif; ?>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Role Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($role['role_name']) ?></strong></td>
                        <td><code class="text-muted"><?= htmlspecialchars($role['slug']) ?></code></td>
                        <td>
                            <span class="badge bg-<?= $role['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($role['status']) ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d', strtotime($role['created_at'])) ?></td>
                        <td class="text-end">
                            <?php if (can('roles.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/roles/permissions?id=<?= $role['id'] ?>" class="btn btn-sm btn-outline-info" title="Manage Permissions">
                                    <i class="bi bi-shield-lock"></i>
                                </a>
                                <a href="<?= $_ENV['APP_URL'] ?>/roles/edit?id=<?= $role['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (can('roles.delete')): ?>
                                <form action="<?= $_ENV['APP_URL'] ?>/roles/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="id" value="<?= $role['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
