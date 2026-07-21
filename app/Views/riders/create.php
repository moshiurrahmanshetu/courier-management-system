<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Rider</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/riders" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/riders/store" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Rider Code</label>
                    <input type="text" name="rider_code" class="form-control bg-light" value="<?= $riderCode ?>" readonly>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Select User</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Select Registered User --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (ID: <?= $u['id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Only users without rider/customer profiles are listed.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Assigned Branch</label>
                    <select name="branch_id" class="form-select" required>
                        <option value="">-- Select Branch --</option>
                        <?php foreach ($branches as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['branch_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vehicle Type</label>
                    <input type="text" name="vehicle_type" class="form-control" placeholder="e.g. Bike, Cycle, Van">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">License Number</label>
                    <input type="text" name="license_number" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NID Number</label>
                    <input type="text" name="nid_number" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Create Rider Profile</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
