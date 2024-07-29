<?php
session_start(); // Start the session

// Include the database connection
require_once '../db/dbconn.php';

$database = new Database();
$conn = $database->getConnection();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in
    header('Location: ../login.php'); // Redirect to login page
    exit();
}

$user_id = $_SESSION['user_id'];

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
