<?php
$servername = "localhost";
$username = "root";
$password = "225009777";
$dbname = "cashier_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi Gagal" . $conn->connect_error);

}

?>