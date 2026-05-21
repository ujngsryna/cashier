<?php
session_start();
require_once('db-connection.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

$level = $_SESSION['level'];
if ($level !== 'supplier' && $level !== 'admin') {
    header('Location: ../pages/dashboard.php');
    exit;
}

if (isset($_POST['restock'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        header('Location: ../pages/supplier-restock.php');
        exit;
    }

    // Pastikan kolom supplier_id ada di users dan products sebelum melakukan validasi
    $res = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
    $has_users_supplier = ($res && $res->num_rows > 0);
    $res = $conn->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
    $has_products_supplier = ($res && $res->num_rows > 0);

    if (!$has_users_supplier || !$has_products_supplier) {
        header('Location: ../pages/supplier-restock.php');
        exit;
    }

    $product_result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    $product = mysqli_fetch_assoc($product_result);

    if (!$product) {
        header('Location: ../pages/supplier-restock.php');
        exit;
    }

    if ($level === 'supplier') {
        $user_id = $_SESSION['user_id'];
        $user_result = mysqli_query($conn, "SELECT supplier_id FROM users WHERE id = $user_id");
        $user = $user_result ? mysqli_fetch_assoc($user_result) : null;

        if (!$user || $user['supplier_id'] != ($product['supplier_id'] ?? null)) {
            header('Location: ../pages/supplier-restock.php');
            exit;
        }
    }

    $update = mysqli_query($conn, "UPDATE products SET warehouse_stock = warehouse_stock + $quantity WHERE id = $product_id");
    if ($update) {
        $timestamp = date("Y-m-d H:i:s");
        $username = $_SESSION['username'];
        $action = "Supplier Restock";
        $product_id_log = $product_id;
        $product_name = $product['nama_produk'];

        $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("sssis", $timestamp, $username, $action, $product_id_log, $product_name);
        $log_stmt->execute();
        $log_stmt->close();
    }
}

header('Location: ../pages/supplier-restock.php');
exit;
