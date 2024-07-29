<?php
include '../../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

$state = $_POST['state'];

$query = "UPDATE system_settings SET garage_auto_mode = :state WHERE id = 1";
$stmt = $connection->prepare($query);
$stmt->bindParam(':state', $state);
$stmt->execute();

echo "Garage auto mode updated to $state";
?>
