<?php

session_start();
require_once('db-connection.php');
include '../config/app.php';

$id = (int)$_GET['id'];

// Fetch product data before deletion for logging
$product_name = "";
$query = "SELECT nama_produk FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($product_name);
$stmt->fetch();
$stmt->close();

if (delete_product($id) > 0) {
    // Log activity
    $timestamp = date("Y-m-d H:i:s");
    $username = $_SESSION['username'];
    $action = "Delete Product";
    
    $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, ?, ?)");
    $log_stmt->bind_param("sssis", $timestamp, $username, $action, $id, $product_name);
    $log_stmt->execute();
    $log_stmt->close();
    
    echo "<script>
            alert('Success!');
            document.location.href = '../pages/products.php';
          </script>";
} else {
    echo "<script>
            alert('Failed');
            document.location.href = '../pages/products.php';
          </script>";
}

?>