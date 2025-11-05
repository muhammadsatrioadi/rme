USE rme_system;

INSERT INTO users (id_user, username, password, email, id_pegawai, role, status_user) VALUES 
('USR001', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@rme.local', NULL, 'Admin', 'Aktif')
ON DUPLICATE KEY UPDATE username=username;

