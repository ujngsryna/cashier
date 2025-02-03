<?php
session_start();
require_once('db-connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama_kategori'])) {
    $nama_kategori = $_POST['nama_kategori'];
    
    $query = "INSERT INTO kategori_produk (nama_kategori) VALUES (?)";
    $params = [$nama_kategori];
    
    if (execute($query, $params)) {
        $_SESSION['success'] = "Kategori berhasil ditambahkan";
    } else {
        $_SESSION['error'] = "Gagal menambahkan kategori";
    }
    
    header('Location: ../pages/manage-categories.php');
    exit;
}

?>