<?php
// Koneksi ke database
session_start();
include '../db/db-connection.php';

// Proses penyimpanan data supplier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];

    $query = "INSERT INTO suppliers (name, contact) VALUES ('$name', '$contact')";
    mysqli_query($conn, $query);

    // Redirect kembali ke halaman supplier
    header("Location: supplier.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <link rel="stylesheet" href="../style/style.css"> <!-- Link ke file CSS -->
</head>
<body>
    <?php include '../layout/header.php'; ?> <!-- Header -->
    <div id="content">
        <main>
            <!-- Breadcrumbs -->
            <div class="head-title">
                <div class="left">
                    <h1>Add Supplier</h1>
                    <ul class="breadcrumb">
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="supplier.php">Suppliers</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#" class="active">Add Supplier</a></li>
                    </ul>
                </div>
            </div>

            <!-- Form Add Supplier -->
            <div class="table-data form-data">
                <div class="order">
                    <div class="head">
                        <h3>Supplier Information</h3>
                    </div>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" id="name" name="name" required placeholder="Enter supplier name">
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" id="contact" name="contact" placeholder="Enter contact details">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-save">Save</button>
                            <a href="supplier.php" class="btn-cancel">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
