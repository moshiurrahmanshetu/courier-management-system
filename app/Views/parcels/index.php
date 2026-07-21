<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Parcel Bookings</h1>
    <?php if (can('parcel.create')): ?>
        <a href="<?= $_ENV['APP_URL'] ?>/parcels/create" class="btn btn-primary btn-sm">Book New Parcel</a>
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
        <form method="GET" action="<?= $_ENV['APP_URL'] ?>/parcels" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="TRK, INV, Name, Phone..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>
            <div class="col-md-2">
                <select name="parcel_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Document" <?= $filters['parcel_type'] === 'Document' ? 'selected' : '' ?>>Document</option>
                    <option value="Package" <?= $filters['parcel_type'] === 'Package' ? 'selected' : '' ?>>Package</option>
                    <option value="Electronics" <?= $filters['parcel_type'] === 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                    <option value="Food" <?= $filters['parcel_type'] === 'Food' ? 'selected' : '' ?>>Food</option>
                    <option value="Others" <?= $filters['parcel_type'] === 'Others' ? 'selected' : '' ?>>Others</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="delivery_type" class="form-select">
                    <option value="">All Delivery</option>
                    <option value="Regular" <?= $filters['delivery_type'] === 'Regular' ? 'selected' : '' ?>>Regular</option>
                    <option value="Express" <?= $filters['delivery_type'] === 'Express' ? 'selected' : '' ?>>Express</option>
                    <option value="Same Day" <?= $filters['delivery_type'] === 'Same Day' ? 'selected' : '' ?>>Same Day</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="booking_date" class="form-control" value="<?= htmlspecialchars($filters['booking_date']) ?>">
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
                        <th>Tracking / Invoice</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Details</th>
                        <th>Charge</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($parcels)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No parcels found.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($parcels as $p): ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-primary"><?= htmlspecialchars($p['tracking_number']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($p['invoice_number']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($p['sender_name']) ?></td>
                        <td>
                            <div><?= htmlspecialchars($p['receiver_name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($p['receiver_phone']) ?></small>
                        </td>
                        <td>
                            <div class="small"><?= $p['parcel_type'] ?> | <?= $p['delivery_type'] ?></div>
                            <small class="text-muted"><?= $p['weight'] ?>kg | Qty: <?= $p['quantity'] ?></small>
                        </td>
                        <td><?= number_format($p['delivery_charge'], 2) ?></td>
                        <td>
                            <span class="badge bg-info"><?= $p['current_status'] ?></span>
                        </td>
                        <td class="text-end">
                            <a href="<?= $_ENV['APP_URL'] ?>/parcels/show?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (can('parcel.edit')): ?>
                                <a href="<?= $_ENV['APP_URL'] ?>/parcels/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (can('parcel.delete')): ?>
                                <form action="<?= $_ENV['APP_URL'] ?>/parcels/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this parcel booking?')">
                                    <?= \App\Helpers\CSRF::field() ?>
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
        
        <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>&parcel_type=<?= $filters['parcel_type'] ?>&delivery_type=<?= $filters['delivery_type'] ?>&booking_date=<?= $filters['booking_date'] ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>&parcel_type=<?= $filters['parcel_type'] ?>&delivery_type=<?= $filters['delivery_type'] ?>&booking_date=<?= $filters['booking_date'] ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($filters['search']) ?>&status=<?= $filters['status'] ?>&parcel_type=<?= $filters['parcel_type'] ?>&delivery_type=<?= $filters['delivery_type'] ?>&booking_date=<?= $filters['booking_date'] ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
