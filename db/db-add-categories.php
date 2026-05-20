<?php
session_start();
require_once('db-connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama_kategori'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $stmt = $conn->prepare("INSERT INTO kategori_produk (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama_kategori);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Kategori berhasil ditambahkan";
    } else {
        $_SESSION['error'] = "Gagal menambahkan kategori";
    }
    $stmt->close();
    
    header('Location: ../pages/manage-categories.php');
    exit;
}

?>