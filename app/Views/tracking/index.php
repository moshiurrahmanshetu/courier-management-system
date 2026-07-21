<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center py-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Track Your Parcel</h2>
                <p class="text-center text-muted mb-4">Enter your tracking number to see the latest updates on your shipment.</p>
                
                <?php if ($error = \App\Helpers\Session::flash('error')): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="<?= $_ENV['APP_URL'] ?>/tracking" method="GET">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" name="tracking_number" class="form-control" placeholder="TRK-2026XXXXXX" required>
                        <button class="btn btn-primary" type="submit">Track</button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <a href="<?= $_ENV['APP_URL'] ?>/parcels" class="text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Back to Parcels
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
