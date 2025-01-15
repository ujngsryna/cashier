<?php 
session_start();

require_once('../db/db-connection.php');
include '../layout/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;

    // Memeriksa level pengguna
if ($_SESSION["level"] != "kasir") {
    echo "<script>
            window.history.back();
          </script>";
    exit;
}

}   

// FUNGSI CARD INFO UNTUK LEVEL OWNER //
// Lakukan query untuk mengambil total terjual
$totalTerjualQuery = "SELECT SUM(jumlah) AS total_terjual FROM transaksi_produk";
$result = mysqli_query($conn, $totalTerjualQuery);

if ($result) {
    // Ambil data hasil query
    $row = mysqli_fetch_assoc($result);
    // Ambil total terjual
    $totalTerjual = $row['total_terjual'];
} else {
    // Jika query gagal, atur total terjual menjadi 0
    $totalTerjual = 0;
}

// Lakukan query untuk mengambil total pendapatan
$totalPendapatanQuery = "SELECT SUM(total_harga) AS total_pendapatan FROM transaksi_produk";
$result = mysqli_query($conn, $totalPendapatanQuery);

if ($result) {
    // Ambil data hasil query
    $row = mysqli_fetch_assoc($result);
    // Ambil total pendapatan
    $totalPendapatan = $row['total_pendapatan'];
} else {
    // Jika query gagal, atur total terjual menjadi 0
    $totalPendapatan = 0;
}

$product = select("SELECT * FROM products");

// Hitung total user
$query_user = "SELECT COUNT(*) AS total_user FROM users"; // Ganti 'users' dengan nama tabel user Anda
$result_user = mysqli_query($conn, $query_user);
$total_user = mysqli_fetch_assoc($result_user)['total_user'];

// Hitung total produk
$query_product = "SELECT COUNT(*) AS total_product FROM products"; // Ganti 'products' dengan nama tabel produk Anda
$result_product = mysqli_query($conn, $query_product);
$total_product = mysqli_fetch_assoc($result_product)['total_product'];

?>

<!-- CONTENT -->
<section id="content">
    <main>
        <div class="head-title">
            <div class="left">  
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php if ($_SESSION['level'] == "admin") : ?>
        <ul class="box-info">
        <li>
    <i class='bx bxs-group'></i>
    <span class="text">
        <h3><?php echo $total_user; ?></h3> <!-- Data dari database -->
        <p>Total User</p>
    </span>
</li>
<li>
    <i class='bx bxs-shopping-bag-alt'></i>
    <span class="text">
        <h3><?php echo $total_product; ?></h3> <!-- Data dari database -->
        <p>Total Product</p>
    </span>
</li>

            <?php endif; ?>

        <?php if ($_SESSION['level'] == "owner") : ?>
        <ul class="box-info">
        <li>
                <i class='bx bxs-coffee'></i>
                <span class="text">
                    <h3><?php echo $totalTerjual; ?></h3>
                    <p>Sold</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-dollar-circle'></i>
                <span class="text">
                    <h3>Rp. <?php echo number_format( $totalPendapatan); ?> </h3>
                    <p>Total Sales</p>
                </span>
            </li>
        </ul>
        <?php endif; ?>


</section>
