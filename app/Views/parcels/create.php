<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Book New Parcel</h1>
    <a href="<?= $_ENV['APP_URL'] ?>/parcels" class="btn btn-secondary btn-sm">Back to List</a>
</div>

<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form action="<?= $_ENV['APP_URL'] ?>/parcels/store" method="POST">
    <?= \App\Helpers\CSRF::field() ?>
    
    <div class="row">
        <!-- Sender & Parcel Info -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Sender & Parcel Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control bg-light" value="<?= $trackingNumber ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control bg-light" value="<?= $invoiceNumber ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Customer (Sender)</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Select Customer --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['contact_person']) ?> (<?= htmlspecialchars($c['customer_code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Parcel Type</label>
                            <select name="parcel_type" class="form-select" required>
                                <option value="Package">Package</option>
                                <option value="Document">Document</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Food">Food</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Delivery Type</label>
                            <select name="delivery_type" class="form-select" required>
                                <option value="Regular">Regular</option>
                                <option value="Express">Express</option>
                                <option value="Same Day">Same Day</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="1.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Declared Value</label>
                            <input type="number" step="0.01" name="declared_value" class="form-control" value="0.00">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Delivery Charge</label>
                            <input type="number" step="0.01" name="delivery_charge" class="form-control" value="0.00">
                            <small class="text-muted">Calculation will be added in later phase.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">COD Amount</label>
                            <input type="number" step="0.01" name="cod_amount" class="form-control" value="0.00">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Special Instructions</label>
                        <textarea name="special_instruction" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receiver Info -->
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Receiver Information</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Receiver Name</label>
                        <input type="text" name="receiver_name" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="receiver_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alt. Phone</label>
                            <input type="text" name="receiver_alt_phone" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="receiver_country" class="form-control" value="Bangladesh">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Division</label>
                            <input type="text" name="receiver_division" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">District</label>
                            <input type="text" name="receiver_district" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upazila</label>
                            <input type="text" name="receiver_upazila" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="receiver_address" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="receiver_landmark" class="form-control">
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Confirm Booking</button>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
