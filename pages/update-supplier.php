<?php
session_start();
include '../db/db-connection.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['level'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: supplier.php');
    exit;
}

// Ambil data supplier
$supplier_result = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = $id");
$supplier = $supplier_result ? mysqli_fetch_assoc($supplier_result) : null;

if (!$supplier) {
    header('Location: supplier.php');
    exit;
}

// Ambil akun user terkait (jika ada)
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE supplier_id = $id LIMIT 1");
$user = $user_result ? mysqli_fetch_assoc($user_result) : null;

$error_messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['password_confirm'] ?? '';

    if (empty($name)) {
        $error_messages[] = 'Supplier name harus diisi.';
    }
    if (empty($username)) {
        $error_messages[] = 'Username harus diisi.';
    }
    if (!empty($password) && $password !== $confirm_password) {
        $error_messages[] = 'Password dan konfirmasi harus sama.';
    }

    // Cek username unik jika diganti
    if (empty($error_messages)) {
        $check_sql = "SELECT id FROM users WHERE username = '$username'";
        if ($user) {
            $check_sql .= " AND id != " . intval($user['id']);
        }
        $check_result = mysqli_query($conn, $check_sql);
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $error_messages[] = 'Username sudah digunakan oleh user lain.';
        }
    }

    if (empty($error_messages)) {
        mysqli_query($conn, "UPDATE suppliers SET name = '$name', contact = '$contact' WHERE id = $id");

        if ($user) {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_user_sql = "UPDATE users SET username = '$username', nama = '$name', password = '$hashed_password' WHERE id = " . intval($user['id']);
            } else {
                $update_user_sql = "UPDATE users SET username = '$username', nama = '$name' WHERE id = " . intval($user['id']);
            }
            mysqli_query($conn, $update_user_sql);
        } else {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                mysqli_query($conn, "INSERT INTO users (username, password, level, supplier_id) VALUES ('$username', '$hashed_password', 'supplier', $id)");
            }
        }

        header('Location: supplier.php');
        exit;
    }
}

include '../layout/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Edit Supplier</h1>
                    <ul class="breadcrumb">
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="supplier.php">Suppliers</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#" class="active">Edit Supplier</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data form-data">
                <div class="order">
                    <div class="head"><h3>Edit Supplier Data</h3></div>
                    <?php if (!empty($error_messages)): ?>
                        <div class="alert alert-danger" style="margin-bottom:20px;">
                            <?php foreach ($error_messages as $msg): ?>
                                <p><?= htmlspecialchars($msg); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST" autocomplete="off">
                        <input type="hidden" style="display:none" autocomplete="username">
                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? $supplier['name']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($_POST['contact'] ?? $supplier['contact']); ?>">
                        </div>
                        <hr style="margin:20px 0;">
                        <h4>Supplier Account</h4>
                        <?php if ($user): ?>
                        <div class="form-group">
                            <label>Current Supplier Account</label>
                            <input type="text" value="<?= htmlspecialchars($user['username']); ?>" readonly style="background:#f0f0f0;">
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? ($user['username'] ?? '')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password <small>(biarkan kosong jika tidak ingin mengubah)</small></label>
                            <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirm Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm new password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-save">Save Changes</button>
                            <a href="supplier.php" class="btn-cancel">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
