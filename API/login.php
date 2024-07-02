<?php
session_start();
include '../db/dbconn.php'; // Include the Database class definition
include '../models/User.php'; // Include the User class definition

$database = new Database();
$db = $database->getConnection(); // Obtain the PDO connection object

$userModel = new User($db); // Instantiate User class

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Fetch user from database using User class method
        $user = $userModel->getUserByEmail($email);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];

                // Update user status to active
                $userModel->updateUserStatus($user['id'], 1);

                // Check if user is admin
                if ($user['is_admin']) {
                    $_SESSION['is_admin'] = true;
                    header("Location: ../pages/dashboard.html");
                } else {
                    header("Location: ../pages/landing.html");
                }
                exit;
            } else {
                header("Location: ../pages/login.html"); // Invalid password
                exit;
            }
        } else {
            header("Location: ../pages/login.html"); // User not found
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>