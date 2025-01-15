<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');
$users = select("SELECT * FROM users");

// Membatasi akses berdasarkan level pengguna
if ($_SESSION["level"] != "owner" && $_SESSION['level'] != "admin") {
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
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Manage User</h3>
                    <?php if ($_SESSION['level'] == "admin") : ?>
                    <a href="add-user.php"><i class='bx bx-plus text-white' style="font-size:30px;"></i></a>
                    <?php endif; ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Create At</th>
                            <th>Update At</th>
                            <th>Level</th>
                            <?php if ($_SESSION['level'] == "admin") : ?>
                            <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nama']); ?></td>
                            <td><?= date('d F Y H:i:s', strtotime($user['created_at'])); ?></td>
                            <td><?= date('d F Y H:i:s', strtotime($user['updated_at'])); ?></td>
                            <td><span class="status process"><?= htmlspecialchars($user['level']); ?></span></td>
                            <?php if ($_SESSION['level'] == "admin") : ?>
                            <td class="text-center">
                                <a href="update-user.php?id=<?= $user['id']; ?>"><i class='bx bxs-edit text-blue' style="font-size:20px;"></i></a>
                                <a href="../db/db-delete-user.php?id=<?= $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');"><i class='bx bxs-trash text-red' style="font-size:20px;"></i></a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</section>

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
