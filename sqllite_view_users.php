<?php
// File: sqllite_view_users.php
// Script sederhana untuk menampilkan data user dari database SQLite Laravel

$dbPath = __DIR__ . '/database/database.sqlite';
if (!file_exists($dbPath)) {
    die('Database file not found: ' . $dbPath);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query('SELECT * FROM users LIMIT 10');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$users) {
        echo "No users found in the database.";
    } else {
        echo "<h2>Users Table (first 10 rows)</h2>";
        echo "<table border='1' cellpadding='5'><tr>";
        foreach (array_keys($users[0]) as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr>";
        foreach ($users as $user) {
            echo "<tr>";
            foreach ($user as $val) {
                echo "<td>" . htmlspecialchars($val) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
