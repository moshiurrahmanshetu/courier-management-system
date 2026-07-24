<?php
require_once __DIR__ . '/core/Bootstrap.php';
Core\Bootstrap::init();

use Core\Database;
use App\Models\User;
use App\Models\ActivityLog;

echo "--- Phase 3 Verification ---\n";

try {
    $db = Database::getInstance()->getConnection();
    echo "[PASS] Database connection established.\n";

    // Check tables
    $tables = ['users', 'roles', 'permissions', 'user_roles', 'role_permissions', 'activity_logs'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "[PASS] Table '$table' exists.\n";
        } else {
            echo "[FAIL] Table '$table' is missing!\n";
        }
    }

    // Check users table columns
    $columns = ['username', 'mobile', 'avatar', 'gender', 'dob', 'address', 'status', 'email_verified_at', 'last_login_at', 'last_login_ip', 'password_change_required', 'deleted_at'];
    foreach ($columns as $col) {
        $stmt = $db->query("SHOW COLUMNS FROM users LIKE '$col'");
        if ($stmt->rowCount() > 0) {
            echo "[PASS] Column 'users.$col' exists.\n";
        } else {
            echo "[FAIL] Column 'users.$col' is missing!\n";
        }
    }

    // Check permissions
    $requiredPermissions = [
        'users.view', 'users.create', 'users.edit', 'users.delete',
        'profile.view', 'profile.edit', 'activity_logs.view'
    ];
    foreach ($requiredPermissions as $p) {
        $stmt = $db->prepare("SELECT id FROM permissions WHERE permission_key = ?");
        $stmt->execute([$p]);
        if ($stmt->fetch()) {
            echo "[PASS] Permission '$p' is seeded.\n";
        } else {
            echo "[FAIL] Permission '$p' is missing!\n";
        }
    }

} catch (Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
}
