<?php
include 'config.php'; 

// 1. Cek ID di URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

// 2. Ambil Data
$query = "SELECT p.*, b.*, s.status, s.id_servis, b.id_barang 
          FROM tb_pelanggan p
          JOIN tb_barang b ON p.id_pelanggan = b.id_pelanggan
          LEFT JOIN tb_servis s ON b.id_barang = s.id_barang
          WHERE p.id_pelanggan = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) { die("Data tidak ditemukan"); }

// 3. Proses Update
if (isset($_POST['update'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $hp     = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $barang = mysqli_real_escape_string($koneksi, $_POST['barang']);
    $merk   = mysqli_real_escape_string($koneksi, $_POST['merk']);
    $keluhan= mysqli_real_escape_string($koneksi, $_POST['keluhan']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    // Update 3 Tabel
    mysqli_query($koneksi, "UPDATE tb_pelanggan SET nama_pelanggan='$nama', no_hp='$hp', alamat='$alamat' WHERE id_pelanggan='$id'");
    mysqli_query($koneksi, "UPDATE tb_barang SET nama_barang='$barang', merk='$merk', keluhan='$keluhan' WHERE id_pelanggan='$id'");
    
    $id_brg = $data['id_barang'];
    mysqli_query($koneksi, "UPDATE tb_servis SET status='$status' WHERE id_barang='$id_brg'");
    
    echo "<script>alert('Data Berhasil Diupdate!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; background: #f0f2f5; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        input, textarea, select { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        a { display: block; text-align: center; margin-top: 15px; color: #dc3545; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center;">Edit Data Servis</h2>
    
    <form method="POST">
        <label>Nama Pelanggan:</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama_pelanggan']); ?>" required>

        <label>No. HP:</label>
        <input type="text" name="hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>">

        <label>Alamat:</label>
        <input type="text" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>">

        <label>Nama Barang:</label>
        <input type="text" name="barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>

        <label>Merk:</label>
        <input type="text" name="merk" value="<?php echo htmlspecialchars($data['merk']); ?>">

        <label>Keluhan:</label>
        <textarea name="keluhan" rows="3"><?php echo htmlspecialchars($data['keluhan']); ?></textarea>

        <label style="color:blue; font-weight:bold;">Status Pengerjaan:</label>
        <select name="status">
            <option value="Pending" <?php if($data['status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Sedang Dikerjakan" <?php if($data['status']=='Sedang Dikerjakan') echo 'selected'; ?>>Sedang Dikerjakan</option>
            <option value="Selesai" <?php if($data['status']=='Selesai') echo 'selected'; ?>>Selesai</option>
            <option value="Diambil" <?php if($data['status']=='Diambil') echo 'selected'; ?>>Diambil</option>
        </select>

        <br><br>
        <button type="submit" name="update">SIMPAN PERUBAHAN</button>
        <a href="index.php">Batal / Kembali</a>
    </form>
</div>

</body>
</html>
