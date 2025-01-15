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
            font-size: 14px;
            color: #333;
        }

        .container {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .logo {
            max-width: 80px;
            margin-bottom: 10px;
        }

        .details, .total-section, .footer {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
        }

        .no-print {
            margin-top: 20px;
        }

        /* Sembunyikan tombol cetak saat dicetak */
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
            <h5>Enigmachino</h5>
        </div>

        <div class="text-center my-3">
            <h4>Rp<?php echo number_format($totalHarga, 0, ',', '.'); ?></h4>
            <p><strong>Jumlah</strong></p>
        </div>

        <div class="details">
            <p>Order: <?php echo $id_transaksi; ?> <?php echo date('d/m/Y H:i'); ?></p>
            
        </div>

        <div class="details">
            <?php foreach ($transaksi_produk as $produk) : ?>
                <p><?php echo $produk['nama_produk']; ?></p>
                <p><?php echo $produk['jumlah']; ?> × Rp<?php echo number_format($produk['harga_produk'], 0, ',', '.'); ?></p>
            <?php endforeach; ?>
        </div>

        <div class="total-section">
            <p><strong>Subtotal:</strong> Rp<?php echo number_format($totalHarga, 0, ',', '.'); ?></p>
            <p><strong>Jumlah:</strong> Rp<?php echo number_format($totalHarga, 0, ',', '.'); ?></p>
            <p><strong>Tunai:</strong> Rp<?php echo number_format($uangPelanggan, 0, ',', '.'); ?></p>
            <p><strong>Kembalian:</strong> Rp<?php echo number_format($kembalian, 0, ',', '.'); ?></p>
        </div>

        <div class="footer">
            <p>Terima kasih atas pembelian Anda!</p>
            <p>Alamat: Jl. Braga No.38, Bandung</p>
            
        </div>

        <div class="text-center no-print">
            <button class="btn btn-primary" onclick="window.print()">Print</button>
            <a href="transaction.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
