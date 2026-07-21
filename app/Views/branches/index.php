<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Branches</h1>
    <?php if (can('branch.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/branches/create" class="btn btn-primary btn-sm">Add New Branch</a>
    <?php endif; ?>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= $_ENV['APP_URL'] ?>/branches" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by Code, Name, Manager, Phone..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-grid">
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
                        <th>Code</th>
                        <th>Branch Name</th>
                        <th>Manager</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($branches)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No branches found.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($branches as $b): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($b['branch_code']) ?></td>
                        <td><?= htmlspecialchars($b['branch_name']) ?></td>
                        <td><?= htmlspecialchars($b['manager_name']) ?></td>
                        <td>
                            <div><?= htmlspecialchars($b['phone']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($b['email']) ?></small>
                        </td>
                        <td>
                            <div class="small"><?= htmlspecialchars($b['district']) ?>, <?= htmlspecialchars($b['division']) ?></div>
                        </td>
                        <td>
                            <span class="badge bg-<?= $b['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($b['status']) ?></span>
                        </td>
                        <td class="text-end">
                            <?php if (can('branch.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/branches/edit?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
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
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
