<?php

namespace App\Models;

use Core\Model;

class Receiver extends Model {
    public function create($data) {
        $sql = "INSERT INTO parcel_receivers (
                    receiver_name, phone, alternative_phone, country, 
                    division, district, upazila, postcode, address, landmark
                ) VALUES (
                    :receiver_name, :phone, :alternative_phone, :country, 
                    :division, :district, :upazila, :postcode, :address, :landmark
                )";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $sql = "UPDATE parcel_receivers SET 
                    receiver_name = :receiver_name, 
                    phone = :phone, 
                    alternative_phone = :alternative_phone, 
                    country = :country, 
                    division = :division, 
                    district = :district, 
                    upazila = :upazila, 
                    postcode = :postcode, 
                    address = :address, 
                    landmark = :landmark
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM parcel_receivers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
