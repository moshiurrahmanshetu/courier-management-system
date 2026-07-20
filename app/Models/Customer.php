<?php

namespace App\Models;

use Core\Model;
use PDO;

class Customer extends Model {
    public function generateCustomerCode() {
        $stmt = $this->db->query("SELECT MAX(id) FROM customers");
        $maxId = (int)$stmt->fetchColumn();
        $nextId = $maxId + 1;
        return 'CUS-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    public function isUserAssigned($userId, $excludeCustomerId = null) {
        $sql = "SELECT id FROM customers WHERE user_id = :user_id AND deleted_at IS NULL";
        if ($excludeCustomerId) {
            $sql .= " AND id != :customer_id";
        }
        $stmt = $this->db->prepare($sql);
        $params = ['user_id' => $userId];
        if ($excludeCustomerId) {
            $params['customer_id'] = $excludeCustomerId;
        }
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO customers (
                    user_id, customer_code, company_name, contact_person, phone, 
                    alternative_phone, email, nid_number, trade_license, country, 
                    division, district, upazila, postcode, address, customer_type, 
                    status, notes, created_at
                ) VALUES (
                    :user_id, :customer_code, :company_name, :contact_person, :phone, 
                    :alternative_phone, :email, :nid_number, :trade_license, :country, 
                    :division, :district, :upazila, :postcode, :address, :customer_type, 
                    :status, :notes, NOW()
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE customers SET 
                    company_name = :company_name, 
                    contact_person = :contact_person, 
                    phone = :phone, 
                    alternative_phone = :alternative_phone, 
                    email = :email, 
                    nid_number = :nid_number, 
                    trade_license = :trade_license, 
                    country = :country, 
                    division = :division, 
                    district = :district, 
                    upazila = :upazila, 
                    postcode = :postcode, 
                    address = :address, 
                    customer_type = :customer_type, 
                    status = :status, 
                    notes = :notes,
                    updated_at = NOW()
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($id) {
        $sql = "SELECT c.*, u.name as user_name, u.avatar as user_avatar, u.username as user_handle 
                FROM customers c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function searchAndFilter($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT c.*, u.avatar as user_avatar FROM customers c 
                JOIN users u ON c.user_id = u.id 
                WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['include_deleted']) && $filters['include_deleted']) {
            // No deleted_at filter
        } else {
            $sql .= " AND c.deleted_at IS NULL";
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (c.customer_code LIKE :search OR c.company_name LIKE :search OR c.contact_person LIKE :search OR c.phone LIKE :search OR c.email LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['customer_type'])) {
            $sql .= " AND c.customer_type = :customer_type";
            $params['customer_type'] = $filters['customer_type'];
        }

        $sql .= " ORDER BY c.id DESC LIMIT :limit OFFSET :offset";
        
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
        $sql = "SELECT COUNT(*) FROM customers c WHERE 1=1";
        $params = [];
        
        if (isset($filters['include_deleted']) && $filters['include_deleted']) {
        } else {
            $sql .= " AND c.deleted_at IS NULL";
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (c.customer_code LIKE :search OR c.company_name LIKE :search OR c.contact_person LIKE :search OR c.phone LIKE :search OR c.email LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['customer_type'])) {
            $sql .= " AND c.customer_type = :customer_type";
            $params['customer_type'] = $filters['customer_type'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function softDelete($id) {
        $sql = "UPDATE customers SET deleted_at = NOW(), status = 'inactive' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function restore($id) {
        $sql = "UPDATE customers SET deleted_at = NULL, status = 'active' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
