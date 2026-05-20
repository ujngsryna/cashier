<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: kategori-list.php');
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM kategori_produk WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: kategori-list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = $_POST['nama_kategori'];

    $query = "UPDATE kategori_produk SET nama_kategori = '$nama_kategori' WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: kategori-list.php');
    exit;
}
?>

<section id="content">
    <main>
        <div class="page-container"><div class="page-card">
        <h2>Edit Kategori</h2>
        <form method="POST">
    <label>Nama Kategori:</label>
    <input type="text" name="nama_kategori" value="<?= $data['nama_kategori'] ?>" required><br>
    
    <button type="submit">Update</button>
    <a href="kategori-list.php">Batal</a>
</form>
</div></div>
