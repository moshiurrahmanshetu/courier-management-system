<?php

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/core/Bootstrap.php';
Core\Bootstrap::init();

use Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Running Rider and Assignment migrations...\n";
    $sqlFile = '011_riders.sql';
    $path = ROOT_PATH . '/database/sql/' . $sqlFile;
    
    if (file_exists($path)) {
        $sql = file_get_contents($path);
        $db->exec($sql);
        echo "Successfully migrated riders and parcel_assignments.\n";
        
        // Auto-assign permissions to Super Admin
        $stmt = $db->prepare("SELECT id FROM roles WHERE slug = 'super-admin' LIMIT 1");
        $stmt->execute();
        $superAdmin = $stmt->fetch();
        
        if ($superAdmin) {
            $roleId = $superAdmin['id'];
            $stmt = $db->query("SELECT id FROM permissions WHERE module IN ('Riders', 'Assignments')");
            $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $stmtInsert = $db->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($perms as $pId) {
                $stmtInsert->execute([$roleId, $pId]);
            }
            echo "Rider and Assignment permissions assigned to Super Admin.\n";
        }
    }
    echo "Phase 7 migration completed successfully.\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
