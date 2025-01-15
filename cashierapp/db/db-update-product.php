<?php
session_start();
require_once('db-connection.php');

if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $nama_produk = $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];

    // Update produk
    $query = "UPDATE products SET nama_produk=?, harga_produk=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $nama_produk, $harga_produk, $id);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        // Jika berhasil mengupdate, tambahkan log aktivitas
        $timestamp = date("Y-m-d H:i:s");
        $username = $_SESSION['username'];
        $action = "Update Product";
        $product_id = $id; // Gunakan id produk yang diambil dari formulir
        $product_name = $nama_produk;
        
        $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("sssis", $timestamp, $username, $action, $product_id, $product_name);
        $log_result = $log_stmt->execute();
        $log_stmt->close();

        if ($log_result) {
            header("Location: ../pages/products.php");
            exit();
        } else {
            echo "Error inserting log activity: " . $conn->error;
        }
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>
