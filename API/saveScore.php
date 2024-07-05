<?php
// Include the Database class
require_once 'Database.php';

try {
    // Create a new instance of the Database class
    $database = new Database();

    // Get PDO connection from the Database instance
    $pdo = $database->getConnection();

    // Get the score from the POST request
    $score = $_POST['score'];

    // Assuming you have a user ID from the session or some other method
    $user_id = $_SESSION['user_id']; // Example: replace this with your actual user ID source

    // Insert the score into the database
    $stmt = $pdo->prepare("INSERT INTO leaderboard_scores (user_id, score) VALUES (:user_id, :score)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':score', $score);
    if ($stmt->execute()) {
        echo "Score saved successfully";
    } else {
        echo "Error saving score";
    }

} catch (Exception $e) {
    echo "Connection failed or query error: " . $e->getMessage();
    exit;
}
?>
