<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Activity Logs</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Browser</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No activity logs found.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td class="small"><?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($log['user_name'] ?? 'System') ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($log['action']) ?></span>
                        </td>
                        <td class="small"><?= htmlspecialchars($log['description']) ?></td>
                        <td><code class="small"><?= htmlspecialchars($log['ip_address']) ?></code></td>
                        <td class="small text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($log['user_agent']) ?>">
                            <?= htmlspecialchars($log['user_agent']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
