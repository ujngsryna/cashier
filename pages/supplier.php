<?php
// Koneksi ke database
include '../db/db-connection.php';
session_start();

include '../layout/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

// Query untuk mendapatkan data supplier
$query = "SELECT * FROM suppliers";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Hitung total supplier
$total_suppliers_query = "SELECT COUNT(*) AS total_suppliers FROM suppliers";
$total_suppliers_result = mysqli_query($conn, $total_suppliers_query);
$total_suppliers = mysqli_fetch_assoc($total_suppliers_result)['total_suppliers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers</title>
    <link rel="stylesheet" href="../style/style.css">
    <style>
    .text-white {
    color: #ffffff;
    }
    </style>
</head>
<body>
    <div id="content">
        <main>
            <!-- Breadcrumbs -->
            <div class="head-title">
                <div class="left">
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#" class="active">Suppliers</a></li>
                    </ul>
                </div>
            </div>

            <!-- Card Info -->
            <ul class="box-info">
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3><?php echo $total_suppliers; ?></h3>
                        <p>Total Suppliers</p>
                    </span>
                </li>
            </ul>

            <!-- Table Data -->
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Supplier List</h3>
                        <!-- Tombol Tambah Supplier -->
                        <?php if ($_SESSION['level'] == "admin") : ?>
                            <a href="/cashierapp/pages/add_supplier.php" class="add-supplier"><i class='bx bx-plus text-white' style="font-size:30px;"></i></a>
                        <?php endif; ?>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Supplier Name</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                <td>
                                    <a href="edit_supplier.php?id=<?php echo urlencode($row['id']); ?>" class="status completed">Edit</a>
                                    <a href="delete_supplier.php?id=<?php echo urlencode($row['id']); ?>" class="status pending" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
