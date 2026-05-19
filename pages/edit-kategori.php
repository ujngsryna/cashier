<?php
include '../config/database.php';

$id = $_GET['id'];
$query = "SELECT * FROM kategori_barang WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];

    $query = "UPDATE kategori_barang SET nama_kategori = '$nama_kategori', deskripsi = '$deskripsi' WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: kategori-list.php');
}
?>

<h2>Edit Kategori</h2>
<form method="POST">
    <label>Nama Kategori:</label>
    <input type="text" name="nama_kategori" value="<?= $data['nama_kategori'] ?>" required><br>
    
    <label>Deskripsi:</label>
    <textarea name="deskripsi"><?= $data['deskripsi'] ?></textarea><br>
    
    <button type="submit">Update</button>
    <a href="kategori-list.php">Batal</a>
</form>
