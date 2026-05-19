<?php

session_start();
require_once('db-connection.php');

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    
    // Cek apakah kategori masih digunakan
    $check_query = "SELECT COUNT(*) as count FROM products WHERE kategori_id = ?";
    $result = select($check_query, [$category_id]);
    
    if ($result[0]['count'] > 0) {
        $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan oleh produk";
    } else {
        $query = "DELETE FROM kategori_produk WHERE id = ?";
        if (execute($query, [$category_id])) {
            $_SESSION['success'] = "Kategori berhasil dihapus";
        } else {
            $_SESSION['error'] = "Gagal menghapus kategori";
        }
    }
    
    header('Location: ../pages/manage-categories.php');
    exit;
}