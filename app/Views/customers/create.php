<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Create Customer</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/customers" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/customers/store" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Customer Code</label>
                    <input type="text" name="customer_code" class="form-control bg-light" value="<?= $customerCode ?>" readonly>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Link User Account</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Select User --</option>
                        <?php foreach ($availableUsers as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Only users without a customer profile are shown.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer Type</label>
                    <select name="customer_type" class="form-select" required>
                        <option value="Individual">Individual</option>
                        <option value="Business">Business</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name (Optional)</label>
                    <input type="text" name="company_name" class="form-control" placeholder="Acme Corp">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" required placeholder="John Doe">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required placeholder="+1234567890">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alternative Phone</label>
                    <input type="text" name="alternative_phone" class="form-control" placeholder="+1234567890">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NID Number</label>
                    <input type="text" name="nid_number" class="form-control" placeholder="National ID">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trade License</label>
                    <input type="text" name="trade_license" class="form-control" placeholder="License Number">
                </div>
            </div>

            <h5 class="mt-4 mb-3 border-bottom pb-2">Address Information</h5>
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
                <div class="col-md-9 mb-3">
                    <label class="form-label">Full Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" value="active" id="statusSwitch" checked>
                    <label class="form-check-label" for="statusSwitch">Active</label>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Create Customer</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
