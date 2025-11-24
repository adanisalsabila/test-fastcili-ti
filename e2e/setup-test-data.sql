-- =====================================================
-- SQL Script untuk Setup Test Data - Role Pelapor
-- =====================================================
-- File ini berisi query untuk membuat data test
-- yang diperlukan untuk menjalankan E2E testing
-- =====================================================

-- 1. Membuat user dengan role Pelapor (id_level = 4)
-- Pastikan level sudah ada di tabel levels
INSERT INTO users (nama, email, email_verified_at, password, id_level, created_at, updated_at)
VALUES (
    'Pelapor Test',
    'pelapor@jti.com',
    NOW(),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    4, -- Level Pelapor
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE 
    nama = 'Pelapor Test',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- 2. Pastikan level Pelapor ada
INSERT INTO levels (id_level, nama_level, created_at, updated_at)
VALUES (4, 'Pelapor', NOW(), NOW())
ON DUPLICATE KEY UPDATE nama_level = 'Pelapor';

-- 3. Membuat test data untuk Gedung
INSERT INTO gedung (nama_gedung, created_at, updated_at)
VALUES ('Gedung Test A', NOW(), NOW())
ON DUPLICATE KEY UPDATE nama_gedung = 'Gedung Test A';

-- Get the id of the inserted gedung
SET @gedung_id = LAST_INSERT_ID();

-- 4. Membuat test data untuk Ruangan
INSERT INTO ruangan (id_gedung, nama_ruangan, created_at, updated_at)
VALUES (@gedung_id, 'Ruangan Test 101', NOW(), NOW())
ON DUPLICATE KEY UPDATE nama_ruangan = 'Ruangan Test 101';

-- Get the id of the inserted ruangan
SET @ruangan_id = LAST_INSERT_ID();

-- 5. Membuat test data untuk Fasilitas
INSERT INTO fasilitas (id_ruangan, nama_fasilitas, jumlah_fasilitas, created_at, updated_at)
VALUES 
    (@ruangan_id, 'Lampu Test', 10, NOW(), NOW()),
    (@ruangan_id, 'AC Test', 2, NOW(), NOW()),
    (@ruangan_id, 'Proyektor Test', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    nama_fasilitas = VALUES(nama_fasilitas),
    jumlah_fasilitas = VALUES(jumlah_fasilitas);

-- 6. Membuat status laporan
INSERT INTO status_laporan (id_status, nama_status, created_at, updated_at)
VALUES 
    (1, 'Belum Ditangani', NOW(), NOW()),
    (2, 'Dalam Proses', NOW(), NOW()),
    (3, 'Selesai', NOW(), NOW()),
    (4, 'Dibatalkan', NOW(), NOW())
ON DUPLICATE KEY UPDATE nama_status = VALUES(nama_status);

-- =====================================================
-- OPTIONAL: Sample laporan untuk testing
-- =====================================================

-- Get user id for pelapor test
SET @pelapor_id = (SELECT id FROM users WHERE email = 'pelapor@jti.com' LIMIT 1);

-- Get fasilitas id
SET @fasilitas_id = (SELECT id_fasilitas FROM fasilitas WHERE nama_fasilitas = 'Lampu Test' LIMIT 1);

-- Create sample laporan
INSERT INTO laporan_kerusakan (
    id_fasilitas,
    deskripsi,
    foto_kerusakan,
    jumlah_kerusakan,
    tanggal_lapor,
    id_status,
    created_at,
    updated_at
)
VALUES (
    @fasilitas_id,
    'Lampu tidak menyala di ruangan test',
    'default.jpg',
    2,
    NOW(),
    1, -- Belum Ditangani
    NOW(),
    NOW()
);

-- Get the created laporan id
SET @laporan_id = LAST_INSERT_ID();

-- Create pelapor_laporan entry
INSERT INTO pelapor_laporan (
    id_user,
    id_laporan,
    deskripsi_tambahan,
    created_at,
    updated_at
)
VALUES (
    @pelapor_id,
    @laporan_id,
    'Mohon segera diperbaiki',
    NOW(),
    NOW()
);

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Verify user created
SELECT 'Pelapor User:' as Info, id, nama, email, id_level FROM users WHERE email = 'pelapor@jti.com';

-- Verify gedung, ruangan, fasilitas
SELECT 'Test Data Structure:' as Info;
SELECT g.nama_gedung, r.nama_ruangan, f.nama_fasilitas, f.jumlah_fasilitas
FROM gedung g
JOIN ruangan r ON g.id_gedung = r.id_gedung
JOIN fasilitas f ON r.id_ruangan = f.id_ruangan
WHERE g.nama_gedung LIKE 'Gedung Test%';

-- Verify laporan created
SELECT 'Sample Laporan:' as Info;
SELECT lk.id_laporan, f.nama_fasilitas, lk.deskripsi, lk.jumlah_kerusakan, s.nama_status
FROM laporan_kerusakan lk
JOIN fasilitas f ON lk.id_fasilitas = f.id_fasilitas
JOIN status_laporan s ON lk.id_status = s.id_status
JOIN pelapor_laporan pl ON lk.id_laporan = pl.id_laporan
JOIN users u ON pl.id_user = u.id
WHERE u.email = 'pelapor@jti.com'
ORDER BY lk.created_at DESC
LIMIT 5;

-- =====================================================
-- CLEANUP QUERIES (Optional - untuk reset test data)
-- =====================================================

/*
-- HATI-HATI: Query ini akan menghapus semua test data!
-- Uncomment hanya jika ingin cleanup

-- Delete pelapor_laporan
DELETE pl FROM pelapor_laporan pl
JOIN users u ON pl.id_user = u.id
WHERE u.email = 'pelapor@jti.com';

-- Delete laporan_kerusakan
DELETE lk FROM laporan_kerusakan lk
JOIN fasilitas f ON lk.id_fasilitas = f.id_fasilitas
JOIN ruangan r ON f.id_ruangan = r.id_ruangan
JOIN gedung g ON r.id_gedung = g.id_gedung
WHERE g.nama_gedung LIKE 'Gedung Test%';

-- Delete fasilitas
DELETE f FROM fasilitas f
JOIN ruangan r ON f.id_ruangan = r.id_ruangan
JOIN gedung g ON r.id_gedung = g.id_gedung
WHERE g.nama_gedung LIKE 'Gedung Test%';

-- Delete ruangan
DELETE r FROM ruangan r
JOIN gedung g ON r.id_gedung = g.id_gedung
WHERE g.nama_gedung LIKE 'Gedung Test%';

-- Delete gedung
DELETE FROM gedung WHERE nama_gedung LIKE 'Gedung Test%';

-- Delete test user
DELETE FROM users WHERE email = 'pelapor@jti.com';
*/

-- =====================================================
-- NOTES
-- =====================================================
-- 1. Password untuk user test adalah: password
-- 2. Pastikan menjalankan ini di test/development database
-- 3. Jangan jalankan di production database!
-- 4. Sesuaikan nama tabel jika berbeda di database Anda
-- 5. Untuk cleanup, uncomment section CLEANUP QUERIES
-- =====================================================
