<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

$has_supplier_column = false;
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
if ($result && $result->num_rows > 0) {
    $has_supplier_column = true;
}

if ($has_supplier_column) {
    $suppliers = mysqli_query($conn, "SELECT id, name FROM suppliers ORDER BY name");
}

// Check if user_id is set in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid user ID.";
    exit;
}
$user_id = $_GET['id'];

// Fetch user data from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    // Fetch the user data as an associative array
    $user = $result->fetch_assoc();
    if ($user === null) {
        echo "User not found.";
        exit;
    }
} else {
    echo "Failed to fetch user data from the database.";
    exit;
}

// Edit atau Update
$error_messages = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $level = $_POST['level'];
    $supplier_id = isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '' ? intval($_POST['supplier_id']) : null;

    if (empty($username) || empty($level)) {
        $error_messages[] = 'Username dan level harus diisi.';
    }
    if (!empty($password) && $password !== $password_confirm) {
        $error_messages[] = 'Password dan konfirmasi tidak cocok.';
    }

    if (empty($error_messages)) {
        $check_sql = "SELECT id FROM users WHERE username = '$username' AND id != $user_id";
        $check_result = mysqli_query($conn, $check_sql);
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $error_messages[] = 'Username sudah digunakan oleh user lain.';
        }
    }

    if (empty($error_messages)) {
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
        if ($has_supplier_column) {
            if ($hashed_password !== null) {
                $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, password = ?, level = ?, supplier_id = ? WHERE id = ?");
                $stmt->bind_param("sssiii", $nama, $username, $hashed_password, $level, $supplier_id, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, level = ?, supplier_id = ? WHERE id = ?");
                $stmt->bind_param("ssiii", $nama, $username, $level, $supplier_id, $user_id);
            }
        } else {
            if ($hashed_password !== null) {
                $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, password = ?, level = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $nama, $username, $hashed_password, $level, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, level = ? WHERE id = ?");
                $stmt->bind_param("sssi", $nama, $username, $level, $user_id);
            }
        }

        if ($stmt->execute()) {
            header('Location: user.php');
            exit;
        } else {
            $error_messages[] = 'Gagal mengupdate user: ' . $stmt->error;
        }
    }
}
?>
<section id="content">
    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Update User</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Update User</h3>
                    <a href="user.php"> <i class='bx bx-undo text-white' href="user.php" style="font-size:30px;"></i></a>
                </div>
                <form action="" method="post" autocomplete="off">
                    <input type="hidden" style="display:none" autocomplete="username">
                    <?php if (!empty($error_messages)): ?>
                        <div style="background-color:#f8d7da;color:#721c24;padding:12px;border-radius:5px;margin-bottom:15px;">
                            <?php foreach ($error_messages as $error): ?>
                                <p style="margin:5px 0;">• <?= htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Populate input fields with user data -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                    <label for="name">Name :</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required><br>
                    <label for="username">Username :</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required><br>
                    <label for="password">Password <small>(kosongkan jika tidak ingin mengubah)</small> :</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" value="" placeholder="Enter new password if you want to change"><br>
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" value="" placeholder="Confirm new password"><br>
                    <label for="level">Level</label>
                    <div class="form-group">
                        <select name="level" id="level" class="form-control" required>
                            <option value="">-- Select Level --</option>
                            <option value="kasir" <?= ($user['level'] == 'kasir') ? 'selected' : ''; ?>>Cashier</option>
                            <option value="supplier" <?= ($user['level'] == 'supplier') ? 'selected' : ''; ?>>Supplier</option>
                            <option value="owner" <?= ($user['level'] == 'owner') ? 'selected' : ''; ?>>Owner</option>
                            <option value="admin" <?= ($user['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <?php if ($has_supplier_column): ?>
                        <label for="supplier_id">Supplier (untuk akun supplier):</label>
                        <div class="form-group">
                            <select name="supplier_id" id="supplier_id" class="form-control">
                                <option value="">-- Select Supplier --</option>
                                <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                                    <option value="<?= $supplier['id']; ?>" <?= isset($user['supplier_id']) && $user['supplier_id'] == $supplier['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($supplier['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="update_user">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</main>

<style>
/* CSS untuk mempercantik form */
.form-group {
margin-bottom: 20px;
}

label {
display: block;
margin-bottom: 5px;
font-weight: bold;
}

input[type="text"],
input[type="password"],
select {
width: 100%;
padding: 10px;
border: 1px solid #ccc;
border-radius: 5px;
box-sizing: border-box;
margin-bottom: 10px;
}

button[type="submit"] {
background-color: #DC692C;
color: #fff;
padding: 10px 20px;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
font-weight: bold;
margin-top: 10px;
}

button[type="submit"]:hover {
background-color: #FD7238;
}
.text-white {
color: #ffffff;
}
</style>