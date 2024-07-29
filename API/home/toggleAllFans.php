<?php
include '../../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

// Toggle logic here (e.g., get current state, toggle it, update the database)
$query = "SELECT state FROM fan_status";
$stmt = $connection->prepare($query);
$stmt->execute();
$fanStates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$newState = (array_sum(array_column($fanStates, 'state')) > 0) ? 0 : 1;

$updateQuery = "UPDATE fan_status SET state = :state";
$updateStmt = $connection->prepare($updateQuery);
$updateStmt->bindParam(':state', $newState);
$updateStmt->execute();

echo "All fans toggled to state $newState";
?>
