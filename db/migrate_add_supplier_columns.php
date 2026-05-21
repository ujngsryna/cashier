<?php
// Safe migration script: add supplier_id and warehouse_stock columns if missing.
require_once(__DIR__ . '/db-connection.php');

header('Content-Type: text/plain; charset=utf-8');

$checks = [
    [
        'table' => 'products',
        'column' => 'warehouse_stock',
        'alter' => "ALTER TABLE products ADD COLUMN warehouse_stock INT(11) NOT NULL DEFAULT 0"
    ],
    [
        'table' => 'products',
        'column' => 'supplier_id',
        'alter' => "ALTER TABLE products ADD COLUMN supplier_id INT(11) DEFAULT NULL"
    ],
    [
        'table' => 'users',
        'column' => 'supplier_id',
        'alter' => "ALTER TABLE users ADD COLUMN supplier_id INT(11) DEFAULT NULL"
    ],
];

echo "Running safe migration checks...\n\n";

foreach ($checks as $c) {
    $table = $c['table'];
    $column = $c['column'];
    $alter = $c['alter'];

    $res = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($res === false) {
        echo "ERROR: cannot inspect table $table: " . $conn->error . "\n";
        continue;
    }

    if ($res->num_rows > 0) {
        echo "OK: $table.$column already exists\n";
        continue;
    }

    echo "Adding $table.$column... ";
    if ($conn->query($alter) === true) {
        echo "DONE\n";
    } else {
        echo "FAILED - " . $conn->error . "\n";
    }
}

echo "\nMigration finished. Please verify in phpMyAdmin or by reloading the application pages.\n";

echo "If any ALTER failed, import 'database/db_cashier.sql' manually.\n";

// Close connection if present
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

?>
