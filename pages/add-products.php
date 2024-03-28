<?php 
session_start();
include '../layout/header.php';
require_once('../db/db-connection.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
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
                        <a class="active" href="#">Add Product</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Add Product</h3>
                    <a href="products.php"> <i class='bx bx-undo text-white' href="products.php" style="font-size:30px;"></i></a>
                </div>
                <form action="../db/db-add-product.php" method="post">
                    <label for="name">Product Name :</label>
                    <input type="text" id="nama_produk" name="nama_produk" required><br>
                    <label for="harga">Price :</label>
                    <input type="text" id="harga_produk" name="harga_produk" required><br>
                    <label for="jumlah">Quantity :</label>
                    <input type="text" id="jumlah" name="jumlah" required><br>
                    <button type="submit" name="add_product">Submit</button>
                </form>
            </div>
        </div>
    </section>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

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
