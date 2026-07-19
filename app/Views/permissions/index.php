<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Permission Management</h1>
    <?php if (can('permissions.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/permissions/create" class="btn btn-primary btn-sm">Create New Permission</a>
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
                        <th>Permission Name</th>
                        <th>Key</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permissions as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['permission_name']) ?></strong></td>
                        <td><code class="text-primary"><?= htmlspecialchars($p['permission_key']) ?></code></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($p['module']) ?></span></td>
                        <td class="small"><?= htmlspecialchars($p['description']) ?></td>
                        <td class="text-end">
                            <?php if (can('permissions.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/permissions/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (can('permissions.delete')): ?>
                                <form action="<?= $_ENV['APP_URL'] ?>/permissions/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
