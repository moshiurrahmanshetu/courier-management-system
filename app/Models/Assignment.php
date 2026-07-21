<?php

namespace App\Models;

use Core\Model;
use PDO;

class Assignment extends Model {
    public function hasActiveAssignment($parcelId, $type) {
        $sql = "SELECT COUNT(*) FROM parcel_assignments 
                WHERE parcel_id = :parcel_id AND assignment_type = :type 
                AND status NOT IN ('Completed', 'Cancelled')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['parcel_id' => $parcelId, 'type' => $type]);
        return $stmt->fetchColumn() > 0;
    }

    public function create($data) {
        $sql = "INSERT INTO parcel_assignments (
                    parcel_id, rider_id, assignment_type, assigned_by, 
                    remarks, status, assigned_at
                ) VALUES (
                    :parcel_id, :rider_id, :assignment_type, :assigned_by, 
                    :remarks, 'Assigned', NOW()
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateStatus($id, $status, $remarks = null) {
        $sql = "UPDATE parcel_assignments SET status = :status";
        $params = ['id' => $id, 'status' => $status];
        
        if ($status === 'Accepted') {
            $sql .= ", accepted_at = NOW()";
        } elseif ($status === 'Completed') {
            $sql .= ", completed_at = NOW()";
        }
        
        if ($remarks !== null) {
            $sql .= ", remarks = :remarks";
            $params['remarks'] = $remarks;
        }
        
        $sql .= " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function find($id) {
        $sql = "SELECT a.*, p.tracking_number, p.invoice_number, r.rider_code, u.name as rider_name 
                FROM parcel_assignments a 
                JOIN parcels p ON a.parcel_id = p.id 
                JOIN riders r ON a.rider_id = r.id 
                JOIN users u ON r.user_id = u.id 
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getRiderTasks($riderId, $status = null) {
        $sql = "SELECT a.*, p.tracking_number, p.invoice_number, p.current_status as parcel_status,
                       c.contact_person as sender_name, pr.receiver_name, pr.phone as receiver_phone, pr.address as receiver_address
                FROM parcel_assignments a 
                JOIN parcels p ON a.parcel_id = p.id 
                JOIN customers c ON p.customer_id = c.id 
                JOIN parcel_receivers pr ON p.receiver_id = pr.id 
                WHERE a.rider_id = :rider_id";
        $params = ['rider_id' => $riderId];
        
        if ($status) {
            $sql .= " AND a.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY a.assigned_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getRiderStats($riderId) {
        $stats = [];
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM parcel_assignments WHERE rider_id = ? AND status = 'Completed' AND DATE(completed_at) = CURDATE()");
        $stmt->execute([$riderId]);
        $stats['completed_today'] = $stmt->fetchColumn();
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM parcel_assignments WHERE rider_id = ? AND status NOT IN ('Completed', 'Cancelled')");
        $stmt->execute([$riderId]);
        $stats['pending'] = $stmt->fetchColumn();
        
        return $stats;
    }

    public function searchAndFilter($filters = [], $limit = 10, $offset = 0) {
        $sql = "SELECT a.*, p.tracking_number, u.name as rider_name, b.branch_name 
                FROM parcel_assignments a 
                JOIN parcels p ON a.parcel_id = p.id 
                JOIN riders r ON a.rider_id = r.id 
                JOIN users u ON r.user_id = u.id 
                JOIN branches b ON r.branch_id = b.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.tracking_number LIKE :search OR u.name LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['type'])) {
            $sql .= " AND a.assignment_type = :type";
            $params['type'] = $filters['type'];
        }

        $sql .= " ORDER BY a.id DESC LIMIT :limit OFFSET :offset";
        
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
        $sql = "SELECT COUNT(*) FROM parcel_assignments a 
                JOIN parcels p ON a.parcel_id = p.id 
                JOIN riders r ON a.rider_id = r.id 
                JOIN users u ON r.user_id = u.id 
                WHERE 1=1";
        $params = [];
        if (!empty($filters['search'])) {
            $sql .= " AND (p.tracking_number LIKE :search OR u.name LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND a.assignment_type = :type";
            $params['type'] = $filters['type'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
