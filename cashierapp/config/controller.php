<?php

// fungsi menampilkan
function select($query)
{
    // panggil koneksi database
    global $db;

    $result = mysqli_query($db, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// fungsi tambah akun
function create_akun($post)
{
    global $db;
    
    $nama       = strip_tags($post['nama']);
    $username   = strip_tags($post['username']);
    $password   = strip_tags($post['password']);
    $level      = strip_tags($post['level']);

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // query tambah data
    $query = "INSERT INTO users VALUES(null, '$nama', '$username','$password', '$level')";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi ubah akun
function update_akun($post)
{
    global $db;

    $id   = strip_tags($post['id']);
    $nama       = strip_tags($post['nama']);
    $username   = strip_tags($post['username']);
    $password   = strip_tags($post['password']);
    $level      = strip_tags($post['level']);

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // query ubah data
    $query = "UPDATE users SET nama = '$nama', username = '$username', password = '$password', level = '$level' WHERE id_akun = $id";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}
// fungsi menghapus data akun
function delete_akun($id)
{
    global $db;

    // Ambil nama pengguna sebelum menghapus
    $result = mysqli_query($db, "SELECT nama FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $nama_pengguna = $row['nama'];

    // Query hapus data akun
    $query = "DELETE FROM users WHERE id = $id";
    mysqli_query($db, $query);

    // Cek apakah penghapusan berhasil
    if (mysqli_affected_rows($db) > 0) {
        // Jika berhasil, tambahkan entri log
        $timestamp = date("Y-m-d H:i:s");
        $username = $_SESSION['username'];
        $action = "Delete User";
        $product_id = $id; // Gunakan id pengguna yang dihapus sebagai id produk dalam log
        $product_name = $nama_pengguna;

        $log_query = "INSERT INTO activity_log (timestamp, username, action, product_id, product_name) VALUES ('$timestamp', '$username', '$action', $product_id, '$product_name')";
        mysqli_query($db, $log_query);
    } else {
        // Jika gagal menghapus, tampilkan pesan atau lakukan penanganan kesalahan
        // Anda dapat menambahkan kode penanganan kesalahan di sini
    }

    return mysqli_affected_rows($db);
}

// fungsi menghapus data akun
function delete_product($id)
{
    global $db;

    // pastikan variabel $db telah didefinisikan sebelumnya dengan koneksi database

    // query hapus data produk
    $query = "DELETE FROM products WHERE id = $id";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}
