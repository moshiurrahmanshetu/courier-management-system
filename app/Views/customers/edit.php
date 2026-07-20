<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Customer: <?= htmlspecialchars($customer['customer_code']) ?></h1>
    <a href="<?= $_ENV['APP_URL'] ?>/customers" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?= $_ENV['APP_URL'] ?>/customers/update" method="POST">
            <?= \App\Helpers\CSRF::field() ?>
            <input type="hidden" name="id" value="<?= $customer['id'] ?>">
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Customer Code</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($customer['customer_code']) ?>" readonly disabled>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Linked User Account</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($customer['user_name']) ?> (<?= htmlspecialchars($customer['email']) ?>)" readonly disabled>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer Type</label>
                    <select name="customer_type" class="form-select" required>
                        <option value="Individual" <?= $customer['customer_type'] === 'Individual' ? 'selected' : '' ?>>Individual</option>
                        <option value="Business" <?= $customer['customer_type'] === 'Business' ? 'selected' : '' ?>>Business</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($customer['company_name']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= htmlspecialchars($customer['contact_person']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alternative Phone</label>
                    <input type="text" name="alternative_phone" class="form-control" value="<?= htmlspecialchars($customer['alternative_phone']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NID Number</label>
                    <input type="text" name="nid_number" class="form-control" value="<?= htmlspecialchars($customer['nid_number']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trade License</label>
                    <input type="text" name="trade_license" class="form-control" value="<?= htmlspecialchars($customer['trade_license']) ?>">
                </div>
            </div>

            <h5 class="mt-4 mb-3 border-bottom pb-2">Address Information</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($customer['country']) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Division</label>
                    <input type="text" name="division" class="form-control" value="<?= htmlspecialchars($customer['division']) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">District</label>
                    <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($customer['district']) ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Upazila</label>
                    <input type="text" name="upazila" class="form-control" value="<?= htmlspecialchars($customer['upazila']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Postcode</label>
                    <input type="text" name="postcode" class="form-control" value="<?= htmlspecialchars($customer['postcode']) ?>">
                </div>
                <div class="col-md-9 mb-3">
                    <label class="form-label">Full Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($customer['address']) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($customer['notes']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?= $customer['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $customer['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
