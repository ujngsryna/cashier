<?php
session_start();
require_once('../db/db-connection.php');
include '../layout/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
// Memeriksa level pengguna
if ($_SESSION["level"] != "admin") {
    echo "<script>
            window.history.back();
          </script>";
    exit;
}

// Ambil data log aktivitas dari database
$query = "SELECT * FROM activity_log ORDER BY timestamp DESC";
$result = $conn->query($query);

if ($result === false) {
    // Menangani kesalahan saat eksekusi query
    die("Error fetching activity log: " . $conn->error);
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
                    <div>
                        <!-- Show entries table -->
                        <label for="entries">Show entries:</label>
                        <select id="entries" class="form-select-custom" onchange="showEntries()">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="300">All</option>
                        </select>
                    </div>
                </div>
                <table id="reportTable">
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
                <td><?php echo date('d F Y H:i:s', strtotime($row['timestamp'])); ?></td>
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
<script>
    function showEntries() {
        var table = document.getElementById("reportTable");
        var rowCount = table.rows.length - 1; // Exclude header row
        var entries = parseInt(document.getElementById("entries").value); // Ambil nilai dan konversi ke integer
        var startIndex = 1;
        var endIndex = entries;

        if (rowCount > entries) {
            for (var i = 1; i <= rowCount; i++) {
                if (i >= startIndex && i <= endIndex) {
                    table.rows[i].style.display = "";
                } else {
                    table.rows[i].style.display = "none";
                }
            }
        } else {
            for (var i = 1; i <= rowCount; i++) {
                table.rows[i].style.display = "";
            }
        }
    }
</script>