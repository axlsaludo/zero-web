<?php
// Adjust the path based on your file structure
require_once '../../db/dbconn.php';

$database = new Database();
$conn = $database->getConnection();

if (isset($_POST['approve']) && isset($_POST['userId'])) {
    $userId = intval($_POST['userId']);
    $adminId = 1; // Replace with the actual admin ID, or get it from session data

    try {
        // Update the user's status to 'approved'
        $stmt = $conn->prepare("UPDATE users SET is_approved = 1, approved_at = NOW(), approved_by = :adminId WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':adminId', $adminId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Optionally redirect back to the dashboard or show a success message
        header('Location: ../dashboard.php'); // Adjust the path to redirect
        exit();
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
