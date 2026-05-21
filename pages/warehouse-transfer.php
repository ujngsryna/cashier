<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['level'] !== 'admin') {
    echo "<script>alert('Access denied.'); window.location.href = 'dashboard.php';</script>";
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = intval($_GET['id']);
$product_result = mysqli_query($conn, "SELECT p.*, c.nama_kategori, s.name AS supplier_name FROM products p LEFT JOIN kategori_produk c ON p.kategori_id = c.id LEFT JOIN suppliers s ON p.supplier_id = s.id WHERE p.id = $product_id");
$product = mysqli_fetch_assoc($product_result);

if (!$product) {
    echo "Product not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Stok Gudang</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div id="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="#" class="active">Ambil Stok Gudang</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Ambil Stok dari Gudang</h3>
                        <a href="products.php"> <i class='bx bx-undo text-white' style="font-size:30px;"></i></a>
                    </div>
                    <div class="details">
                        <p><strong>Product:</strong> <?= htmlspecialchars($product['nama_produk']); ?></p>
                        <p><strong>Supplier:</strong> <?= htmlspecialchars($product['supplier_name'] ?? 'Unknown'); ?></p>
                        <p><strong>Display Stock:</strong> <?= $product['jumlah']; ?></p>
                        <p><strong>Warehouse Stock:</strong> <?= $product['warehouse_stock']; ?></p>
                    </div>
                    <form action="../db/db-warehouse-transfer.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                        <label for="quantity">Jumlah yang ditarik dari gudang:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?= $product['warehouse_stock']; ?>" value="1" required><br>
                        <button type="submit" name="transfer">Transfer ke Display</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
