<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model {
    public function create($data) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO users (name, username, email, mobile, password, role, gender, dob, address, status, password_change_required, created_at) 
                    VALUES (:name, :username, :email, :mobile, :password, :role, :gender, :dob, :address, :status, :password_change_required, NOW())";
            $stmt = $this->db->prepare($sql);
            
            $roleSlug = $data['role_slug'] ?? 'general-user';
            $stmtRole = $this->db->prepare("SELECT id, role_name FROM roles WHERE slug = ? LIMIT 1");
            $stmtRole->execute([$roleSlug]);
            $role = $stmtRole->fetch();
            
            $stmt->execute([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $role['role_name'] ?? 'General User',
                'gender' => $data['gender'] ?? 'other',
                'dob' => $data['dob'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => $data['status'] ?? 'active',
                'password_change_required' => $data['password_change_required'] ?? 0
            ]);
            $userId = $this->db->lastInsertId();

            if ($role) {
                $stmtAssign = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $stmtAssign->execute([$userId, $role['id']]);
            }

            $this->db->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET 
                name = :name, 
                email = :email, 
                mobile = :mobile,
                gender = :gender,
                dob = :dob,
                address = :address,
                status = :status,
                updated_at = NOW() 
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateAvatar($id, $avatar) {
        $sql = "UPDATE users SET avatar = :avatar, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'avatar' => $avatar,
            'id' => $id
        ]);
    }

    public function updatePassword($id, $password, $changeRequired = 0) {
        $sql = "UPDATE users SET password = :password, password_change_required = :pcr, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'pcr' => $changeRequired,
            'id' => $id
        ]);
    }

    public function softDelete($id) {
        $sql = "UPDATE users SET deleted_at = NOW(), status = 'inactive' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function updateLoginInfo($id, $ip) {
        $sql = "UPDATE users SET last_login_at = NOW(), last_login_ip = :ip WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'ip' => $ip]);
    }

    public function searchAndFilter($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT u.*, r.role_name as current_role_name FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.deleted_at IS NULL";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (u.name LIKE :search OR u.email LIKE :search OR u.mobile LIKE :search OR u.username LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['role'])) {
            $sql .= " AND r.slug = :role";
            $params['role'] = $filters['role'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND u.status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY u.id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll($filters = []) {
        $sql = "SELECT COUNT(*) FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.deleted_at IS NULL";
        
        $params = [];
        if (!empty($filters['search'])) {
            $sql .= " AND (u.name LIKE :search OR u.email LIKE :search OR u.mobile LIKE :search OR u.username LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['role'])) {
            $sql .= " AND r.slug = :role";
            $params['role'] = $filters['role'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND u.status = :status";
            $params['status'] = $filters['status'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
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
