CREATE TABLE IF NOT EXISTS branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_code VARCHAR(20) NOT NULL UNIQUE,
    branch_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    manager_name VARCHAR(255) DEFAULT NULL,
    country VARCHAR(100) DEFAULT 'Bangladesh',
    division VARCHAR(100) DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    upazila VARCHAR(100) DEFAULT NULL,
    postcode VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS parcel_tracking_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parcel_id INT NOT NULL,
    branch_id INT DEFAULT NULL,
    status VARCHAR(50) NOT NULL,
    remarks TEXT DEFAULT NULL,
    updated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parcel_id) REFERENCES parcels(id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO permissions (permission_name, permission_key, module, description) VALUES 
('View Branches', 'branch.view', 'Branches', 'Access to view branch list and details'),
('Create Branch', 'branch.create', 'Branches', 'Access to create new branches'),
('Edit Branch', 'branch.edit', 'Branches', 'Access to edit branch information'),
('Delete Branch', 'branch.delete', 'Branches', 'Access to delete branches'),
('View Tracking', 'tracking.view', 'Tracking', 'Access to view parcel tracking timeline'),
('Update Tracking', 'tracking.update', 'Tracking', 'Access to update parcel status and tracking logs');
