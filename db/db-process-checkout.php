<?php
session_start();
require_once('db-connection.php');

if (isset($_POST['checkout_product'])) {
    $product_id = $_POST['product_id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $invoice_amount = $product['harga_produk'] * $product['jumlah'];

        $invoice_data = [
            'product_id' => $product['id'],
            'nama_produk' => $product['nama_produk'],
            'harga_produk' => $product['harga_produk'],
            'jumlah' => $product['jumlah'],
            'kode_unik' => $product['kode_unik'],
            'updated_at' => $product['updated_at'],
            'invoice_amount' => $invoice_amount, 
        ];

        $_SESSION['invoice_data'] = $invoice_data;

        header('Location: ../pages/kasir/show-invoice.php');
        exit;
    } else {
        echo "Product not found.";
    }
}

?>