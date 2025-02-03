<?php
include '../config/database.php'; // Sesuaikan dengan struktur proyek

$query = "SELECT * FROM kategori_barang";
$result = mysqli_query($conn, $query);
?>

<h2>Daftar Kategori Barang</h2>
<a href="kategori_tambah.php">Tambah Kategori</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Kategori</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td><?= $row['deskripsi'] ?></td>
            <td>
                <a href="kategori_edit.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="kategori_hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
            </td>
        </tr>
    <?php } ?>
</table>
