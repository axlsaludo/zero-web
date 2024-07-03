<?php
session_start();

// Include the User class definition and Database connection
require_once('../db/dbconn.php');
require_once('../models/User.php');

try {
    // Establish database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Initialize User model
    $userModel = new User($conn);

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // Update user status to inactive
        $userModel->updateUserStatus($_SESSION['user_id'], 0);

        // Destroy the session
        session_destroy();
    }

    // Redirect to login page
    header("Location: ../pages/login.html");
    exit;

} catch (Exception $e) {
    // Handle database connection or query errors
    echo "Error: " . $e->getMessage();
    exit;
}
?>
