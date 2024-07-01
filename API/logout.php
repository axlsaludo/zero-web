<?php
session_start();

// Include the dbconn.php file for database connection
include '../db/dbconn.php';

try {
    $database = new Database();
    $conn = $database->getConnection(); // Obtain the PDO connection object

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // Prepare and execute the SQL statement to update user status to inactive
        $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();

        // Destroy the session
        session_destroy();
    }

    // Redirect to login page
    header("Location: ../pages/login.html");
    exit;

} catch (PDOException $e) {
    // Handle database connection or query errors
    echo "Error: " . $e->getMessage();
    exit;
}
?>
