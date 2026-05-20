<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = $_POST['nama_kategori'];

    $query = "INSERT INTO kategori_produk (nama_kategori) VALUES ('$nama_kategori')";
    mysqli_query($conn, $query);
    header('Location: kategori-list.php');
    exit;
}
?>

<section id="content">
    <main>
        <div class="page-container"><div class="page-card">
        <h2>Tambah Kategori</h2>
        <form method="POST">
    <label>Nama Kategori:</label>
    <input type="text" name="nama_kategori" required><br>
    
    <button type="submit">Simpan</button>
    <a href="kategori-list.php">Batal</a>
</form>
</div></div>
    </main>
</section>
