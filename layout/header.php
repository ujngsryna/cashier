<?php

include '../config/app.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ./index.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="../style/style.css">
    <link href='../img/logo_mini.png' rel='shortcut icon'>
    <title>KlikMart</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="dashboard.php" class="brand">
            <img src="../img/logo_mini.png" width="35px" height="35px"  alt="logo" class="logo">
            <span class="text">KlikMart</span>
        </a>
        <ul class="side-menu top">

        <?php if ($_SESSION['level'] == "owner" || $_SESSION['level'] == "admin") : ?>
            <li>
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['level'] == "kasir") : ?>
            <li>
                <a href="./transaction.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Transaction</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['level'] == "owner" || $_SESSION['level'] == "admin") : ?>
            <li>
                <a href="./user.php" class="nav-link">
                    <i class='bx bxs-group'></i>
                    <span class="text">Users</span>
                </a>
            </li>
            <?php endif; ?>
            
            
            <li>
                <a href="./products.php">
                    <i class='bx bxs-coffee'></i>
                    <span class="text">Products</span>
                </a>
            </li>

            <?php if ($_SESSION['level'] == "owner" || $_SESSION['level'] == "admin") : ?>
<li>
    <a href="./supplier.php">
        <i class='bx bxs-truck'></i>
        <span class="text">Suppliers</span>
    </a>
</li>
<?php endif; ?>


            <?php if ($_SESSION['level'] == "admin") : ?>
            <li>
                <a href="./activity.php">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Activity</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($_SESSION['level'] == "owner") : ?>
            <li>
                <a href="./report.php">
                    <i class='bx bxs-report'></i>
                    <span class="text">Report</span>
                </a>
            </li>
            <?php endif; ?>
            
        </ul>
        <ul class="side-menu">
            <li>
                <a href="../db/db-logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- NAVBAR -->
    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <div class="user-info">
                <a href="#" class="d-block"><?= $_SESSION['nama']; ?></a>
                <img src="../img/user.png" alt="Profile Picture" class="profile-picture">
            </div>
        </nav>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentLocation = window.location.href;
            const menuLinks = document.querySelectorAll('.side-menu a');

            menuLinks.forEach(link => {
                if (link.href === currentLocation) {
                    link.parentElement.classList.add('active');
                }
            });
        });
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

          nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            margin: 15px;
            margin-right: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            font-family: 'Lato', sans-serif;
        
        }
        .profile-picture {
            width: 35px;
            height: 35px;
            border-radius: 50%; 
            margin-right: 10px;
            border: 2px solid #ccc; 
            margin: 8px;
        }

        .user-info a {
            text-decoration: none;
            color: #333; 
            
        }
        
    </style>

    <script src="../javascript/script.js"></script>
</body>
</html>
