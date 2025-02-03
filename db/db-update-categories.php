<?php

session_start();
require_once('db-connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_id']) && isset($_POST['nama_kategori'])) {
    $category_id = $_POST['category_id'];
    $nama_kategori = $_POST['nama_kategori'];
    
    $query = "UPDATE kategori_produk SET nama_kategori = ? WHERE id = ?";
    $params = [$nama_kategori, $category_id];
    
    if (execute($query, $params)) {
        $_SESSION['success'] = "Kategori berhasil diupdate";
    } else {
        $_SESSION['error'] = "Gagal mengupdate kategori";
    }
    
    header('Location: ../pages/manage-categories.php');
    exit;
}

?>