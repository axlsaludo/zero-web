<?php
include '../../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

$fanIndex = $_POST['fanIndex'];
$state = $_POST['state'];

$query = "UPDATE fan_status SET state = :state WHERE fan_index = :fanIndex";
$stmt = $connection->prepare($query);
$stmt->bindParam(':state', $state);
$stmt->bindParam(':fanIndex', $fanIndex);
$stmt->execute();

echo "Fan $fanIndex state updated to $state";
?>
