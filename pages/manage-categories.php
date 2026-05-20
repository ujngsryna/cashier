<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['level'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');
$categories = select("SELECT k.*, COUNT(p.id) as product_count 
                     FROM kategori_produk k 
                     LEFT JOIN products p ON k.id = p.kategori_id 
                     GROUP BY k.id");
?>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Kelola Kategori</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Tambah Kategori -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Tambah Kategori Baru</h3>
                </div>
                    <form action="../db/db-add-categories.php" method="POST" class="p-3">
                    <div class="form-group mb-3">
                        <label for="nama_kategori">Nama Kategori:</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Kategori</button>
                </form>
            </div>
        </div>

        <!-- Tabel Kategori -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Daftar Kategori</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Jumlah Produk</th>
                            <th>Terakhir Diupdate</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['nama_kategori']); ?></td>
                            <td><?php echo $category['product_count']; ?> produk</td>
                            <td><?php echo date('d F Y H:i:s', strtotime($category['updated_at'])); ?></td>
                            <td class="text-center">
                                <a href="#" onclick="editCategory(<?= $category['id']; ?>, '<?= htmlspecialchars($category['nama_kategori']); ?>')" class="me-2 action-icon" title="Edit">
                                    <i class='bx bxs-edit text-blue' style="font-size:18px;"></i>
                                </a>
                                <?php if ($category['product_count'] == 0): ?>
                                    <a href="../db/db-delete-categories.php?id=<?= $category['id']; ?>" class="action-icon" 
                                       onclick="return confirm('Yakin ingin menghapus kategori ini?');" title="Hapus">
                                        <i class='bx bxs-trash text-red' style="font-size:18px;"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="action-icon disabled-delete" title="Tidak dapat dihapus — digunakan oleh <?= $category['product_count']; ?> produk">
                                        <i class='bx bxs-trash text-red' style="font-size:18px;"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Edit Kategori -->
        <div class="modal" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Edit Kategori</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../db/db-update-categories.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_category_id" name="category_id">
                    <div class="form-group">
                        <label for="edit_nama_kategori" class="text-white">Nama Kategori</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" 
                               id="edit_nama_kategori" 
                               name="nama_kategori" 
                               required 
                               style="outline: none; /* Menghilangkan outline */
                                      box-shadow: none; /* Menghilangkan shadow */"> 
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Menyembunyikan tanda asterisk (*) dari required fields */
input:required {
    box-shadow: none;
}
input:invalid {
    box-shadow: none;
}
/* Mengubah warna placeholder jika diperlukan */
input::placeholder {
    color: #6c757d !important;
}
</style>
    </main>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editCategoryModal');

    function openModal() {
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }

    // Close when clicking on elements that have data-bs-dismiss="modal" or btn-close
    modal.querySelectorAll('[data-bs-dismiss="modal"], .btn-close').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    // Close when clicking outside dialog
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Expose function to global scope for inline onclick handlers
    window.editCategory = function (id, nama) {
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_nama_kategori').value = nama;
        openModal();
    }
});
</script>