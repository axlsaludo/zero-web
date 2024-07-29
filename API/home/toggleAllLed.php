<?php
include '../../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

// Toggle logic here (e.g., get current state, toggle it, update the database)
$query = "SELECT state FROM led_status";
$stmt = $connection->prepare($query);
$stmt->execute();
$ledStates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$newState = (array_sum(array_column($ledStates, 'state')) > 0) ? 0 : 1;

$updateQuery = "UPDATE led_status SET state = :state";
$updateStmt = $connection->prepare($updateQuery);
$updateStmt->bindParam(':state', $newState);
$updateStmt->execute();

echo "All LEDs toggled to state $newState";
?>
