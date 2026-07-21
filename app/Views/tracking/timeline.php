<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 30px;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: -30px;
    width: 2px;
    background: #e9ecef;
}
.timeline-item:last-child::before {
    display: none;
}
.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}
.timeline-content {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.timeline-date {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 5px;
}
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Tracking Timeline: <?= htmlspecialchars($parcel['tracking_number']) ?></h1>
    <a href="<?= $_ENV['APP_URL'] ?>/parcels" class="btn btn-secondary btn-sm">Back to Parcels</a>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Parcel Status History</div>
            <div class="card-body">
                <div class="timeline">
                    <?php if (empty($logs)): ?>
                        <p class="text-center text-muted">No tracking history found.</p>
                    <?php endif; ?>
                    <?php foreach ($logs as $log): ?>
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi bi-check2 text-primary"></i>
                        </div>
                        <div class="timeline-content shadow-sm">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-0 fw-bold"><?= $log['status'] ?></h6>
                                <div class="timeline-date"><?= date('M d, Y H:i', strtotime($log['created_at'])) ?></div>
                            </div>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-building me-1"></i> <?= $log['branch_name'] ?: 'N/A' ?>
                            </div>
                            <?php if ($log['remarks']): ?>
                                <p class="mb-1 small"><?= nl2br(htmlspecialchars($log['remarks'])) ?></p>
                            <?php endif; ?>
                            <div class="small text-muted mt-2">
                                <i class="bi bi-person me-1"></i> Updated by: <?= htmlspecialchars($log['updated_by_name']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <?php if (can('tracking.update')): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">Update Status</div>
            <div class="card-body">
                <form action="<?= $_ENV['APP_URL'] ?>/tracking/update" method="POST">
                    <?= \App\Helpers\CSRF::field() ?>
                    <input type="hidden" name="parcel_id" value="<?= $parcel['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-select" required>
                            <?php
                            $statuses = [
                                'Booked', 'Pickup Requested', 'Picked Up', 'Received at Origin Branch',
                                'Sorting', 'Dispatched', 'In Transit', 'Received at Destination Branch',
                                'Out For Delivery', 'Delivered', 'Returned', 'Cancelled', 'Hold'
                            ];
                            foreach ($statuses as $status): ?>
                                <option value="<?= $status ?>" <?= $parcel['current_status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">-- Select Branch --</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['branch_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Add any specific details..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">Parcel Summary</div>
            <div class="card-body small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tracking #:</span>
                    <span class="fw-bold"><?= $parcel['tracking_number'] ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Invoice #:</span>
                    <span><?= $parcel['invoice_number'] ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Current Status:</span>
                    <span class="badge bg-info"><?= $parcel['current_status'] ?></span>
                </div>
                <hr>
                <div class="mb-1 fw-bold text-muted">Sender</div>
                <div><?= htmlspecialchars($parcel['sender_name']) ?></div>
                <div class="text-muted"><?= htmlspecialchars($parcel['sender_phone']) ?></div>
                
                <div class="mt-2 mb-1 fw-bold text-muted">Receiver</div>
                <div><?= htmlspecialchars($parcel['receiver_name']) ?></div>
                <div class="text-muted"><?= htmlspecialchars($parcel['receiver_phone']) ?></div>
                <div class="text-muted"><?= htmlspecialchars($parcel['receiver_address']) ?></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
