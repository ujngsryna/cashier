<?php
session_start();
require_once('../db/db-connection.php');
include '../layout/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// Ambil data log aktivitas dari database
$query = "SELECT * FROM activity_log ORDER BY timestamp DESC";
$result = $conn->query($query);

if ($result === false) {
    // Menangani kesalahan saat eksekusi query
    die("Error fetching activity log: " . $conn->error);
}
// membatasi halaman sesuai user login
if ($_SESSION["level"] == "admin") {
    echo "<script>
            window.history.back(); 
          </script>";
    exit;
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
                            <a class="active" href="#">Users</a>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Log Activity</h3>
                </div>
                <table>
                    <thead>
                    <th>User</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                    </thead>
                    <tbody>
        <?php $no = 1; ?>
        <!-- tampil seluruh data -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['action']; ?></td>
                <td><?php echo $row['timestamp']; ?></td>
            </tr>
        <?php endwhile; ?>
            </table>
            </div>
        </div>
    </div>
</div>
</tbody>
    </table>
        </div>
            </main>


<style>
.text-white {
    color: #ffffff;
}
.text-blue {
    color: #007bff; /* Ubah sesuai dengan warna biru yang Anda inginkan */
}

.text-red {
    color: #ff0000; /* Ubah sesuai dengan warna merah yang Anda inginkan */
}
</style>