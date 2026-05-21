<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['level'] !== 'supplier') {
    echo "<script>alert('Access denied.'); window.location.href = 'dashboard.php';</script>";
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

// Pastikan kolom `supplier_id` ada di tabel users dan products sebelum menjalankan query
$has_users_supplier = false;
$has_products_supplier = false;
$res = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'supplier_id'");
if ($res && mysqli_num_rows($res) > 0) {
    $has_users_supplier = true;
}
$res = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'supplier_id'");
if ($res && mysqli_num_rows($res) > 0) {
    $has_products_supplier = true;
}

if (!$has_users_supplier || !$has_products_supplier) {
    $error_message = 'Fitur restock belum diaktifkan. Hubungi admin untuk melakukan migrasi database.';
    $products = [];
} else {
    $user_id = $_SESSION['user_id'];
    $user_result = mysqli_query($conn, "SELECT supplier_id FROM users WHERE id = $user_id");
    $user = $user_result ? mysqli_fetch_assoc($user_result) : null;
    $supplier_id = $user['supplier_id'] ?? null;

    if (!$supplier_id) {
        $error_message = 'Akun supplier Anda belum dihubungkan dengan data supplier. Hubungi admin untuk mengaitkan supplier.';
        $products = [];
    } else {
        $products = [];
        $query = "SELECT p.*, c.nama_kategori, s.name AS supplier_name FROM products p LEFT JOIN kategori_produk c ON p.kategori_id = c.id LEFT JOIN suppliers s ON p.supplier_id = s.id WHERE p.supplier_id = $supplier_id";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restock Gudang</title>
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
                        <li><a href="#" class="active">Restock Gudang</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Restock Warehouse</h3>
                        <a href="./add-products.php" style="padding:10px 15px;background-color:#DC692C;color:white;text-decoration:none;border-radius:5px;font-weight:bold;">+ Add Product</a>
                    </div>
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Display Stock</th>
                                <th>Warehouse Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)) : ?>
                                <tr><td colspan="5" style="text-align:center;padding:20px;">
                                    <?php if (isset($supplier_id) && $supplier_id): ?>
                                        <p><strong>Belum ada produk yang didaftarkan.</strong></p>
                                        <p>Silakan <a href="./add-products.php" style="color:#DC692C;font-weight:bold;">daftarkan produk Anda di sini</a> untuk memulai restock.</p>
                                    <?php else: ?>
                                        <p>Hubungi admin untuk mengaitkan akun Anda dengan supplier.</p>
                                    <?php endif; ?>
                                </td></tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['nama_produk']); ?></td>
                                        <td><?= htmlspecialchars($product['nama_kategori'] ?? 'Uncategorized'); ?></td>
                                        <td><?= $product['jumlah']; ?></td>
                                        <td><?= $product['warehouse_stock']; ?></td>
                                        <td>
                                            <form action="../db/db-supplier-restock.php" method="post" style="display:inline-block;">
                                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                                <input type="number" name="quantity" min="1" value="1" style="width:70px;">
                                                <button type="submit" name="restock" class="btn btn-primary">Tambah Gudang</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
