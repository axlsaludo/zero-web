<?php
session_start();
include 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];

            // Update user status to active
            $stmt = $conn->prepare("UPDATE users SET is_active = 1 WHERE id = :id");
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();

            // Check if user is admin
            if ($user['is_admin']) {
                $_SESSION['is_admin'] = true;
                header("Location: ../pages/dashboard.html"); // Redirect admin to dashboard
            } else {
                header("Location: ../pages/landing.html"); // Redirect normal user to landing page
            }
            exit;
        } else {
            header("Location: ../pages/login.html"); 
        }
    } else {
        header("Location: ../pages/login.html"); 
    }
}
?>
