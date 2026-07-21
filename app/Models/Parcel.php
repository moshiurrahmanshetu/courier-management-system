<?php

namespace App\Models;

use Core\Model;
use PDO;

class Parcel extends Model {
    public function generateTrackingNumber() {
        $year = date('Y');
        $stmt = $this->db->query("SELECT MAX(id) FROM parcels");
        $maxId = (int)$stmt->fetchColumn();
        $nextId = $maxId + 1;
        return 'TRK-' . $year . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    public function generateInvoiceNumber() {
        $year = date('Y');
        $stmt = $this->db->query("SELECT MAX(id) FROM parcels");
        $maxId = (int)$stmt->fetchColumn();
        $nextId = $maxId + 1;
        return 'INV-' . $year . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO parcels (
                    tracking_number, invoice_number, customer_id, receiver_id, 
                    parcel_type, delivery_type, weight, quantity, declared_value, 
                    delivery_charge, cod_amount, special_instruction, current_status, 
                    booking_date, created_by, created_at
                ) VALUES (
                    :tracking_number, :invoice_number, :customer_id, :receiver_id, 
                    :parcel_type, :delivery_type, :weight, :quantity, :declared_value, 
                    :delivery_charge, :cod_amount, :special_instruction, :current_status, 
                    :booking_date, :created_by, NOW()
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE parcels SET 
                    parcel_type = :parcel_type, 
                    delivery_type = :delivery_type, 
                    weight = :weight, 
                    quantity = :quantity, 
                    declared_value = :declared_value, 
                    delivery_charge = :delivery_charge, 
                    cod_amount = :cod_amount, 
                    special_instruction = :special_instruction,
                    updated_at = NOW()
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($id) {
        $sql = "SELECT p.*, 
                       c.customer_code, c.company_name as sender_company, c.contact_person as sender_name, c.phone as sender_phone,
                       r.receiver_name, r.phone as receiver_phone, r.address as receiver_address, r.district as receiver_district,
                       u.name as creator_name
                FROM parcels p 
                JOIN customers c ON p.customer_id = c.id 
                JOIN parcel_receivers r ON p.receiver_id = r.id 
                JOIN users u ON p.created_by = u.id 
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function searchAndFilter($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT p.*, c.contact_person as sender_name, r.receiver_name, r.phone as receiver_phone 
                FROM parcels p 
                JOIN customers c ON p.customer_id = c.id 
                JOIN parcel_receivers r ON p.receiver_id = r.id 
                WHERE p.deleted_at IS NULL";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.tracking_number LIKE :search OR p.invoice_number LIKE :search OR c.contact_person LIKE :search OR r.receiver_name LIKE :search OR r.phone LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND p.current_status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['parcel_type'])) {
            $sql .= " AND p.parcel_type = :parcel_type";
            $params['parcel_type'] = $filters['parcel_type'];
        }

        if (!empty($filters['delivery_type'])) {
            $sql .= " AND p.delivery_type = :delivery_type";
            $params['delivery_type'] = $filters['delivery_type'];
        }

        if (!empty($filters['booking_date'])) {
            $sql .= " AND p.booking_date = :booking_date";
            $params['booking_date'] = $filters['booking_date'];
        }

        $sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
        
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
        $sql = "SELECT COUNT(*) FROM parcels p 
                JOIN customers c ON p.customer_id = c.id 
                JOIN parcel_receivers r ON p.receiver_id = r.id 
                WHERE p.deleted_at IS NULL";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.tracking_number LIKE :search OR p.invoice_number LIKE :search OR c.contact_person LIKE :search OR r.receiver_name LIKE :search OR r.phone LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['status'])) {
            $sql .= " AND p.current_status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['parcel_type'])) {
            $sql .= " AND p.parcel_type = :parcel_type";
            $params['parcel_type'] = $filters['parcel_type'];
        }
        if (!empty($filters['delivery_type'])) {
            $sql .= " AND p.delivery_type = :delivery_type";
            $params['delivery_type'] = $filters['delivery_type'];
        }
        if (!empty($filters['booking_date'])) {
            $sql .= " AND p.booking_date = :booking_date";
            $params['booking_date'] = $filters['booking_date'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function softDelete($id) {
        $sql = "UPDATE parcels SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
