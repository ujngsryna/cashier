<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

$categories = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori_produk ORDER BY nama_kategori");
$has_supplier_column = column_exists('products', 'supplier_id');
$has_warehouse_stock_column = column_exists('products', 'warehouse_stock');
// If current user is supplier, fetch their supplier_id and hide the supplier select
$current_user_supplier_id = null;
if ($has_supplier_column && isset($_SESSION['level']) && $_SESSION['level'] === 'supplier') {
    $uid = intval($_SESSION['user_id']);
    $ures = mysqli_query($conn, "SELECT supplier_id FROM users WHERE id = $uid");
    $urow = $ures ? mysqli_fetch_assoc($ures) : null;
    $current_user_supplier_id = $urow['supplier_id'] ?? null;
} elseif ($has_supplier_column) {
    $suppliers = mysqli_query($conn, "SELECT id, name FROM suppliers ORDER BY name");
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
                        <a class="active" href="#">Add Product</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Add Product</h3>
                    <a href="products.php"> <i class='bx bx-undo text-white' href="products.php" style="font-size:30px;"></i></a>
                </div>
                <form action="../db/db-add-product.php" method="post">
                    <label for="name">Product Name :</label>
                    <input type="text" id="nama_produk" name="nama_produk" required><br>
                    <label for="harga">Price :</label>
                    <input type="text" id="harga_produk" name="harga_produk" required><br>
                    <label for="kategori_id">Category :</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['nama_kategori']); ?></option>
                        <?php endwhile; ?>
                    </select><br>
                    <?php if ($has_supplier_column): ?>
                        <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'supplier'): ?>
                            <label>Supplier: </label>
                            <div>
                                <?php if ($current_user_supplier_id): ?>
                                    <?php
                                    $sres = mysqli_query($conn, "SELECT name FROM suppliers WHERE id = " . intval($current_user_supplier_id));
                                    $srow = $sres ? mysqli_fetch_assoc($sres) : null;
                                    $sname = $srow['name'] ?? 'Unknown Supplier';
                                    ?>
                                    <strong><?= htmlspecialchars($sname); ?></strong>
                                    <input type="hidden" name="supplier_id" value="<?= intval($current_user_supplier_id); ?>">
                                <?php else: ?>
                                    <em>Anda belum ditautkan dengan supplier. Hubungi admin.</em>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <label for="supplier_id">Supplier :</label>
                            <select id="supplier_id" name="supplier_id" required>
                                <option value="">-- Select Supplier --</option>
                                <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                                    <option value="<?= $supplier['id']; ?>"><?= htmlspecialchars($supplier['name']); ?></option>
                                <?php endwhile; ?>
                            </select><br>
                        <?php endif; ?>
                    <?php endif; ?>
                    <label for="jumlah">Quantity :</label>
                    <input type="text" id="jumlah" name="jumlah" required><br>
                    <?php if ($has_warehouse_stock_column): ?>
                        <label for="warehouse_stock">Warehouse Stock :</label>
                        <input type="number" id="warehouse_stock" name="warehouse_stock" value="0" min="0" required><br>
                    <?php endif; ?>
                    <button type="submit" name="add_product">Submit</button>
                </form>
            </div>
        </div>
    </section>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

    .form-group {
        margin-bottom: 20px;
        
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
       
    }

    input[type="text"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    button[type="submit"] {
        background-color: #DC692C;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
        
    }

    button[type="submit"]:hover {
        background-color: #FD7238;
    }
    .text-white {
        color: #ffffff;
    }
</style>
