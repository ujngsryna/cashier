<?php

require_once('db-connection.php');

function cariProduk($keyword) {
    global $conn;
    $query = "SELECT * FROM products WHERE nama_produk LIKE '%$keyword%' OR harga_produk LIKE '%$keyword%' OR kode_unik LIKE '%$keyword%'";
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambahkanProduk($id, $jumlah) {
    global $conn;
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $produk = mysqli_fetch_assoc($result);

    if (!$produk) {
        // Produk tidak ditemukan
        return false;
    }

    if ($jumlah > $produk['jumlah']) {
        // Jika jumlah yang diminta melebihi stok produk yang tersedia
        return false;
    } elseif ($jumlah <= 0) {
        // Jika jumlah yang diminta tidak valid (negatif atau nol)
        return false;
    } else {
        // Jika jumlah cukup, tambahkan produk ke dalam keranjang
        $produk['jumlah'] = $jumlah;
        return $produk;
    }
}

function kurangiProduk($index) {
    $struk = isset($_SESSION['struk']) ? $_SESSION['struk'] : [];
    unset($struk[$index]);
    $_SESSION['struk'] = array_values($struk);

    // Hitung ulang total harga setelah menghapus item
    $totalHarga = 0;
    foreach ($struk as $item) {
        $totalHarga += $item['harga_produk'] * $item['jumlah'];
    }
    $_SESSION['totalHarga'] = $totalHarga;
}

function cekProduk($produk, $struk) {
    foreach ($struk as $index => $item) {
        if ($item['id'] == $produk['id']) {
            return $index;
        }
    }
    return -1;
}

$struk = isset($_SESSION['struk']) ? $_SESSION['struk'] : [];
$totalHarga = isset($_SESSION['totalHarga']) ? $_SESSION['totalHarga'] : 0;
$error = '';

$rows = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];
    $rows = cariProduk($keyword);
} else {
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['tambah'])) {
    $id_produk = $_GET['id'];
    $jumlah = isset($_GET['jumlah']) ? $_GET['jumlah'] : 1;
    $produk = tambahkanProduk($id_produk, $jumlah);
    if ($produk === false) {
        $error = 'Stok produk habis!';
    } else {
        $index = cekProduk($produk, $struk);
        if ($index != -1) {
            $struk[$index]['jumlah'] += $jumlah;
        } else {
            array_push($struk, $produk);
        }
        $totalHarga = 0;
        foreach ($struk as $item) {
            $totalHarga += $item['harga_produk'] * $item['jumlah'];
        }
        $_SESSION['struk'] = $struk;
        $_SESSION['totalHarga'] = $totalHarga;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kurang'])) {
    $index = $_POST['index'];
    kurangiProduk($index);
    header("Location: " . $_SERVER['PHP_SELF']); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cetak'])) {
    // Periksa stok sebelum checkout
    $stokCukup = true;
    foreach ($struk as $produk) {
        $id_produk = $produk['id'];
        $jumlah = $produk['jumlah'];
        $query_stok = "SELECT jumlah FROM products WHERE id = $id_produk";
        $result_stok = mysqli_query($conn, $query_stok);

        if (!$result_stok) {
            // Jika query gagal dieksekusi
            $error = 'Gagal memeriksa stok produk.';
            $stokCukup = false;
            break;
        }

        $row_stok = mysqli_fetch_assoc($result_stok);
        if ($row_stok['jumlah'] < $jumlah) {
            $stokCukup = false;
            break;
        }
    }

    if (!$stokCukup) {
        $error = 'Stok produk tidak mencukupi untuk checkout.';
    } else {
        // Lanjutkan proses checkout
        if (isset($_POST['uang']) && isset($_POST['kembalian'])) {
            $uang = $_POST['uang'];
            $kembalian_transaksi = $_POST['kembalian'];

            if ($uang < $totalHarga) {
                $error = "Uang yang diterima kurang dari total harga!";
            } else {
                // Proses checkout
                $query_transaksi = "INSERT INTO transaksi (uang_pelanggan, kembalian, total_harga) VALUES ($uang, $kembalian_transaksi, $totalHarga)";
                $result_transaksi = mysqli_query($conn, $query_transaksi);
                if (!$result_transaksi) {
                    $error = 'Gagal memproses transaksi.';
                } else {
                    $id_transaksi = mysqli_insert_id($conn);

                    foreach ($struk as $produk) {
                        $nama_produk = $produk['nama_produk'];
                        $harga_produk = $produk['harga_produk'];
                        $jumlah = $produk['jumlah'];
                        $kode_unik = $produk['kode_unik'];
                        $totalHargaProduk = $harga_produk * $jumlah;

                        $query_produk = "INSERT INTO transaksi_produk (id_transaksi, nama_produk, harga_produk, jumlah, kode_unik, total_harga) VALUES ($id_transaksi, '$nama_produk', $harga_produk, $jumlah, '$kode_unik', $totalHargaProduk)";
                        $result_produk = mysqli_query($conn, $query_produk);
                        if (!$result_produk) {
                            $error = 'Gagal menyimpan informasi produk dalam transaksi!';
                            break;
                        }

                        $query_update_produk = "UPDATE products SET jumlah = jumlah - $jumlah WHERE id = $id_produk";
                        $result_update_produk = mysqli_query($conn, $query_update_produk);
                        if (!$result_update_produk) {
                            $error = 'Gagal mengurangi jumlah produk!';
                            break;
                        }
                    }

                    // Bersihkan keranjang dan total harga setelah checkout berhasil
                    $_SESSION['struk'] = [];
                    $_SESSION['totalHarga'] = 0;
                    header("Location: print_invoice.php?id_transaksi=$id_transaksi");
                    exit();
                }
            }
        } else {
            $error = "Mohon lengkapi informasi uang dan kembalian.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['refresh'])) {
    $_SESSION['struk'] = [];
    $_SESSION['totalHarga'] = 0;
    $struk = [];
    $totalHarga = 0;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>
