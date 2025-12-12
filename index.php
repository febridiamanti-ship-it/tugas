<?php
// --- PENGATURAN ERROR & KONEKSI ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; 

$pesan = "";

// --- LOGIKA SIMPAN DATA (CREATE) ---
if (isset($_POST['simpan'])) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat         = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $nama_barang    = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $merk           = mysqli_real_escape_string($koneksi, $_POST['merk']);
    $keluhan        = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
    $tgl_masuk      = date('Y-m-d');

    // 1. Simpan Pelanggan
    $q_pelanggan = "INSERT INTO tb_pelanggan (nama_pelanggan, no_hp, alamat) VALUES ('$nama_pelanggan', '$no_hp', '$alamat')";
    
    if (mysqli_query($koneksi, $q_pelanggan)) {
        $id_pelanggan_baru = mysqli_insert_id($koneksi); // Ambil ID baru
        
        // 2. Simpan Barang
        $q_barang = "INSERT INTO tb_barang (id_pelanggan, nama_barang, merk, keluhan, tgl_masuk) 
                     VALUES ('$id_pelanggan_baru', '$nama_barang', '$merk', '$keluhan', '$tgl_masuk')";
        
        if (mysqli_query($koneksi, $q_barang)) {
            $id_barang_baru = mysqli_insert_id($koneksi); // Ambil ID barang baru
            
            // 3. Simpan Status Awal Servis
            $q_servis = "INSERT INTO tb_servis (id_barang, status) VALUES ('$id_barang_baru', 'Pending')";
            mysqli_query($koneksi, $q_servis);

            $pesan = "<div class='alert success'>✅ Data Berhasil Disimpan!</div>";
        } else {
            $pesan = "<div class='alert error'>❌ Gagal Simpan Barang: " . mysqli_error($koneksi) . "</div>";
        }
    } else {
        $pesan = "<div class='alert error'>❌ Gagal Simpan Pelanggan: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Bengkel Multiguna</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        
        /* Form Styling */
        input[type=text], textarea { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-simpan { background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%; font-size: 16px; border-radius: 4px; }
        .btn-simpan:hover { background-color: #218838; }

        /* Alert Styling */
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }

        /* Tombol Aksi */
        .btn-edit { background-color: #ffc107; color: black; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px; }
        .btn-hapus { background-color: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 14px; margin-left: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div style="text-align: center; margin-bottom: 20px;">
        <h1>Sistem Informasi Penerimaan Servis</h1>
        <h3>CV. Multiguna Elektro</h3>
        <p>Perancang Sistem: <strong>Febrisio Diamanti</strong></p> </div>

    <?php echo $pesan; ?>

    <h3>Input Servis Baru</h3>
    <form method="POST" action="">
        <label>Nama Pelanggan:</label>
        <input type="text" name="nama_pelanggan" required placeholder="Nama Pemilik Barang">

        <label>No. HP:</label>
        <input type="text" name="no_hp" placeholder="08xxxxx">

        <label>Alamat:</label>
        <textarea name="alamat" rows="2"></textarea>
        
        <label>Nama Barang (Motor/Genset):</label>
        <input type="text" name="nama_barang" required placeholder="Contoh: Dinamo 3 Phase">

        <label>Merk:</label>
        <input type="text" name="merk" placeholder="Contoh: Hitachi">

        <label>Keluhan / Kerusakan:</label>
        <textarea name="keluhan" rows="2" required placeholder="Jelaskan kerusakan..."></textarea>

        <button type="submit" name="simpan" class="btn-simpan">SIMPAN DATA</button>
    </form>

    <hr style="margin: 30px 0;">

    <h3>Daftar Antrian Servis</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Barang (Merk)</th>
                <th>Keluhan</th>
                <th>Status</th>
                <th width="150">Aksi</th> </tr>
        </thead>
        <tbody>
            <?php
            // Query JOIN 3 Tabel
            $query = "SELECT p.id_pelanggan, p.nama_pelanggan, b.nama_barang, b.merk, b.keluhan, s.status 
                      FROM tb_pelanggan p
                      JOIN tb_barang b ON p.id_pelanggan = b.id_pelanggan
                      LEFT JOIN tb_servis s ON b.id_barang = s.id_barang
                      ORDER BY p.id_pelanggan DESC";
            
            $result = mysqli_query($koneksi, $query);
            
            if (!$result) {
                echo "<tr><td colspan='6'>Error Query: " . mysqli_error($koneksi) . "</td></tr>";
            } else {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['nama_pelanggan'] . "</td>";
                    echo "<td>" . $row['nama_barang'] . " (" . $row['merk'] . ")</td>";
                    echo "<td>" . $row['keluhan'] . "</td>";
                    
                    // Warna status biar bagus
                    $warna_status = "black";
                    if($row['status'] == 'Pending') $warna_status = "orange";
                    if($row['status'] == 'Selesai') $warna_status = "green";
                    
                    echo "<td style='color:$warna_status; font-weight:bold;'>" . $row['status'] . "</td>";
                    
                    // TOMBOL EDIT DAN HAPUS
                    echo "<td>
                            <a href='edit.php?id=" . $row['id_pelanggan'] . "' class='btn-edit'>Edit</a>
                            <a href='hapus.php?id=" . $row['id_pelanggan'] . "' class='btn-hapus' onclick='return confirm(\"Yakin ingin menghapus data Bapak/Ibu " . $row['nama_pelanggan'] . "?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
