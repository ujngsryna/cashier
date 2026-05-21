<?php
session_start();
require_once('db-connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $authenticated = false;
    $used_plaintext_fallback = false;
    if ($user) {
        if (password_verify($password, $user['password'])) {
            $authenticated = true;
        } elseif ($password === $user['password']) {
            $authenticated = true;
            $used_plaintext_fallback = true;
        }
    }

    if ($authenticated) {
        if ($used_plaintext_fallback) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user['id']);
            $update_stmt->execute();
            $update_stmt->close();
        }
        $_SESSION['loggedin'] = true;
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['level']  = $user['level'];   

        // Redirect user based on their level
        if ($_SESSION['level'] == 'kasir') {
            header("location: ./pages/transaction.php");
        } elseif ($_SESSION['level'] == 'supplier') {
            header("location: ./pages/supplier-restock.php");
        } else {
            header("location: ./pages/dashboard.php");
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
