<?php

// Include the database connection
require_once '../db/dbconn.php';

$database = new Database();
$conn = $database->getConnection();

// Check if the user is approved
try {
    $stmt = $conn->prepare("SELECT is_approved FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !$user['is_approved']) {
        // User is not approved
        header('Location: ../index.html'); // Redirect to not approved page
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
