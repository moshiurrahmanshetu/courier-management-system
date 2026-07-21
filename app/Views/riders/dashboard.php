<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Rider Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="badge bg-primary p-2">Rider: <?= htmlspecialchars($rider['rider_code']) ?></span>
    </div>
</div>

<?php if ($success = \App\Helpers\Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if ($error = \App\Helpers\Session::flash('error')): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">Completed Today</h6>
                <h2 class="display-4 mb-0"><?= $stats['completed_today'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-dark shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">Pending Tasks</h6>
                <h2 class="display-4 mb-0"><?= $stats['pending'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold">Active Assignments</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Tracking #</th>
                        <th>Customer/Receiver</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tasks)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No active assignments.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td>
                            <span class="badge bg-<?= $task['assignment_type'] === 'pickup' ? 'info' : 'primary' ?>">
                                <?= ucfirst($task['assignment_type']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold"><?= $task['tracking_number'] ?></div>
                            <small class="text-muted">INV: <?= $task['invoice_number'] ?></small>
                        </td>
                        <td>
                            <?php if ($task['assignment_type'] === 'pickup'): ?>
                                <div>Sender: <?= htmlspecialchars($task['sender_name']) ?></div>
                            <?php else: ?>
                                <div>Receiver: <?= htmlspecialchars($task['receiver_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($task['receiver_phone']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="small text-truncate" style="max-width: 200px;">
                                <?= htmlspecialchars($task['receiver_address']) ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= $task['status'] ?></span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?= $task['id'] ?>">
                                Update
                            </button>
                            
                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal<?= $task['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content text-start">
                                        <form action="<?= $_ENV['APP_URL'] ?>/assignments/update-status" method="POST">
                                            <?= \App\Helpers\CSRF::field() ?>
                                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Assignment Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Tracking #: <strong><?= $task['tracking_number'] ?></strong></p>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Accepted" <?= $task['status'] === 'Accepted' ? 'selected' : '' ?>>Accept Task</option>
                                                        <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                                        <option value="Completed" <?= $task['status'] === 'Completed' ? 'selected' : '' ?>>Mark as Completed</option>
                                                        <option value="Cancelled" <?= $task['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancel Task</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Remarks</label>
                                                    <textarea name="remarks" class="form-control" rows="3"><?= htmlspecialchars($task['remarks']) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
