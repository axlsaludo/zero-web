<?php
include '../db/dbconn.php';

// Fetch active users
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE is_active = 1");
$stmt->execute();
$activeUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($activeUsers) {
    echo '<ul>';
    foreach ($activeUsers as $user) {
        echo '<li>Status: Online | ' . $user['first_name'] . ' ' . $user['last_name'] . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No active users found.</p>';
}
?>
