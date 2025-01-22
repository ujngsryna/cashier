<?php
// Koneksi ke database
include '../db/db-connection.php';


// Query untuk mendapatkan data supplier menggunakan prepared statement untuk keamanan
$query = "SELECT * FROM suppliers";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers</title>
    <link rel="stylesheet" href="../style/style.css"> <!-- Link ke file CSS Anda -->
</head>
<body>
   <?phpinclude '../layout/header.php';?>
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
                    <h1>Suppliers</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a></li>
                        <li><a href="#" class="active">Suppliers</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Supplier List</h3>
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
