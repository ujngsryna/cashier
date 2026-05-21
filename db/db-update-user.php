<?php 
session_start();
require_once('db-connection.php');

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];
    $supplier_id = isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '' ? intval($_POST['supplier_id']) : null;
    $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    $has_supplier_column = false;
    $result = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
    if ($result && $result->num_rows > 0) {
        $has_supplier_column = true;
    }

    if ($has_supplier_column) {
        if ($hashed_password !== null) {
            $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, password = ?, level = ?, supplier_id = ? WHERE id = ?");
            $stmt->bind_param("sssiii", $nama, $username, $hashed_password, $level, $supplier_id, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, level = ?, supplier_id = ? WHERE id = ?");
            $stmt->bind_param("ssiii", $nama, $username, $level, $supplier_id, $id);
        }
    } else {
        if ($hashed_password !== null) {
            $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, password = ?, level = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nama, $username, $hashed_password, $level, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, level = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nama, $username, $level, $id);
        }
    }

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Update successfully!";
        } else {
            echo "No changes made to the user";
        }
    } else {
        echo "Failed to update user.";
    }

    $stmt->close(); // Close the statement here, inside the conditional block
}

header('location: ../pages/user.php');
exit;
?>