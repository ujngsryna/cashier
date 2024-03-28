<?php 
session_start();
require_once('db-connection.php');

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    $stmt = $conn->prepare("UPDATE users SET nama = ?, username = ?, password = ?, level = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nama, $username, $password, $level, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Update successfully!";
        } else {
            echo "No changes made to the user";
        }
    } else {
        echo "Failed to update user.";
    }

    $stmt->close(); // Close the statement here, inside the conditional block
}

header('location: ../pages/user.php');
exit;
?>

  // Periksa apakah parameter id sudah diterima
  if(isset($_GET['id'])) {
      $id = $_GET['id'];

      // Ambil data pengguna berdasarkan ID
      $user = select("SELECT * FROM users WHERE id = ?", [$id]);

      // Pastikan data pengguna ditemukan
      if($user) {
          $user = $user[0]; // Ambil data pengguna pertama
      } else {
          echo "User not found!";
          exit;
      }
  } else {
      echo "User ID not provided!";
      exit;
  }

  // Jika form dikirimkan
  if(isset($_POST['submit'])) {
      // Ambil data dari form
      $nama = $_POST['nama'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      $level = $_POST['level'];

      // Update data pengguna di database
      $update_result = update("UPDATE users SET nama = ?, username = ?, password = ?, level = ? WHERE id = ?", [$nama, $username, $password, $level, $id]);

      if($update_result) {
          echo "User updated successfully!";
      } else {
          echo "Failed to update user!";
      }
  }
