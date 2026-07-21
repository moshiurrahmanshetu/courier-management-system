CREATE TABLE IF NOT EXISTS riders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    rider_code VARCHAR(20) NOT NULL UNIQUE,
    branch_id INT NOT NULL,
    vehicle_type VARCHAR(50) DEFAULT NULL,
    license_number VARCHAR(100) DEFAULT NULL,
    nid_number VARCHAR(50) DEFAULT NULL,
    joining_date DATE DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS parcel_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parcel_id INT NOT NULL,
    rider_id INT NOT NULL,
    assignment_type ENUM('pickup', 'delivery') NOT NULL,
    assigned_by INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted_at TIMESTAMP NULL DEFAULT NULL,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    status ENUM('Assigned', 'Accepted', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Assigned',
    remarks TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parcel_id) REFERENCES parcels(id) ON DELETE CASCADE,
    FOREIGN KEY (rider_id) REFERENCES riders(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO permissions (permission_name, permission_key, module, description) VALUES 
('View Riders', 'rider.view', 'Riders', 'Access to view rider list and details'),
('Create Rider', 'rider.create', 'Riders', 'Access to create new rider profiles'),
('Edit Rider', 'rider.edit', 'Riders', 'Access to edit rider information'),
('Delete Rider', 'rider.delete', 'Riders', 'Access to delete/restore riders'),
('View Assignments', 'assignment.view', 'Assignments', 'Access to view parcel assignments'),
('Create Assignment', 'assignment.create', 'Assignments', 'Access to assign parcels to riders'),
('Edit Assignment', 'assignment.edit', 'Assignments', 'Access to edit assignment status'),
('Delete Assignment', 'assignment.delete', 'Assignments', 'Access to cancel assignments');
