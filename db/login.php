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

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        
        // Update user status to active
        $stmt = $conn->prepare("UPDATE users SET is_active = 1 WHERE id = :id");
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        // Successful login response
        echo json_encode(array("success" => "Login successful."));
        exit;
    } else {
        // Failed login response
        echo json_encode(array("error" => "Invalid email or password."));
        exit;
    }
}
?>
