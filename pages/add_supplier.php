<?php
// Koneksi ke database
include '../db/db-connection.php';

// Proses penyimpanan data supplier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];

    $query = "INSERT INTO supplier (name, contact) VALUES ('$name', '$contact')";
    mysqli_query($conn, $query);

    // Redirect kembali ke halaman supplier
    header("Location: supplier.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <link rel="stylesheet" href="../style/style.css"> <!-- Link ke file CSS Anda -->
</head>
<body>
    <!-- SIDEBAR -->
    <div id="sidebar">
        <div class="brand">
            <span>Brand Name</span>
        </div>
        <ul class="side-menu top">
            <li>
                <a href="supplier.php">
                    <i class='bx bx-user'></i>
                    <span class="text">Suppliers</span>
                </a>
            </li>
            <li class="active">
                <a href="add_supplier.php">
                    <i class='bx bx-plus'></i>
                    <span class="text">Add Supplier</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div id="content">
        <!-- NAVBAR -->
        <nav>
            <a href="#" class="nav-link">Dashboard</a>
            <form action="#" class="form-input">
                <input type="text" placeholder="Search...">
                <button type="submit"><i class="bx bx-search"></i></button>
            </form>
        </nav>

        <!-- MAIN CONTENT -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Add Supplier</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a></li>
                        <li><a href="supplier.php" class="active">Suppliers</a></li>
                        <li><a href="#">Add Supplier</a></li>
                    </ul>
                </div>
            </div>

            <form action="" method="POST" class="todo-list">
                <div class="form-group">
                    <label for="name">Supplier Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact">
                </div>
                <button type="submit">Save</button>
            </form>
        </main>
    </div>
</body>
</html>
