<?php

namespace App\Models;

use Core\Model;
use PDO;

class ActivityLog extends Model {
    public function log($userId, $action, $description) {
        $sql = "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) 
                VALUES (:user_id, :action, :description, :ip_address, :user_agent)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    public function all($limit = 50, $offset = 0) {
        $sql = "SELECT al.*, u.name as user_name FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();
    }
}
