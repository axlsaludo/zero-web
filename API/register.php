<?php
session_start();
include '../db/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
    
    try {
        // Prepare SQL statement to insert data
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) 
                                VALUES (:first_name, :last_name, :email, :password)");
        
        // Bind parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        
        // Execute the statement
        $stmt->execute();
        
        header("Location: ../pages/login.html");
        exit; // Ensure script stops here to prevent further execution
        
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect to login page if accessed without POST method
    header("Location: ../pages/login.html");
    exit;
}
?>
