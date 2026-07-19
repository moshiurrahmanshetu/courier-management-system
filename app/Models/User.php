<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {
    public function create($data) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO users (name, email, password, role, created_at) VALUES (:name, :email, :password, :role, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => 'General User'
            ]);
            $userId = $this->db->lastInsertId();

            // Assign default 'General User' role
            $stmtRole = $this->db->prepare("SELECT id FROM roles WHERE slug = 'general-user' LIMIT 1");
            $stmtRole->execute();
            $role = $stmtRole->fetch();
            if ($role) {
                $stmtAssign = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $stmtAssign->execute([$userId, $role['id']]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET name = :name, email = :email, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'id' => $id
        ]);
    }

    public function updateAvatar($id, $avatar) {
        $sql = "UPDATE users SET avatar = :avatar, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'avatar' => $avatar,
            'id' => $id
        ]);
    }

    public function updatePassword($id, $password) {
        $sql = "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $id
        ]);
    }

    public function getRole($userId) {
        $sql = "SELECT r.* FROM roles r 
                JOIN user_roles ur ON r.id = ur.role_id 
                WHERE ur.user_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function assignRole($userId, $roleId) {
        $this->db->beginTransaction();
        try {
            // Delete existing role (Phase 2 requirement: one role per user)
            $stmtDelete = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $stmtDelete->execute([$userId]);

            // Assign new role
            $stmtInsert = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $stmtInsert->execute([$userId, $roleId]);

            // Update role name in users table for backward compatibility/quick access
            $stmtRole = $this->db->prepare("SELECT role_name FROM roles WHERE id = ?");
            $stmtRole->execute([$roleId]);
            $role = $stmtRole->fetch();
            if ($role) {
                $stmtUpdate = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
                $stmtUpdate->execute([$role['role_name'], $userId]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getPermissions($userId) {
        $sql = "SELECT DISTINCT p.permission_key FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                JOIN user_roles ur ON rp.role_id = ur.role_id 
                WHERE ur.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
