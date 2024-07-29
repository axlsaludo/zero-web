<?php
// Adjust the path based on your file structure
require_once '../../db/dbconn.php';

$database = new Database();
$conn = $database->getConnection();

try {
    // Fetch registered users
    $stmt = $conn->prepare("SELECT id, first_name, last_name, is_approved FROM users");
    $stmt->execute();
    $registeredUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($registeredUsers) {
        echo '<ul>';
        foreach ($registeredUsers as $user) {
            echo '<li>' . $user['id'] . ' | ' . $user['first_name'] . ' ' . $user['last_name'];
            if (!$user['is_approved']) {
                // Add the "Approve" button
                echo ' <form action="/axl.com/API/dashboard/approveUser.php" method="post" style="display:inline;">
                        <input type="hidden" name="userId" value="' . htmlspecialchars($user['id']) . '" />
                        <button type="submit" name="approve">Approve</button>
                      </form>';
            } else {
                echo ' <span>Approved</span>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No registered users found.</p>';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
