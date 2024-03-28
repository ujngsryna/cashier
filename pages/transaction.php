<?php 
session_start();
require_once('../db/db-connection.php');
include '../db/db-transaction.php';
include '../layout/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}



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
                        <a class="active" href="#">Transaction</a>
                    </li>
                </ul>
            </div>
        </div>

        <?php if ($_SESSION['level'] == "kasir") : ?>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Menu</h3>
                    <i class='bx bx-search'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rows)) : ?>
                      <?php foreach ($rows as $row) : ?>
                        <tr>
                <td><?php echo $row['nama_produk']; ?></td>
                <td>Rp <?php echo number_format($row['harga_produk'], 0, ',', '.'); ?></td>
                <td>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="jumlah" value="1" min="1" class="form-control" style="width: 70px; display: inline-block;">
                        <button class="status process add-btn" name="tambah">add</button>
                    </form>
                </td>
                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="todo">
                <div class="order">
                    <div class="head">
                        <h3>Cart</h3>
                    </div>
                    <table>
                        <thead>
                            <tr class="">
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Total Cost</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="billing-list">
                        <?php foreach ($struk as $index => $produk) : ?>
                <tr>
                    <td><?php echo $produk['nama_produk']; ?></td>
                    <td><?php echo $produk['jumlah']; ?></td>
                    <td><?php echo $produk['harga_produk']; ?></td>
                    <td>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit" name="kurang" class="status pending">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <table>
                        <th>
                            <tr>
                                <td><h4>Subtotal : Rp. <?php echo number_format($totalHarga, 0, ',', '.'); ?></h4></td>
                            </tr>
                        </th>
                    </table>
                    <table>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="mb-3">
                            <label for="uang" class="form-label">Cash Received:</label>
                            <input type="text" name="uang" id="uang" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="kembalian" class="form-label">Change Amount:</label>
                            <input type="text" name="kembalian" id="kembalian" class="form-control" readonly>
                        </div>
                        <center>
                            <td><button name="cetak" class="status process">Checkout</button>
                            <button name="refresh" class="status pending">Reset</button></td>
                        </center>
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                    </form>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>
    <!-- MAIN -->
</section>

<script>
    // Hitung kembalian saat input uang pelanggan
    document.getElementById('uang').addEventListener('input', function() {
        var uang = parseFloat(this.value);
        var totalHarga = <?php echo $totalHarga; ?>;
        var kembalian = uang - totalHarga;
        document.getElementById('kembalian').value = kembalian >= 0 ? kembalian : 0;
    });
</script>
