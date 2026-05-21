<?php
session_start();
require_once('db-connection.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['level'] !== 'admin') {
    header('Location: ../pages/dashboard.php');
    exit;
}

if (isset($_POST['transfer'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        header('Location: ../pages/products.php');
        exit;
    }

    $product_result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($product_result);

    if (!$product || $product['warehouse_stock'] < $quantity) {
        header('Location: ../pages/products.php');
        exit;
    }

    if ($product['jumlah'] > 0) {
        header('Location: ../pages/products.php');
        exit;
    }

    $update = mysqli_query($conn, "UPDATE products SET warehouse_stock = warehouse_stock - $quantity, jumlah = jumlah + $quantity WHERE id = $product_id");
    if ($update) {
        $timestamp = date("Y-m-d H:i:s");
        $username = $_SESSION['username'];
        $action = "Warehouse Transfer";
        $product_id_log = $product_id;
        $product_name = $product['nama_produk'];

        $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("sssis", $timestamp, $username, $action, $product_id_log, $product_name);
        $log_stmt->execute();
        $log_stmt->close();
    }
}

header('Location: ../pages/products.php');
exit;
