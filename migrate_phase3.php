<?php

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/core/Bootstrap.php';
Core\Bootstrap::init();

use Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $sqlFiles = [
        '006_update_users_table.sql',
        '007_activity_logs.sql'
    ];

    foreach ($sqlFiles as $file) {
        $path = ROOT_PATH . '/database/sql/' . $file;
        if (file_exists($path)) {
            echo "Migrating $file...\n";
            $sql = file_get_contents($path);
            $db->exec($sql);
            echo "Successfully migrated $file.\n";
        }
    }
    echo "Phase 3 migrations completed successfully.\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
