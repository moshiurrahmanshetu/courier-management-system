<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">User Management</h1>
    <?php if (can('users.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/users/create" class="btn btn-primary btn-sm">Create New User</a>
    <?php endif; ?>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= $_ENV['APP_URL'] ?>/users" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['slug'] ?>" <?= $filters['role'] === $role['slug'] ? 'selected' : '' ?>><?= $role['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Contact</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No users found matching your criteria.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= $u['avatar'] ? $_ENV['APP_URL'] . '/' . $u['avatar'] : 'https://via.placeholder.com/40' ?>" 
                                     class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($u['name']) ?></div>
                                    <small class="text-muted">@<?= htmlspecialchars($u['username']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($u['email']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($u['mobile']) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($u['current_role_name']) ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($u['status']) ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
                        <td class="text-end">
                            <a href="<?= $_ENV['APP_URL'] ?>/users/show?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-info" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (can('users.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/users/edit?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (can('users.delete')): ?>
                                <form action="<?= $_ENV['APP_URL'] ?>/users/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This is a soft delete.')">
                                    <?= \App\Helpers\CSRF::field() ?>
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($filters['search']) ?>&role=<?= $filters['role'] ?>&status=<?= $filters['status'] ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
