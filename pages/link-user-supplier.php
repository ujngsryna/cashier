<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['level'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

include '../layout/header.php';
require_once('../db/db-connection.php');

$users = mysqli_query($conn, "SELECT id, username, supplier_id FROM users ORDER BY username");
$suppliers = mysqli_query($conn, "SELECT id, name FROM suppliers ORDER BY name");

?>
<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <ul class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Link User ↔ Supplier</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head"><h3>Link User to Supplier</h3></div>

                <form action="../db/db-link-user-supplier.php" method="post">
                    <label for="user_id">User</label>
                    <select name="user_id" id="user_id" required>
                        <option value="">-- Select User --</option>
                        <?php while ($u = mysqli_fetch_assoc($users)): ?>
                            <option value="<?= $u['id']; ?>"><?= htmlspecialchars($u['username']); ?><?= $u['supplier_id'] ? ' (linked)' : ''; ?></option>
                        <?php endwhile; ?>
                    </select>

                    <label for="supplier_id">Supplier</label>
                    <select name="supplier_id" id="supplier_id">
                        <option value="">-- Unlink / Select Supplier --</option>
                        <?php while ($s = mysqli_fetch_assoc($suppliers)): ?>
                            <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['name']); ?></option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit" name="link">Save Link</button>
                </form>

                <hr>
                <h4>Current Links</h4>
                <table>
                    <thead><tr><th>User</th><th>Supplier</th></tr></thead>
                    <tbody>
                    <?php
                    // Re-query to show current links
                    $res = mysqli_query($conn, "SELECT u.username, s.name AS supplier_name FROM users u LEFT JOIN suppliers s ON u.supplier_id = s.id ORDER BY u.username");
                    while ($row = mysqli_fetch_assoc($res)):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['supplier_name'] ?? '—'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </main>
</section>

<style>
    label {display:block;margin-top:10px;font-weight:bold}
    select, button {padding:8px;margin-top:6px}
    table {width:100%;border-collapse:collapse;margin-top:12px}
    table th, table td {border:1px solid #ddd;padding:8px}
</style>

<?php include '../layout/footer.php'; ?>
