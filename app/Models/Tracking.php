<?php

namespace App\Models;

use Core\Model;
use PDO;

class Tracking extends Model {
    public function addLog($data) {
        $this->db->beginTransaction();
        try {
            // 1. Insert new tracking log
            $sql = "INSERT INTO parcel_tracking_logs (parcel_id, branch_id, status, remarks, updated_by, created_at) 
                    VALUES (:parcel_id, :branch_id, :status, :remarks, :updated_by, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);

            // 2. Update current status in parcels table
            $sql = "UPDATE parcels SET current_status = :status, updated_at = NOW() WHERE id = :parcel_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'status' => $data['status'],
                'parcel_id' => $data['parcel_id']
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getLogsByParcelId($parcelId) {
        $sql = "SELECT l.*, b.branch_name, u.name as updated_by_name 
                FROM parcel_tracking_logs l 
                LEFT JOIN branches b ON l.branch_id = b.id 
                JOIN users u ON l.updated_by = u.id 
                WHERE l.parcel_id = :parcel_id 
                ORDER BY l.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['parcel_id' => $parcelId]);
        return $stmt->fetchAll();
    }
}
