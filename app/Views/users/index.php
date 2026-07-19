<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">User Management</h1>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current Role</th>
                        <th>Joined At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($u['role']) ?></span>
                        </td>
                        <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                        <td class="text-end">
                            <?php if (can('users.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/users/edit-role?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning" title="Change Role">
                                    <i class="bi bi-person-gear"></i> Change Role
                                </a>
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
