<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 text-center">
                <h1 class="display-1 fw-bold text-danger">403</h1>
                <h2 class="mb-4">Access Denied</h2>
                <p class="text-muted mb-4">You do not have permission to access this resource.</p>
                <a href="<?= $_ENV['APP_URL'] ?>/dashboard" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
