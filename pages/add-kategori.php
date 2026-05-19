<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO kategori_barang (nama_kategori, deskripsi) VALUES ('$nama_kategori', '$deskripsi')";
    mysqli_query($conn, $query);
    header('Location: kategori-list.php');
}
?>

<h2>Tambah Kategori</h2>
<form method="POST">
    <label>Nama Kategori:</label>
    <input type="text" name="nama_kategori" required><br>
    
    <label>Deskripsi:</label>
    <textarea name="deskripsi"></textarea><br>
    
    <button type="submit">Simpan</button>
    <a href="kategori-list.php">Batal</a>
</form>
