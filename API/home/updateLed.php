<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
