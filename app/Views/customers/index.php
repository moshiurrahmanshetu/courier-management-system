<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Customer Management</h1>
    <?php if (can('customer.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/customers/create" class="btn btn-primary btn-sm">Create New Customer</a>
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
        <form method="GET" action="<?= $_ENV['APP_URL'] ?>/customers" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Code, Company, Contact, Phone..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
            <div class="col-md-2">
                <select name="customer_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Individual" <?= $filters['customer_type'] === 'Individual' ? 'selected' : '' ?>>Individual</option>
                    <option value="Business" <?= $filters['customer_type'] === 'Business' ? 'selected' : '' ?>>Business</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="show_deleted" value="1" id="showDeleted" <?= $filters['include_deleted'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="showDeleted">Show Deleted</label>
                </div>
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
                        <th>Customer</th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No customers found matching your criteria.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($customers as $c): ?>
                    <tr class="<?= $c['deleted_at'] ? 'table-light text-muted' : '' ?>">
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= $c['user_avatar'] ? $_ENV['APP_URL'] . '/' . $c['user_avatar'] : 'https://via.placeholder.com/40' ?>" 
                                     class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($c['customer_code']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($c['customer_type']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($c['company_name'] ?: 'N/A') ?></td>
                        <td>
                            <div><?= htmlspecialchars($c['contact_person']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($c['phone']) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($c['status']) ?>
                            </span>
                            <?php if ($c['deleted_at']): ?>
                                <span class="badge bg-warning text-dark">Deleted</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('Y-m-d', strtotime($c['created_at'])) ?></td>
                        <td class="text-end">
                            <a href="<?= $_ENV['APP_URL'] ?>/customers/show?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-info" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (!$c['deleted_at']): ?>
                                <?php if (can('customer.edit')): ?>
                                    <a href="<?= $_ENV['APP_URL'] ?>/customers/edit?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if (can('customer.delete')): ?>
                                    <form action="<?= $_ENV['APP_URL'] ?>/customers/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                        <?= \App\Helpers\CSRF::field() ?>
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (can('customer.delete')): ?>
                                    <form action="<?= $_ENV['APP_URL'] ?>/customers/restore" method="POST" class="d-inline" onsubmit="return confirm('Restore this customer?')">
                                        <?= \App\Helpers\CSRF::field() ?>
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                    </form>
                                <?php endif; ?>
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
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>&customer_type=<?= $filters['customer_type'] ?>&show_deleted=<?= $filters['include_deleted'] ? 1 : 0 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>&customer_type=<?= $filters['customer_type'] ?>&show_deleted=<?= $filters['include_deleted'] ? 1 : 0 ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>&customer_type=<?= $filters['customer_type'] ?>&show_deleted=<?= $filters['include_deleted'] ? 1 : 0 ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
