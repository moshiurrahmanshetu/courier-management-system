<?php

namespace App\Models;

use Core\Model;
use PDO;

class Role extends Model {
    public function all() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO roles (role_name, slug, description, status) VALUES (:role_name, :slug, :description, :status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE roles SET role_name = :role_name, slug = :slug, description = :description, status = :status WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM roles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPermissions($roleId) {
        $sql = "SELECT p.* FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                WHERE rp.role_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleId]);
        return $stmt->fetchAll();
    }

    public function syncPermissions($roleId, $permissionIds) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM role_permissions WHERE role_id = ?");
            $stmt->execute([$roleId]);

            if (!empty($permissionIds)) {
                $stmt = $this->db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
                foreach ($permissionIds as $pId) {
                    $stmt->execute([$roleId, $pId]);
                }
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
