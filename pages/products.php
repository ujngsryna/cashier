<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

$has_supplier_id = column_exists('products', 'supplier_id');
$has_warehouse_stock = column_exists('products', 'warehouse_stock');

$supplier_select = $has_supplier_id ? 's.name AS supplier_name' : 'NULL AS supplier_name';
$supplier_join = $has_supplier_id ? 'LEFT JOIN suppliers s ON p.supplier_id = s.id' : '';
$warehouse_select = $has_warehouse_stock ? 'p.warehouse_stock' : '0 AS warehouse_stock';

$product = select("SELECT p.*, c.nama_kategori, {$supplier_select}, {$warehouse_select} 
                  FROM products p 
                  LEFT JOIN kategori_produk c ON p.kategori_id = c.id 
                  {$supplier_join}");
?>

<style>
.text-white {
    color: #ffffff;
}
.text-blue {
    color: #007bff;
}
.text-red {
    color: #ff0000;
}
</style>

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
                        <a class="active" href="#">Products</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Manage Product</h3>
                    <?php if ($_SESSION['level'] == "admin") : ?>
                    <div>
                        <a href="manage-categories.php" class="me-2"><i class='bx bx-category text-white' style="font-size:30px;"></i></a>
                        <a href="add-products.php"><i class='bx bx-plus text-white' style="font-size:30px;"></i></a>
                    </div>
                    <?php endif; ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Warehouse Stock</th>
                            <th>Product Price</th>
                            <th>Latest Update</th>
                            <?php if ($_SESSION['level'] == "admin") : ?>
                            <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product as $products): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($products['nama_produk']); ?></td>
                            <td><?php echo htmlspecialchars($products['nama_kategori'] ?? 'Uncategorized'); ?></td>
                            <td><?php echo htmlspecialchars($products['supplier_name'] ?? 'Unknown'); ?></td>
                            <td><?php echo $products['jumlah']; ?></td>
                            <td><?php echo $products['warehouse_stock']; ?></td>
                            <td>Rp. <?php echo number_format($products["harga_produk"]); ?></td>
                            <td><?php echo date('d F Y H:i:s', strtotime($products['updated_at'])); ?></td>
                            <?php if ($_SESSION['level'] == "admin") : ?>
                            <td class="text-center">
                                <a href="update-products.php?id=<?= $products['id']; ?>"><i class='bx bxs-edit text-blue' style="font-size:20px;"></i></a>
                                <a href="../db/db-delete-product.php?id=<?= $products['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');"><i class='bx bxs-trash text-red' style="font-size:20px;" name="delete_product"></i></a>
                                <?php if ($products['jumlah'] == 0 && $products['warehouse_stock'] > 0) : ?>
                                    <a href="warehouse-transfer.php?id=<?= $products['id']; ?>" onclick="return confirm('Ambil stok dari gudang untuk produk ini?');"><i class='bx bxs-down-arrow text-white' style="font-size:20px;"></i></a>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</section>