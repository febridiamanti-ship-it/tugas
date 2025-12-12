-- 1. Membuat Tabel Pelanggan
CREATE TABLE tb_pelanggan (
    id_pelanggan INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20),
    alamat TEXT,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Membuat Tabel Barang
CREATE TABLE tb_barang (
    id_barang INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT(11),
    nama_barang VARCHAR(100) NOT NULL,
    merk VARCHAR(50),
    keluhan TEXT NOT NULL,
    tgl_masuk DATE NOT NULL,
    FOREIGN KEY (id_pelanggan) REFERENCES tb_pelanggan(id_pelanggan)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- 3. Membuat Tabel Servis
CREATE TABLE tb_servis (
    id_servis INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_barang INT(11),
    tindakan VARCHAR(200),
    biaya INT(11),
    status ENUM('Pending', 'Sedang Dikerjakan', 'Selesai', 'Diambil') DEFAULT 'Pending',
    tgl_selesai DATE,
    FOREIGN KEY (id_barang) REFERENCES tb_barang(id_barang)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- 4. Data Dumy
INSERT INTO tb_pelanggan (nama_pelanggan, no_hp, alamat) VALUES 
('Bapak Budi', '08123456789', 'Manado Tua'),
('Ibu Sari', '08987654321', 'Airmadidi');

INSERT INTO tb_barang (id_pelanggan, nama_barang, merk, keluhan, tgl_masuk) VALUES 
(1, 'Elektro Motor 1HP', 'Hitachi', 'Cepat panas dan bau hangus', '2025-12-10'),
(2, 'Pompa Air Jetpump', 'Shimizu', 'Mati total', '2025-12-11');

INSERT INTO tb_servis (id_barang, tindakan, biaya, status, tgl_selesai) VALUES 
(1, 'Gulung Ulang (Rewinding) Stator', 750000, 'Sedang Dikerjakan', NULL);
