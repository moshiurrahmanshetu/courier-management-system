<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Parcel Details: <?= htmlspecialchars($parcel['tracking_number']) ?></h1>
    <div>
        <a href="<?= $_ENV['APP_URL'] ?>/parcels" class="btn btn-secondary btn-sm">Back to List</a>
        <?php if (can('parcel.edit')): ?>
            <a href="<?= $_ENV['APP_URL'] ?>/parcels/edit?id=<?= $parcel['id'] ?>" class="btn btn-primary btn-sm">Edit Booking</a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span class="fw-bold">Parcel Information</span>
                <span class="badge bg-info"><?= $parcel['current_status'] ?></span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Tracking Number</div>
                    <div class="col-sm-8 fw-bold text-primary"><?= $parcel['tracking_number'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Invoice Number</div>
                    <div class="col-sm-8"><?= $parcel['invoice_number'] ?></div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Parcel Type</div>
                    <div class="col-sm-8"><?= $parcel['parcel_type'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Delivery Type</div>
                    <div class="col-sm-8"><?= $parcel['delivery_type'] ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Weight & Quantity</div>
                    <div class="col-sm-8"><?= $parcel['weight'] ?> kg | <?= $parcel['quantity'] ?> Pcs</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Declared Value</div>
                    <div class="col-sm-8"><?= number_format($parcel['declared_value'], 2) ?></div>
                </div>
                <hr>
                <div class="row mb-3 text-success fw-bold">
                    <div class="col-sm-4">Delivery Charge</div>
                    <div class="col-sm-8"><?= number_format($parcel['delivery_charge'], 2) ?></div>
                </div>
                <div class="row mb-3 text-danger fw-bold">
                    <div class="col-sm-4">COD Amount</div>
                    <div class="col-sm-8"><?= number_format($parcel['cod_amount'], 2) ?></div>
                </div>
                <?php if ($parcel['special_instruction']): ?>
                <hr>
                <div class="row">
                    <div class="col-sm-4 text-muted">Special Instruction</div>
                    <div class="col-sm-8"><?= nl2br(htmlspecialchars($parcel['special_instruction'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Metadata</div>
            <div class="card-body py-2">
                <div class="row small">
                    <div class="col-sm-6">
                        <span class="text-muted">Booked By:</span> <?= htmlspecialchars($parcel['creator_name']) ?>
                    </div>
                    <div class="col-sm-6 text-end">
                        <span class="text-muted">Booking Time:</span> <?= date('Y-m-d H:i', strtotime($parcel['created_at'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Sender Card -->
        <div class="card shadow-sm mb-4 border-start border-primary border-4">
            <div class="card-header bg-white fw-bold">Sender (Customer)</div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($parcel['sender_name']) ?></h5>
                <p class="card-text mb-1"><i class="bi bi-building me-2 text-muted"></i><?= htmlspecialchars($parcel['sender_company'] ?: 'Individual') ?></p>
                <p class="card-text mb-1"><i class="bi bi-telephone me-2 text-muted"></i><?= htmlspecialchars($parcel['sender_phone']) ?></p>
                <p class="card-text"><i class="bi bi-hash me-2 text-muted"></i><?= htmlspecialchars($parcel['customer_code']) ?></p>
            </div>
        </div>

        <!-- Receiver Card -->
        <div class="card shadow-sm mb-4 border-start border-success border-4">
            <div class="card-header bg-white fw-bold">Receiver</div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($parcel['receiver_name']) ?></h5>
                <p class="card-text mb-1"><i class="bi bi-telephone me-2 text-muted"></i><?= htmlspecialchars($parcel['receiver_phone']) ?></p>
                <p class="card-text mb-1"><i class="bi bi-geo-alt me-2 text-muted"></i><?= htmlspecialchars($parcel['receiver_address']) ?></p>
                <p class="card-text"><i class="bi bi-map me-2 text-muted"></i><?= htmlspecialchars($parcel['receiver_district']) ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
