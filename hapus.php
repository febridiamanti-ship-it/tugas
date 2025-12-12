<?php
include 'config.php'; // PENTING: Sesuaikan dengan nama file koneksi Anda (config.php atau koneksi.php)

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus data pelanggan (Barang & Servis akan terhapus otomatis karena CASCADE)
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_pelanggan WHERE id_pelanggan = '$id'");
    
    if ($hapus) {
        echo "<script>alert('Data Berhasil Dihapus!'); window.location='index.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($koneksi);
    }
}
?>
