<?php
session_start();
require_once('db-connection.php');

if (isset($_POST['add_product'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];
    $jumlah = $_POST['jumlah'];
    $kategori_id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 0;

    $has_supplier_column = false;
    $has_warehouse_stock_column = false;
    $result = $conn->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
    if ($result && $result->num_rows > 0) {
        $has_supplier_column = true;
    }
    $result = $conn->query("SHOW COLUMNS FROM products LIKE 'warehouse_stock'");
    if ($result && $result->num_rows > 0) {
        $has_warehouse_stock_column = true;
    }

    $supplier_id = $has_supplier_column ? intval($_POST['supplier_id']) : null;
    $warehouse_stock = $has_warehouse_stock_column ? intval($_POST['warehouse_stock']) : 0;

    // If logged-in user is supplier, prefer their linked supplier_id from users table
    if ($has_supplier_column && isset($_SESSION['level']) && $_SESSION['level'] === 'supplier') {
        $uid = intval($_SESSION['user_id']);
        $ures = $conn->query("SELECT supplier_id FROM users WHERE id = $uid");
        if ($ures && $ures->num_rows > 0) {
            $urow = $ures->fetch_assoc();
            if (!empty($urow['supplier_id'])) {
                $supplier_id = intval($urow['supplier_id']);
            }
        }
    }

    // Generating a random string using openssl_random_pseudo_bytes (alternative to random_bytes)
    $kode_unik = bin2hex(openssl_random_pseudo_bytes(5));

    if ($has_supplier_column && $has_warehouse_stock_column) {
        $stmt = $conn->prepare("INSERT INTO products (nama_produk, harga_produk, jumlah, warehouse_stock, kode_unik, kategori_id, supplier_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiisi", $nama_produk, $harga_produk, $jumlah, $warehouse_stock, $kode_unik, $kategori_id, $supplier_id);
    } elseif ($has_warehouse_stock_column) {
        $stmt = $conn->prepare("INSERT INTO products (nama_produk, harga_produk, jumlah, warehouse_stock, kode_unik, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiisi", $nama_produk, $harga_produk, $jumlah, $warehouse_stock, $kode_unik, $kategori_id);
    } elseif ($has_supplier_column) {
        $stmt = $conn->prepare("INSERT INTO products (nama_produk, harga_produk, jumlah, kode_unik, kategori_id, supplier_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisii", $nama_produk, $harga_produk, $jumlah, $kode_unik, $kategori_id, $supplier_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO products (nama_produk, harga_produk, jumlah, kode_unik, kategori_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siisi", $nama_produk, $harga_produk, $jumlah, $kode_unik, $kategori_id);
    }
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