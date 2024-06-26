<?php
// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "accounts"; // Replace with your database name

// Establish database connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle login form submission
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
                header("Location: ../pages/landing.php");
                exit; 
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User with that email does not exist.";
        }
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
