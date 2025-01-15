<?php

require_once('db-connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];

    $check_query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $check_result = mysqli_query($conn, $check_query);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $error_message = "Username already exists. Please choose a different username.";
    } else {
        $insert_query = "INSERT INTO users (username, password, nama) VALUES ('$username', '$password', '$nama')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
            header("Location: ../pages/index.php");
            exit();
        } else {
            $error_message = "Registration failed. Please try again later.";
        }
    }
}

?>