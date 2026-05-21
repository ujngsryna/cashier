<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

$categories = mysqli_query($conn, "SELECT id, nama_kategori FROM kategori_produk ORDER BY nama_kategori");
$has_supplier_column = false;
$has_warehouse_stock_column = false;

$result = $conn->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
if ($result && $result->num_rows > 0) {
    $has_supplier_column = true;
}
$result = $conn->query("SHOW COLUMNS FROM products LIKE 'warehouse_stock'");
if ($result && $result->num_rows > 0) {
    $has_warehouse_stock_column = true;
}

if ($has_supplier_column) {
    $suppliers = mysqli_query($conn, "SELECT id, name FROM suppliers ORDER BY name");
} else {
    $suppliers = [];
}

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
    $kategori_id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 0;

    $has_supplier_column = false;
    $has_warehouse_stock_column = false;
    $result = $conn->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
    if ($result && $result->num_rows > 0) {
        $has_supplier_column = true;
    }
    $result = $conn->query("SHOW COLUMNS FROM products LIKE 'warehouse_stock'");
    if ($result && $result->num_rows > 0) {
        $has_warehouse_stock_column = true;
    }

    $supplier_id = $has_supplier_column ? intval($_POST['supplier_id']) : null;
    $warehouse_stock = $has_warehouse_stock_column ? intval($_POST['warehouse_stock']) : 0;

    $updateFields = [
        "nama_produk='$nama_produk'",
        "harga_produk=$harga_produk",
        "updated_at=NOW()",
        "jumlah=$jumlah",
        "kategori_id=$kategori_id"
    ];

    if ($has_warehouse_stock_column) {
        $updateFields[] = "warehouse_stock=$warehouse_stock";
    }
    if ($has_supplier_column) {
        $updateFields[] = "supplier_id=$supplier_id";
    }

    $query = "UPDATE products SET " . implode(', ', $updateFields) . " WHERE id=$id";
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
                    <label for="kategori_id">Category :</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $category['id']; ?>" <?= isset($product['kategori_id']) && $product['kategori_id'] == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['nama_kategori']); ?></option>
                        <?php endwhile; ?>
                    </select><br>
                    <?php if ($has_supplier_column): ?>
                        <label for="supplier_id">Supplier :</label>
                        <select id="supplier_id" name="supplier_id" required>
                            <option value="">-- Select Supplier --</option>
                            <?php while ($supplier = mysqli_fetch_assoc($suppliers)): ?>
                                <option value="<?= $supplier['id']; ?>" <?= isset($product['supplier_id']) && $product['supplier_id'] == $supplier['id'] ? 'selected' : '' ?>><?= htmlspecialchars($supplier['name']); ?></option>
                            <?php endwhile; ?>
                        </select><br>
                    <?php endif; ?>
                    <label for="jumlah">Quantity :</label>
                    <input type="text" id="jumlah" name="jumlah" value="<?= $product['jumlah'] ?? ''; ?>" required><br>
                    <?php if ($has_warehouse_stock_column): ?>
                        <label for="warehouse_stock">Warehouse Stock :</label>
                        <input type="number" id="warehouse_stock" name="warehouse_stock" value="<?= $product['warehouse_stock'] ?? 0; ?>" min="0" required><br>
                    <?php endif; ?>
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
