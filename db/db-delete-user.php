<?php

session_start();

// membatasi halaman sebelum login
// if (!isset($_SESSION["login"])) {
//   echo "<script>
//           alert('Anda perlu login untuk memasuki halaman');
//           document.location.href = '../index.php';
//         </script>";
//   exit;
// }

include '../config/app.php';

// menerima id akun yang dipilih pengguna
$id = (int)$_GET['id'];

if (delete_akun($id) > 0) {
    echo "<script>
            alert('Success!');
            document.location.href = '../pages/user.php';
          </script>";
} else {
    echo "<script>
            alert('Failed');
            document.location.href = '../pages/user.php';
          </script>";
}