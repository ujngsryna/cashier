<?php

session_start();
require_once('db-connection.php');

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    
    // Cek apakah kategori masih digunakan
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE kategori_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan oleh produk";
    } else {
        $del_stmt = $conn->prepare("DELETE FROM kategori_produk WHERE id = ?");
        $del_stmt->bind_param("i", $category_id);
        if ($del_stmt->execute()) {
            $_SESSION['success'] = "Kategori berhasil dihapus";
        } else {
            $_SESSION['error'] = "Gagal menghapus kategori";
        }
        $del_stmt->close();
    }
    
    header('Location: ../pages/manage-categories.php');
    exit;
}