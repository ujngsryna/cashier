<?php
session_start();
require_once('db-connection.php');

if (isset($_POST['submit'])) {
    $nama       = strip_tags($_POST['nama']);
    $username   = strip_tags($_POST['username']);
    $password   = strip_tags($_POST['password']);
    $level      = strip_tags($_POST['level']);

    // Generating a random string using openssl_random_pseudo_bytes (alternative to random_bytes)
    $kode_unik = bin2hex(openssl_random_pseudo_bytes(5));

    $stmt = $conn->prepare("INSERT INTO users (nama, username, password, level) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $username, $password, $level);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $timestamp = date("Y-m-d H:i:s");
        $username = $_SESSION['username'];
        $action = "Add User";
        $product_id = $stmt->insert_id; // Get the ID of the inserted product
        $product_name = $nama_produk;
        
        $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("sssis", $timestamp, $username, $action, $product_id, $product_name);
        $log_stmt->execute();
        $log_stmt->close();
        
        echo "User added successfully!";
    } else {
        echo "Failed to add user. Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header('Location: ../pages/user.php');
    exit;
}
?>
