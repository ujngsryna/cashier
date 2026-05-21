<?php
// Koneksi ke database
session_start();
include '../db/db-connection.php';

// Proses penyimpanan data supplier + user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    $errors = [];

    // Validasi
    if (empty($username)) {
        $errors[] = 'Username harus diisi.';
    }
    if (empty($password)) {
        $errors[] = 'Password harus diisi.';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Password dan konfirmasi tidak cocok.';
    }

    // Cek apakah username sudah ada
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Username sudah terdaftar.';
        }
    }

    if (empty($errors)) {
        // Insert supplier dulu
        $supplier_query = "INSERT INTO suppliers (name, contact) VALUES ('$name', '$contact')";
        if (mysqli_query($conn, $supplier_query)) {
            $supplier_id = mysqli_insert_id($conn);

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user dengan level 'supplier' dan supplier_id
            $has_supplier_column = false;
            $res = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
            if ($res && $res->num_rows > 0) {
                $has_supplier_column = true;
            }

            if ($has_supplier_column) {
                $user_query = "INSERT INTO users (username, password, nama, level, supplier_id) VALUES ('$username', '$hashed_password', '$name', 'supplier', $supplier_id)";
            } else {
                $user_query = "INSERT INTO users (username, password, nama, level) VALUES ('$username', '$hashed_password', '$name', 'supplier')";
            }

            if (mysqli_query($conn, $user_query)) {
                header("Location: supplier.php");
                exit;
            } else {
                $errors[] = 'Gagal membuat akun user. Error: ' . mysqli_error($conn);
            }
        } else {
            $errors[] = 'Gagal menambah supplier. Error: ' . mysqli_error($conn);
        }
    }
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
                        <?php if (!empty($errors)): ?>
                            <div style="background-color:#f8d7da;color:#721c24;padding:12px;border-radius:5px;margin-bottom:15px;">
                                <?php foreach ($errors as $error): ?>
                                    <p style="margin:5px 0;">• <?= htmlspecialchars($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" id="name" name="name" required placeholder="Enter supplier name" value="<?= htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" id="contact" name="contact" placeholder="Enter contact details" value="<?= htmlspecialchars($_POST['contact'] ?? ''); ?>">
                        </div>
                        <hr style="margin:15px 0;">
                        <h4 style="margin-bottom:10px;">User Account untuk Supplier</h4>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required placeholder="Enter username" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirm Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" required placeholder="Confirm password">
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
