<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Customer Details: <?= htmlspecialchars($customer['customer_code']) ?></h1>
    <div>
        <a href="<?= $_ENV['APP_URL'] ?>/customers" class="btn btn-secondary btn-sm">Back to List</a>
        <?php if (can('customer.edit')): ?>
            <a href="<?= $_ENV['APP_URL'] ?>/customers/edit?id=<?= $customer['id'] ?>" class="btn btn-primary btn-sm">Edit Customer</a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <img src="<?= $customer['user_avatar'] ? $_ENV['APP_URL'] . '/' . $customer['user_avatar'] : 'https://via.placeholder.com/150' ?>" 
                     class="rounded-circle mb-3 border p-1" style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?= htmlspecialchars($customer['contact_person']) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($customer['company_name'] ?: 'Individual') ?></p>
                <span class="badge bg-primary mb-2"><?= htmlspecialchars($customer['customer_code']) ?></span>
                <br>
                <span class="badge bg-<?= $customer['status'] === 'active' ? 'success' : 'danger' ?>">
                    <?= ucfirst($customer['status']) ?>
                </span>
                <?php if ($customer['deleted_at']): ?>
                    <span class="badge bg-warning text-dark">Deleted</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Linked User Account</div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle me-2 text-muted"></i>
                    <span><?= htmlspecialchars($customer['user_name']) ?></span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-at me-2 text-muted"></i>
                    <small class="text-muted">@<?= htmlspecialchars($customer['user_handle']) ?></small>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-envelope me-2 text-muted"></i>
                    <small><?= htmlspecialchars($customer['email']) ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Company & Contact Information</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Customer Type</div>
                    <div class="col-sm-8 fw-bold"><?= $customer['customer_type'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Company Name</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['company_name'] ?: 'N/A') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Contact Person</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['contact_person']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Phone</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['phone']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Alt. Phone</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['alternative_phone'] ?: 'N/A') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Email</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['email']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">NID Number</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['nid_number'] ?: 'N/A') ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Trade License</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['trade_license'] ?: 'N/A') ?></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Address Information</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Address</div>
                    <div class="col-sm-8"><?= nl2br(htmlspecialchars($customer['address'] ?: 'N/A')) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Location</div>
                    <div class="col-sm-8">
                        <?= htmlspecialchars($customer['upazila'] ?: '') ?>, 
                        <?= htmlspecialchars($customer['district'] ?: '') ?>, 
                        <?= htmlspecialchars($customer['division'] ?: '') ?> 
                        <?= htmlspecialchars($customer['postcode'] ?: '') ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Country</div>
                    <div class="col-sm-8"><?= htmlspecialchars($customer['country']) ?></div>
                </div>
            </div>
        </div>

        <?php if ($customer['notes']): ?>
        <div class="card shadow-sm mb-4 border-info">
            <div class="card-header bg-info text-white fw-bold">Notes</div>
            <div class="card-body">
                <?= nl2br(htmlspecialchars($customer['notes'])) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
