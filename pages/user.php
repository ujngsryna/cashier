<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');
$users = select("SELECT * FROM users");

// membatasi halaman sesuai user login
if ($_SESSION["level"] != "owner" and  $_SESSION['level'] != "admin") {
    echo "<script>
            window.history.back(); 
          </script>";
    exit;
  } 

?>
<section id="content">
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <!-- <h1>User</h1> -->
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Users</a>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Manage User</h3>
                    <?php if ($_SESSION['level'] == "admin" ||  $_SESSION['level'] == "owner") : ?>
                    <a href="add-user.php"><i class='bx bx-plus text-white'  style="font-size:30px;"></i></a>
                    <?php endif; ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Create At</th>
                            <th>Update At</th>
                            <th>Level</th>
                            <?php if ($_SESSION['level'] == "admin" ||  $_SESSION['level'] == "owner") : ?>
                            <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
        <?php $no = 1; ?>
        <!-- tampil seluruh data -->
        <?php foreach ($users as $user) : ?>
     <tr>
        <td><?= $user['nama']; ?></td>
        <td><?= date('d F Y H:i:s', strtotime($user['created_at'])); ?></td>
        <td><?= date('d F Y H:i:s', strtotime($user['updated_at'])); ?></td>
        <td><span class="status process"><?= $user['level']; ?></span></td>
        <td class="text-center">
        <?php if ($_SESSION['level'] == "admin" ||  $_SESSION['level'] == "owner") : ?>
        <a href="update-user.php?id=<?= $user['id']; ?>"><i class='bx bxs-edit text-blue' style="font-size:20px; "></i></a>
        <a href="../db/db-delete-user.php?id=<?= $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');"><i class='bx bxs-trash text-red' style="font-size:20px; "></i></a>
        <?php endif; ?>
        </td>
        <?php endforeach; ?>
            </table>
            </div>
        </div>
    </div>
</div>
</tbody>
    </table>
        </div>
            </main>


<style>
.text-white {
    color: #ffffff;
}
.text-blue {
    color: #007bff; /* Ubah sesuai dengan warna biru yang Anda inginkan */
}

.text-red {
    color: #ff0000; /* Ubah sesuai dengan warna merah yang Anda inginkan */
}
</style>