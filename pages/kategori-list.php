<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

$query = "SELECT * FROM kategori_produk ORDER BY nama_kategori";
$result = mysqli_query($conn, $query);
?>

<section id="content">
    <main>
        <div class="page-container"><div class="page-card">
        <h2>Daftar Kategori Barang</h2>
        <a href="add-kategori.php">Tambah Kategori</a>
        <table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Kategori</th>
        <th>Terakhir Diupdate</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td><?= $row['updated_at'] ?></td>
            <td>
                <a href="edit-kategori.php?id=<?= $row['id'] ?>">Edit</a>
            </td>
        </tr>
    <?php } ?>
    </table>
    </div></div>
        </main>
    </section>
