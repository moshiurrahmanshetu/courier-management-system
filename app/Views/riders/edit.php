<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Rider: <?= htmlspecialchars($rider['rider_name']) ?></h1>
    <a href="<?= $_ENV['APP_URL'] ?>/riders" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/riders/update" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <input type="hidden" name="id" value="<?= $rider['id'] ?>">
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Rider Code</label>
                    <input type="text" class="form-control bg-light" value="<?= $rider['rider_code'] ?>" readonly disabled>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($rider['rider_name']) ?> (ID: <?= $rider['user_id'] ?>)" readonly disabled>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Assigned Branch</label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        <?php foreach ($branches as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= $rider['branch_id'] == $b['id'] ? 'selected' : '' ?>><?= htmlspecialchars($b['branch_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Type</label>
                    <input type="text" name="vehicle_type" class="form-control" value="<?= htmlspecialchars($rider['vehicle_type']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">License Number</label>
                    <input type="text" name="license_number" class="form-control" value="<?= htmlspecialchars($rider['license_number']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NID Number</label>
                    <input type="text" name="nid_number" class="form-control" value="<?= htmlspecialchars($rider['nid_number']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control" value="<?= $rider['joining_date'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= $rider['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $rider['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Update Rider Profile</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
