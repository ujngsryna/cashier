<?php
session_start();
require_once('db-connection.php');

if (isset($_POST['add_product'])) {
    $nama_produk =  $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];
    $jumlah = $_POST['jumlah'];

    // Generating a random string using openssl_random_pseudo_bytes (alternative to random_bytes)
    $kode_unik = bin2hex(openssl_random_pseudo_bytes(5));

    $stmt = $conn->prepare("INSERT INTO products (nama_produk, harga_produk, jumlah, kode_unik) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $nama_produk, $harga_produk, $jumlah, $kode_unik);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $timestamp = date("Y-m-d H:i:s");
        $username = $_SESSION['username'];
        $action = "Add Product";
        $product_id = $stmt->insert_id; // Get the ID of the inserted product
        $product_name = $nama_produk;
        
        $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("sssis", $timestamp, $username, $action, $product_id, $product_name);
        $log_stmt->execute();
        $log_stmt->close();

        echo "Product added successfully!";
    } else {
        echo "Failed to add product. Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: ../pages/products.php');
    exit;
}

?>