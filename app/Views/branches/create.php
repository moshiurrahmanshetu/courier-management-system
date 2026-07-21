<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Branch</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/branches" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/branches/store" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Branch Code</label>
                    <input type="text" name="branch_code" class="form-control bg-light" value="<?= $branchCode ?>" readonly>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Branch Name</label>
                    <input type="text" name="branch_name" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Manager Name</label>
                    <input type="text" name="manager_name" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="Bangladesh">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Division</label>
                    <input type="text" name="division" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">District</label>
                    <input type="text" name="district" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Upazila</label>
                    <input type="text" name="upazila" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Postcode</label>
                    <input type="text" name="postcode" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Create Branch</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
