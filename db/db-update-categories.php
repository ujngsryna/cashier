<?php

session_start();
require_once('db-connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_id']) && isset($_POST['nama_kategori'])) {
    $category_id = $_POST['category_id'];
    $nama_kategori = $_POST['nama_kategori'];
    $stmt = $conn->prepare("UPDATE kategori_produk SET nama_kategori = ? WHERE id = ?");
    $stmt->bind_param("si", $nama_kategori, $category_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Kategori berhasil diupdate";
    } else {
        $_SESSION['error'] = "Gagal mengupdate kategori";
    }
    $stmt->close();
    
    header('Location: ../pages/manage-categories.php');
    exit;
}

?>