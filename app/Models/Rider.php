<?php

namespace App\Models;

use Core\Model;
use PDO;

class Rider extends Model {
    public function generateRiderCode() {
        $stmt = $this->db->query("SELECT MAX(id) FROM riders");
        $maxId = (int)$stmt->fetchColumn();
        $nextId = $maxId + 1;
        return 'RID-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO riders (
                    user_id, rider_code, branch_id, vehicle_type, 
                    license_number, nid_number, joining_date, status
                ) VALUES (
                    :user_id, :rider_code, :branch_id, :vehicle_type, 
                    :license_number, :nid_number, :joining_date, :status
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE riders SET 
                    branch_id = :branch_id, 
                    vehicle_type = :vehicle_type, 
                    license_number = :license_number, 
                    nid_number = :nid_number, 
                    joining_date = :joining_date, 
                    status = :status
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($id) {
        $sql = "SELECT r.*, u.name as rider_name, u.email, u.mobile, b.branch_name 
                FROM riders r 
                JOIN users u ON r.user_id = u.id 
                JOIN branches b ON r.branch_id = b.id 
                WHERE r.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByUserId($userId) {
        $sql = "SELECT r.*, u.name as rider_name, b.branch_name 
                FROM riders r 
                JOIN users u ON r.user_id = u.id 
                JOIN branches b ON r.branch_id = b.id 
                WHERE r.user_id = :user_id AND r.deleted_at IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function searchAndFilter($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT r.*, u.name as rider_name, b.branch_name 
                FROM riders r 
                JOIN users u ON r.user_id = u.id 
                JOIN branches b ON r.branch_id = b.id 
                WHERE r.deleted_at IS NULL";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (r.rider_code LIKE :search OR u.name LIKE :search OR u.email LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['branch_id'])) {
            $sql .= " AND r.branch_id = :branch_id";
            $params['branch_id'] = $filters['branch_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND r.status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY r.id DESC LIMIT :limit OFFSET :offset";
        
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
        $sql = "SELECT COUNT(*) FROM riders r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.deleted_at IS NULL";
        $params = [];
        if (!empty($filters['search'])) {
            $sql .= " AND (r.rider_code LIKE :search OR u.name LIKE :search OR u.email LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['branch_id'])) {
            $sql .= " AND r.branch_id = :branch_id";
            $params['branch_id'] = $filters['branch_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND r.status = :status";
            $params['status'] = $filters['status'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function softDelete($id) {
        $sql = "UPDATE riders SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function restore($id) {
        $sql = "UPDATE riders SET deleted_at = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
