<?php
session_start();
include '../layout/header.php';
require_once('../db/db-connection.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    // Check if all fields are filled
    if (empty($username) || empty($password) || empty($level)) {
        echo "All fields are required.";
        exit;
    }

    // Update user data in the database
    $stmt = $conn->prepare("UPDATE users SET username=?, password=?, level=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $password, $level, $user_id);
    $result = $stmt->execute();

    // Check if the query was successful
    if ($result) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
        exit;
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
                <form action="../db/db-update-user.php" method="post">
                    <!-- Populate input fields with user data -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                    <label for="name">Name :</label>
                    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required><br>
                    <label for="username">Username :</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required><br>
                    <label for="password">Password :</label>
                    <input type="password" id="password" name="password" value="<?= htmlspecialchars($user['password']); ?>" required><br>
                    <label for="level">Level</label>
                    <div class="form-group">
                        <select name="level" id="level" class="form-control" required>
                            <option value="">-- Select Level --</option>
                            <option value="kasir" <?= ($user['level'] == 'kasir') ? 'selected' : ''; ?>>Cashier</option>
                            <option value="owner" <?= ($user['level'] == 'owner') ? 'selected' : ''; ?>>Owner</option>
                            <option value="admin" <?= ($user['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
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