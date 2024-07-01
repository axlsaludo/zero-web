<?php
session_start();
include '../db/dbconn.php'; // Include the Database class definition

$database = new Database();
$db = $database->getConnection(); // Obtain the PDO connection object

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare SQL statement to fetch user from database
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];

                // Update user status to active (optional)
                $stmt = $db->prepare("UPDATE users SET is_active = 1 WHERE id = :id");
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();

                // Check if user is admin
                if ($user['is_admin']) {
                    $_SESSION['is_admin'] = true;
                    header("Location: ../pages/dashboard.html");
                } else {
                    header("Location: ../pages/landing.html");
                }
                exit;
            } else {
                header("Location: ../pages/login.html");
                exit;
            }
        } else {
            header("Location: ../pages/login.html");
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>
