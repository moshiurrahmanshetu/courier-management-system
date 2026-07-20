<?php

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/core/Bootstrap.php';
Core\Bootstrap::init();

use Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Running Parcel and Receiver migrations...\n";
    $sqlFile = '007_parcels.sql';
    $path = ROOT_PATH . '/database/sql/' . $sqlFile;
    
    if (file_exists($path)) {
        $sql = file_get_contents($path);
        $db->exec($sql);
        echo "Successfully migrated parcels and parcel_receivers.\n";
        
        // Auto-assign permissions to Super Admin
        $stmt = $db->prepare("SELECT id FROM roles WHERE slug = 'super-admin' LIMIT 1");
        $stmt->execute();
        $superAdmin = $stmt->fetch();
        
        if ($superAdmin) {
            $roleId = $superAdmin['id'];
            $stmt = $db->query("SELECT id FROM permissions WHERE module = 'Parcels'");
            $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $stmtInsert = $db->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($perms as $pId) {
                $stmtInsert->execute([$roleId, $pId]);
            }
            echo "Parcel permissions assigned to Super Admin.\n";
        }
    }
    echo "Phase 5 migration completed successfully.\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
