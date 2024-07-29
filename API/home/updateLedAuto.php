<?php
include '../../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

$state = $_POST['state'];

$query = "UPDATE system_settings SET led_auto_mode = :state WHERE id = 1";
$stmt = $connection->prepare($query);
$stmt->bindParam(':state', $state);
$stmt->execute();

echo "LED auto mode updated to $state";
?>
