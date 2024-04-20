<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

// Ambil semua data produk
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

// Ambil data produk berdasarkan ID
$product_id = isset($_GET['id']) ? $_GET['id'] : null;
$product = [];
if ($product_id !== null) {
    foreach ($rows as $row) {
        if ($row['id'] == $product_id) {
            $product = $row;
            break;
        }
    }
}

// Tambah DATA atau Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $nama_produk = $_POST['nama'];
    // Hilangkan tanda titik dari input harga sebelum menyimpannya
    $harga_produk = str_replace('.', '', $_POST['harga_produk']);
    $jumlah = $_POST['jumlah'];
    
    $query = "UPDATE products SET nama_produk='$nama_produk', harga_produk=$harga_produk, updated_at=NOW(), jumlah=$jumlah WHERE id=$id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: products.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

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
                        <a class="active" href="#">Update Product</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Update Product</h3>
                    <a href="products.php"><i class='bx bx-undo text-white' style="font-size:30px;"></i></a>
                </div>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <!-- Populasikan input field dengan data produk -->
                    <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                    <label for="nama">Name :</label>
                    <input type="text" id="nama" name="nama" value="<?= $product['nama_produk'] ?? ''; ?>" required><br>
                    <label for="harga_produk">Price :</label>
                    <input type="text" id="harga_produk" name="harga_produk" value="<?= $product['harga_produk'] ?? ''; ?>" required><br>
                    <label for="jumlah">Quantity :</label>
                    <input type="text" id="jumlah" name="jumlah" value="<?= $product['jumlah'] ?? ''; ?>" required><br>
                    <!-- Tambahkan field lain sesuai kebutuhan, seperti quantity -->
                    <button type="submit" name="update_product">Submit</button>
                </form>
            </div>
        </div>
    </section>

<style>
/* CSS untuk mempercantik form */
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
