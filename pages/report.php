<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}
// Memeriksa level pengguna
if ($_SESSION["level"] != "owner") {
    echo "<script>
            window.history.back();
          </script>";
    exit;
}

require_once('../db/db-connection.php');
include '../layout/header.php';


$transaksi = select("SELECT * FROM transaksi_produk");
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
                        <a class="active" href="#">Report</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Report</h3>
                    <div>
                        <!-- Show entries table -->
                        <label for="entries">Show entries:</label>
                        <select id="entries" class="form-select-custom" onchange="showEntries()">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="300">All</option>
                        </select>
                    </div>
                </div>
                <div class="form-outline">
                    <table id="reportTable">
                        <thead>
                            <th>Product Name</th>
                            <th>Date Order</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                        </thead>
                        <tbody>
                            <?php foreach ($transaksi as $row) : ?>
                                <tr>
                                    <td><?php echo $row['nama_produk']; ?></td>
                                    <td><?php echo date('d F Y H:i:s', strtotime($row['tanggal_transaksi'])); ?></td>
                                    <td><?php echo $row['jumlah']; ?></td>
                                    <td>Rp. <?php echo number_format($row["total_harga"]); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</section>

<style>
    .text-white {
        color: #ffffff;
    }

    .text-blue {
        color: #007bff;
        /* Ubah sesuai dengan warna biru yang Anda inginkan */
    }

    .text-red {
        color: #ff0000;
        /* Ubah sesuai dengan warna merah yang Anda inginkan */
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

</html>
