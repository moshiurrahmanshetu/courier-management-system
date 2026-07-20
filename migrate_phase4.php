<?php

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/core/Bootstrap.php';
Core\Bootstrap::init();

use Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $sqlFile = '008_customers.sql';
    $path = ROOT_PATH . '/database/sql/' . $sqlFile;
    
    if (file_exists($path)) {
        echo "Migrating $sqlFile...\n";
        $sql = file_get_contents($path);
        $db->exec($sql);
        echo "Successfully migrated $sqlFile.\n";
        
        // Auto-assign permissions to Super Admin
        $stmt = $db->prepare("SELECT id FROM roles WHERE slug = 'super-admin' LIMIT 1");
        $stmt->execute();
        $superAdmin = $stmt->fetch();
        
        if ($superAdmin) {
            $roleId = $superAdmin['id'];
            $stmt = $db->query("SELECT id FROM permissions WHERE module = 'Customers'");
            $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $stmtInsert = $db->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($perms as $pId) {
                $stmtInsert->execute([$roleId, $pId]);
            }
            echo "Customer permissions assigned to Super Admin.\n";
        }
    }
    echo "Phase 4 migration completed successfully.\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
