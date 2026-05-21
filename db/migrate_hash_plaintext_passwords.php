<?php
require_once(__DIR__ . '/db-connection.php');
header('Content-Type: text/plain; charset=utf-8');

echo "Hashing plaintext user passwords...\n\n";

$query = "SELECT id, username, password FROM users";
$result = $conn->query($query);
if (!$result) {
    die("Failed to query users: " . $conn->error . "\n");
}

$updated = 0;
while ($row = $result->fetch_assoc()) {
    $password = $row['password'];
    $id = intval($row['id']);
    $username = $row['username'];

    if ($password === null || $password === '') {
        echo "- Skip user $username (empty password)\n";
        continue;
    }

    // Detect if already hashed with bcrypt or argon2
    if (strpos($password, '$2y$') === 0 || strpos($password, '$2a$') === 0 || strpos($password, '$argon2') === 0) {
        echo "- Skip user $username (already hashed)\n";
        continue;
    }

    $new_hash = password_hash($password, PASSWORD_DEFAULT);
    if ($new_hash === false) {
        echo "- Failed to hash password for $username\n";
        continue;
    }

    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    if (!$stmt) {
        echo "- Failed to prepare update for $username: " . $conn->error . "\n";
        continue;
    }
    $stmt->bind_param('si', $new_hash, $id);
    if ($stmt->execute()) {
        echo "- Updated user $username\n";
        $updated++;
    } else {
        echo "- Failed to update user $username: " . $stmt->error . "\n";
    }
    $stmt->close();
}

echo "\nDone. Total passwords hashed: $updated\n";
$conn->close();
?>
