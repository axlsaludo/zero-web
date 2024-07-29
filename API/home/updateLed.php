<?php
include '../../db/Database.php';

$db = new Database();
$connection = $db->getConnection();

$ledIndex = $_POST['ledIndex'];
$state = $_POST['state'];

$query = "UPDATE led_status SET state = :state WHERE led_index = :ledIndex";
$stmt = $connection->prepare($query);
$stmt->bindParam(':state', $state);
$stmt->bindParam(':ledIndex', $ledIndex);
$stmt->execute();

echo "LED $ledIndex state updated to $state";
?>
