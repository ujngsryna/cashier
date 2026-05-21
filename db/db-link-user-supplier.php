<?php
session_start();
require_once('db-connection.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['level'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (isset($_POST['link'])) {
    $user_id = intval($_POST['user_id']);
    $supplier_id = isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '' ? intval($_POST['supplier_id']) : null;

    // Ensure column exists
    $res = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
    if (!$res || $res->num_rows === 0) {
        die('Database schema missing supplier_id column on users table.');
    }

    $stmt = $conn->prepare("UPDATE users SET supplier_id = ? WHERE id = ?");
    if ($supplier_id === null) {
        $stmt->bind_param('ii', $null = null, $user_id); // set to NULL
        // workaround: bind_param cannot bind null directly in some drivers; use separate query
        $conn->query("UPDATE users SET supplier_id = NULL WHERE id = $user_id");
    } else {
        $stmt->bind_param('ii', $supplier_id, $user_id);
        $stmt->execute();
    }

    // Log activity
    $timestamp = date('Y-m-d H:i:s');
    $username = $_SESSION['username'];
    $action = $supplier_id === null ? 'Unlink user from supplier' : 'Link user to supplier';
    $log_stmt = $conn->prepare("INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES (?, ?, ?, NULL, NULL)");
    $log_stmt->bind_param('sss', $timestamp, $username, $action);
    $log_stmt->execute();

    header('Location: ../pages/link-user-supplier.php');
    exit;
}

header('Location: ../pages/link-user-supplier.php');
exit;
