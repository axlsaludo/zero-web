<?php
// Adjust the path based on your file structure
require_once '../../db/dbconn.php';

$database = new Database();
$conn = $database->getConnection();

try {
    // Fetch registered users
    $stmt = $conn->prepare("SELECT id, first_name, last_name FROM users");
    $stmt->execute();
    $registeredUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($registeredUsers) {
        echo '<ul>';
        foreach ($registeredUsers as $user) {
            echo '<li>' . $user['id'] . ' | ' . $user['first_name'] . ' ' . $user['last_name'] . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No registered users found.</p>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
