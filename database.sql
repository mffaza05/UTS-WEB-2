-- Database Creation
CREATE DATABASE IF NOT EXISTS uniska_2026_utsweb2_2410010546;
USE uniska_2026_utsweb2_2410010546;

-- Table: prodi
CREATE TABLE IF NOT EXISTS prodi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_prodi VARCHAR(100) NOT NULL UNIQUE,
    kode_prodi VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    npm VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    no_telp VARCHAR(15),
    alamat TEXT,
    id_prodi INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prodi) REFERENCES prodi(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: krs
CREATE TABLE IF NOT EXISTS krs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_mahasiswa INT NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    kode_mk VARCHAR(20) NOT NULL,
    semester INT NOT NULL,
    sks INT NOT NULL,
    nilai VARCHAR(2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert Sample Data: Prodi
INSERT INTO prodi (nama_prodi, kode_prodi) VALUES
('Teknik Informatika', 'TIF'),
('Sistem Informasi', 'SI'),
('Manajemen Informatika', 'MI'),
('Teknik Komputer', 'TK'),
('Keamanan Siber', 'KS');

-- Insert Sample Data: Mahasiswa
INSERT INTO mahasiswa (npm, nama, email, no_telp, alamat, id_prodi) VALUES
('2410010546', 'Muhammad Hafizh Faza Qodama', 'hafizh@uniska.ac.id', '081234567890', 'Jl. Ahmad Yani No. 1 Banjarmasin', 1),
('2410010547', 'Siti Nur Azizah', 'siti@uniska.ac.id', '081234567891', 'Jl. Lambung Mangkurat No. 5 Banjarmasin', 1),
('2410010548', 'Budi Santoso', 'budi@uniska.ac.id', '081234567892', 'Jl. Gatot Subroto No. 10 Banjarmasin', 2),
('2410010549', 'Rini Handoko', 'rini@uniska.ac.id', '081234567893', 'Jl. Merdeka No. 15 Banjarmasin', 2),
('2410010550', 'Ahmad Rizki', 'ahmad@uniska.ac.id', '081234567894', 'Jl. Sudirman No. 20 Banjarmasin', 3);

-- Insert Sample Data: KRS
INSERT INTO krs (id_mahasiswa, mata_kuliah, kode_mk, semester, sks, nilai) VALUES
(1, 'Pemrograman Web 1', 'WEB101', 1, 3, 'A'),
(1, 'Basis Data', 'DB101', 1, 3, 'B+'),
(1, 'Algoritma dan Pemrograman', 'ALG101', 1, 4, 'A'),
(1, 'Logika Matematika', 'MATH101', 1, 2, 'B'),
(1, 'Sistem Operasi', 'OS101', 2, 3, 'A'),
(2, 'Pemrograman Web 1', 'WEB101', 1, 3, 'B'),
(2, 'Basis Data', 'DB101', 1, 3, 'B+'),
(2, 'Algoritma dan Pemrograman', 'ALG101', 1, 4, 'A-'),
(2, 'Logika Matematika', 'MATH101', 1, 2, 'A'),
(3, 'Analisis Sistem Informasi', 'SI201', 2, 3, 'A'),
(3, 'Manajemen Database', 'DB202', 2, 3, 'B+'),
(3, 'Business Intelligence', 'BI203', 2, 4, 'A'),
(4, 'Analisis Sistem Informasi', 'SI201', 2, 3, 'B'),
(4, 'Manajemen Database', 'DB202', 2, 3, 'B'),
(5, 'Sistem Informasi Manajemen', 'SIM301', 3, 3, 'A');
