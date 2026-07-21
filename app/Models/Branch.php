<?php

namespace App\Models;

use Core\Model;
use PDO;

class Branch extends Model {
    public function generateBranchCode() {
        $stmt = $this->db->query("SELECT MAX(id) FROM branches");
        $maxId = (int)$stmt->fetchColumn();
        $nextId = $maxId + 1;
        return 'BR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO branches (
                    branch_code, branch_name, phone, email, manager_name, 
                    country, division, district, upazila, postcode, address, status
                ) VALUES (
                    :branch_code, :branch_name, :phone, :email, :manager_name, 
                    :country, :division, :district, :upazila, :postcode, :address, :status
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE branches SET 
                    branch_name = :branch_name, 
                    phone = :phone, 
                    email = :email, 
                    manager_name = :manager_name, 
                    country = :country, 
                    division = :division, 
                    district = :district, 
                    upazila = :upazila, 
                    postcode = :postcode, 
                    address = :address, 
                    status = :status
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM branches WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function searchAndFilter($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM branches WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (branch_code LIKE :search OR branch_name LIKE :search OR manager_name LIKE :search OR phone LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }

        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";
        
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
        $sql = "SELECT COUNT(*) FROM branches WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (branch_code LIKE :search OR branch_name LIKE :search OR manager_name LIKE :search OR phone LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params['status'] = $filters['status'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
