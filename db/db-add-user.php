<?php
session_start();
require_once('db-connection.php');

if (isset($_POST['submit'])) {
    $nama       = strip_tags($_POST['nama']);
    $username   = strip_tags($_POST['username']);
    $password   = strip_tags($_POST['password']);
    $level      = strip_tags($_POST['level']);
    $supplier_id = isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '' ? intval($_POST['supplier_id']) : null;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $has_supplier_column = false;
    $result = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
    if ($result && $result->num_rows > 0) {
        $has_supplier_column = true;
    }

    if ($has_supplier_column) {
        $stmt = $conn->prepare("INSERT INTO users (nama, username, password, level, supplier_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $nama, $username, $hashed_password, $level, $supplier_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nama, username, password, level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $username, $hashed_password, $level);
    }
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $timestamp = date("Y-m-d H:i:s");
        $admin_username = $_SESSION['username'];
        $action = "Add User";
        $product_id = $stmt->insert_id; // Get the ID of the inserted user
        $product_name = $nama;
        
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
