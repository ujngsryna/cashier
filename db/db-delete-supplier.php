<?php
session_start();
include '../config/app.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Ambil ID supplier dari URL

    if (delete_supplier($id) > 0) {
        echo "<script>
                alert('Supplier berhasil dihapus!');
                document.location.href = '../pages/supplier.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus supplier.');
                document.location.href = '../pages/supplier.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID supplier tidak ditemukan.');
            document.location.href = '../pages/supplier.php';
          </script>";
}
?>
