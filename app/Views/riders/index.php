<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Riders</h1>
    <?php if (can('rider.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/riders/create" class="btn btn-primary btn-sm">Add New Rider</a>
    <?php endif; ?>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= $_ENV['APP_URL'] ?>/riders" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Code, Name, Email..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
            <div class="col-md-3">
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $b): ?>
                        <option value="<?= $b['id'] ?>" <?= $filters['branch_id'] == $b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['branch_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
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
                        <th>Rider Name</th>
                        <th>Branch</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riders)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No riders found.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($riders as $r): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($r['rider_code']) ?></td>
                        <td><?= htmlspecialchars($r['rider_name']) ?></td>
                        <td><?= htmlspecialchars($r['branch_name']) ?></td>
                        <td><?= htmlspecialchars($r['vehicle_type']) ?></td>
                        <td>
                            <span class="badge bg-<?= $r['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($r['status']) ?></span>
                        </td>
                        <td class="text-end">
                            <?php if (can('rider.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/riders/edit?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (can('rider.delete')): ?>
                                <form action="<?= $_ENV['APP_URL'] ?>/riders/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this rider?')">
                                    <?= \App\Helpers\CSRF::field() ?>
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
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
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($filters['search']) ?>&branch_id=<?= $filters['branch_id'] ?>&status=<?= $filters['status'] ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($filters['search']) ?>&branch_id=<?= $filters['branch_id'] ?>&status=<?= $filters['status'] ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($filters['search']) ?>&branch_id=<?= $filters['branch_id'] ?>&status=<?= $filters['status'] ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
