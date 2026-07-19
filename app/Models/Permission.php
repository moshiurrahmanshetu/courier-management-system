<?php

namespace App\Models;

use Core\Model;
use PDO;

class Permission extends Model {
    public function all() {
        $stmt = $this->db->query("SELECT * FROM permissions ORDER BY module, permission_name");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM permissions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO permissions (permission_name, permission_key, module, description) VALUES (:permission_name, :permission_key, :module, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE permissions SET permission_name = :permission_name, permission_key = :permission_key, module = :module, description = :description WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM permissions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getGrouped() {
        $permissions = $this->all();
        $grouped = [];
        foreach ($permissions as $p) {
            $grouped[$p['module']][] = $p;
        }
        return $grouped;
    }
}
