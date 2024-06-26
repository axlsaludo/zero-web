<?php
// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "accounts"; // Replace with your database name

// Function to create database and table if they don't exist
function createDatabaseAndTableIfNeeded($conn, $dbname) {
    try {
        // Create database if not exists
        $sqlCreateDatabase = "CREATE DATABASE IF NOT EXISTS $dbname";
        $conn->exec($sqlCreateDatabase);

        // Select database
        $conn->exec("USE $dbname");

        // Create table if not exists
        $createTableSQL = "CREATE TABLE IF NOT EXISTS users (
                            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            first_name VARCHAR(50) NOT NULL,
                            last_name VARCHAR(50) NOT NULL,
                            email VARCHAR(100) NOT NULL UNIQUE,
                            password VARCHAR(255) NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                          )";

        $conn->exec($createTableSQL);

    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Establish database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Call function to create database and table if they don't exist
    createDatabaseAndTableIfNeeded($conn, $dbname);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle registration form submission
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
}
?>
