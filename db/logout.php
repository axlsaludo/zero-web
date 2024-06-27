<?php
session_start();
include 'dbconn.php';

if (isset($_SESSION['user_id'])) {
    // Update user status to inactive
    $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();

    // Destroy the session
    session_destroy();
}

header("Location: ../pages/login.html");
exit;
?>
