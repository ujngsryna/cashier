<?php
session_start();
require_once('db-connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['loggedin'] = true;
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['level']  = $user['level'];   

        // Redirect user based on their level
        if ($_SESSION['level'] == 'kasir') {
            header("location: ./pages/transaction.php");
        } else {
            header("location: ./pages/dashboard.php");
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>
