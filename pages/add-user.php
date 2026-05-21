<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

// membatasi halaman sesuai user login
if ($_SESSION["level"] == "kasir") {
    echo "<script>
            window.history.back(); 
          </script>";
    exit;
  } 

$has_supplier_column = false;
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'supplier_id'");
if ($result && $result->num_rows > 0) {
    $has_supplier_column = true;
}

if ($has_supplier_column) {
    $suppliers = mysqli_query($conn, "SELECT id, name FROM suppliers ORDER BY name");
}

?>
<section id="content">
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <!-- <h1>User</h1> -->
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Add User</a>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Add User</h3>
                    <a href="user.php"> <i class='bx bx-undo text-white' href="user.php" style="font-size:30px;"></i></a>
                </div>
                <form action="../db/db-add-user.php" method="post">
                <label for="name">Name :</label>
<input type="text" id="nama" name="nama" required><br>
<label for="username">Username :</label>
<input type="text" id="username" name="username" required><br>
<label for="password">Password :</label>
<input type="password" id="password" name="password" required><br>
<label for="level">Level</label>
<div class="form-group">
    <select name="level" id="level" class="form-control" required>
        <option value="">-- Select Level --</option>
        <option value="kasir">Cashier</option>
        <option value="supplier">Supplier</option>
    </select>
</div>
<?php if ($has_supplier_column): ?>
    <label for="supplier_id">Supplier (untuk akun supplier):</label>
    <div class="form-group">
        <select name="supplier_id" id="supplier_id" class="form-control">
            <option value="">-- Select Supplier --</option>
            <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                <option value="<?= $supplier['id']; ?>"><?= htmlspecialchars($supplier['name']); ?></option>
            <?php endwhile; ?>
        </select>
    </div>
<?php endif; ?>
<button type="submit" id="submit" name="submit">Submit</button>



            
            </div>
        </div>
    </div>
</div>
</tbody>
    </table>
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

