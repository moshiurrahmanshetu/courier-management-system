<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Parcel: <?= htmlspecialchars($parcel['tracking_number']) ?></h1>
    <a href="<?= $_ENV['APP_URL'] ?>/parcels" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form action="<?= $_ENV['APP_URL'] ?>/parcels/update" method="POST">
    <?= \App\Helpers\CSRF::field() ?>
    <input type="hidden" name="id" value="<?= $parcel['id'] ?>">
    <input type="hidden" name="receiver_id" value="<?= $parcel['receiver_id'] ?>">
    
    <div class="row">
        <!-- Parcel Info -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Parcel Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" class="form-control bg-light" value="<?= $parcel['tracking_number'] ?>" readonly disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control bg-light" value="<?= $parcel['invoice_number'] ?>" readonly disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sender (Customer)</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($parcel['sender_name']) ?> (<?= htmlspecialchars($parcel['customer_code']) ?>)" readonly disabled>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Parcel Type</label>
                            <select name="parcel_type" class="form-select" required>
                                <option value="Package" <?= $parcel['parcel_type'] === 'Package' ? 'selected' : '' ?>>Package</option>
                                <option value="Document" <?= $parcel['parcel_type'] === 'Document' ? 'selected' : '' ?>>Document</option>
                                <option value="Electronics" <?= $parcel['parcel_type'] === 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                                <option value="Food" <?= $parcel['parcel_type'] === 'Food' ? 'selected' : '' ?>>Food</option>
                                <option value="Others" <?= $parcel['parcel_type'] === 'Others' ? 'selected' : '' ?>>Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Delivery Type</label>
                            <select name="delivery_type" class="form-select" required>
                                <option value="Regular" <?= $parcel['delivery_type'] === 'Regular' ? 'selected' : '' ?>>Regular</option>
                                <option value="Express" <?= $parcel['delivery_type'] === 'Express' ? 'selected' : '' ?>>Express</option>
                                <option value="Same Day" <?= $parcel['delivery_type'] === 'Same Day' ? 'selected' : '' ?>>Same Day</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="<?= $parcel['weight'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="<?= $parcel['quantity'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Declared Value</label>
                            <input type="number" step="0.01" name="declared_value" class="form-control" value="<?= $parcel['declared_value'] ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Delivery Charge</label>
                            <input type="number" step="0.01" name="delivery_charge" class="form-control" value="<?= $parcel['delivery_charge'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">COD Amount</label>
                            <input type="number" step="0.01" name="cod_amount" class="form-control" value="<?= $parcel['cod_amount'] ?>">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Special Instructions</label>
                        <textarea name="special_instruction" class="form-control" rows="2"><?= htmlspecialchars($parcel['special_instruction']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receiver Info -->
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Receiver Information</div>
                <div class="card-body">
                    <?php
                    // We need to fetch full receiver info if not already in $parcel
                    // But in our Model->find, we joined some. Let's assume we have them or fetch them.
                    // For now, I'll use what's in $parcel and assume the controller passed enough.
                    // Actually, let's fetch full receiver in Controller or adjust Model.
                    // I'll adjust the Controller to fetch full receiver info.
                    ?>
                    <div class="mb-3">
                        <label class="form-label">Receiver Name</label>
                        <input type="text" name="receiver_name" class="form-control" value="<?= htmlspecialchars($parcel['receiver_name']) ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="receiver_phone" class="form-control" value="<?= htmlspecialchars($parcel['receiver_phone']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alt. Phone</label>
                            <input type="text" name="receiver_alt_phone" class="form-control" value="<?= htmlspecialchars($parcel['receiver_alt_phone'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="receiver_country" class="form-control" value="<?= htmlspecialchars($parcel['receiver_country'] ?? 'Bangladesh') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Division</label>
                            <input type="text" name="receiver_division" class="form-control" value="<?= htmlspecialchars($parcel['receiver_division'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">District</label>
                            <input type="text" name="receiver_district" class="form-control" value="<?= htmlspecialchars($parcel['receiver_district'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upazila</label>
                            <input type="text" name="receiver_upazila" class="form-control" value="<?= htmlspecialchars($parcel['receiver_upazila'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="receiver_address" class="form-control" rows="2" required><?= htmlspecialchars($parcel['receiver_address']) ?></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="receiver_landmark" class="form-control" value="<?= htmlspecialchars($parcel['receiver_landmark'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Update Booking</button>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
