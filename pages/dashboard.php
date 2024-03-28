<?php 
session_start();
require_once('../db/db-connection.php');
include '../layout/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

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
                    <h3>3</h3>
                    <p>Total User</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">
                    <h3>5</h3>
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

    <!-- MAIN -->
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutBtn = document.getElementById('checkout-btn');
        const billingList = document.getElementById('billing-list');
        let totalCost = 0; // variabel untuk menyimpan total biaya

        // Event listener untuk setiap tombol "Add"
        const addButtons = document.querySelectorAll('.add-btn');
        addButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Ambil id produk dari atribut data-id
                const productId = this.getAttribute('data-id');

                // Ambil informasi produk dari baris tabel yang sesuai
                const productRow = document.getElementById(`product-row-${productId}`);
                const productName = productRow.querySelector('.product-name').innerText;
                const productPrice = parseInt(productRow.querySelector('.product-price').innerText.replace('Rp. ', '').replace(',', ''));

                // Cek apakah produk sudah ada di keranjang belanja
                const existingCartItem = billingList.querySelector(`tr[data-product-id="${productId}"]`);
                if (existingCartItem) {
                    // Jika produk sudah ada, tambahkan jumlahnya
                    const quantityCell = existingCartItem.querySelector('.quantity');
                    const currentQuantity = parseInt(quantityCell.innerText);
                    quantityCell.innerText = currentQuantity + 1;

                    // Update total harga
                    const totalCell = existingCartItem.querySelector('.total-cost');
                    const currentTotal = parseInt(totalCell.innerText.replace('Rp. ', '').replace(',', ''));
                    totalCell.innerText = `Rp. ${currentTotal + productPrice}`;
                } else {
                    // Jika produk belum ada, tambahkan baris baru
                    const listItem = document.createElement('tr');
                    listItem.dataset.productId = productId;
                    listItem.innerHTML = `
                        <td class="product-name">${productName}</td>
                        <td class="quantity">1</td>
                        <td class="total-cost">Rp. ${productPrice}</td>
                    `;
                    billingList.appendChild(listItem);
                }

                // Tambahkan biaya produk ke totalCost
                totalCost += productPrice;
                // Update total biaya
                updateTotalCost();
            });
        });

        // Event listener untuk tombol Checkout
        checkoutBtn.addEventListener('click', function() {
            // Lakukan tindakan checkout di sini (misalnya, hit API untuk menyimpan pesanan)
            // Setelah itu, bersihkan daftar pembelian
            billingList.innerHTML = '';
            totalCost = 0; // reset totalCost
            updateTotalCost(); // reset tampilan total biaya
            alert('Checkout berhasil!');
        });

        // Fungsi untuk menampilkan total biaya
        function updateTotalCost() {
            const totalCostElement = document.getElementById('total-cost');
            totalCostElement.innerText = `Total: Rp. ${totalCost.toLocaleString()}`;
        }
    });
</script>
