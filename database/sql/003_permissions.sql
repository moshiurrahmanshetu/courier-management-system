CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permission_name VARCHAR(100) NOT NULL,
    permission_key VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO permissions (permission_name, permission_key, module, description) VALUES 
('View Dashboard', 'dashboard.view', 'Dashboard', 'Access to view dashboard'),
('View Users', 'users.view', 'Users', 'Access to view user list'),
('Create Users', 'users.create', 'Users', 'Access to create users'),
('Edit Users', 'users.edit', 'Users', 'Access to edit users'),
('Delete Users', 'users.delete', 'Users', 'Access to delete users'),
('View Roles', 'roles.view', 'Roles', 'Access to view roles'),
('Create Roles', 'roles.create', 'Roles', 'Access to create roles'),
('Edit Roles', 'roles.edit', 'Roles', 'Access to edit roles'),
('Delete Roles', 'roles.delete', 'Roles', 'Access to delete roles'),
('View Permissions', 'permissions.view', 'Permissions', 'Access to view permissions'),
('Create Permissions', 'permissions.create', 'Permissions', 'Access to create permissions'),
('Edit Permissions', 'permissions.edit', 'Permissions', 'Access to edit permissions'),
('Delete Permissions', 'permissions.delete', 'Permissions', 'Access to delete permissions'),
('View Profile', 'profile.view', 'Profile', 'Access to view profile'),
('Edit Profile', 'profile.edit', 'Profile', 'Access to edit profile');
