<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include_once '../db/db-connection.php';

// Periksa apakah ID transaksi disediakan dalam parameter GET
if (!isset($_GET['id_transaksi'])) {
    echo "ID transaksi tidak ditemukan.";
    exit();
}

$id_transaksi = $_GET['id_transaksi'];

$query = "SELECT * FROM transaksi_produk WHERE id_transaksi = $id_transaksi";
$result = mysqli_query($conn, $query);

// Pastikan data transaksi ditemukan
if (mysqli_num_rows($result) == 0) {
    echo "Data transaksi tidak ditemukan.";
    exit();
}

$transaksi_produk = [];
$totalHarga = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $transaksi_produk[] = $row;
    $totalHarga += $row['total_harga'];
}

// Ambil data uang pelanggan dan kembalian dari database
$query_transaksi = "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi";
$result_transaksi = mysqli_query($conn, $query_transaksi);
if (!$result_transaksi) {
    echo "Error: " . mysqli_error($conn);
    exit();
}
$transaksi = mysqli_fetch_assoc($result_transaksi);
$uangPelanggan = $transaksi['uang_pelanggan'];
$kembalian = $transaksi['kembalian'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .struk {
            border: 1px solid #000;
            padding: 20px;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }

        .text-center {
            text-align: center;
        }

        /* Sembunyikan tombol cetak saat halaman dicetak */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <img src="../img/logo.png" alt="Logo" class="logo">
            <h2>Morcoffee</h2>
            <p>Follow us on Instagram @morcoffeed</p>
        </div>
        <?php foreach ($transaksi_produk as $produk) : ?>
            <div class="struk">
                <p><strong>Product Name:</strong> <?php echo $produk['nama_produk']; ?></p>
                <p><strong>Product Price:</strong> Rp. <?php echo number_format($produk['harga_produk'], 0, ',', '.'); ?></p>
                <p><strong>Quantity:</strong> <?php echo $produk['jumlah']; ?></p>
                <p><strong>Total Cost:</strong> Rp. <?php echo number_format($produk['total_harga'], 0, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>
        <div class="struk">
            <p><strong>Cash :</strong> Rp. <?php echo number_format($uangPelanggan, 0, ',', '.'); ?></p>
            <p><strong>Subtotal :</strong> Rp. <?php echo number_format($totalHarga, 0, ',', '.'); ?></p>
            <p><strong>Cash Amount :</strong> Rp. <?php echo number_format($kembalian, 0, ',', '.'); ?></p>
        </div>
        <div class="text-center">
            <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
            <a href="transaction.php" class="btn btn-secondary no-print">Back</a>
        </div>
    </div>
</body>
</html>
