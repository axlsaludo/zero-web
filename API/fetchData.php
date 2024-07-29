<?php
header('Content-Type: application/json');
require_once '../db/Database.php';
require_once '../models/Users.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

$table = 'sensor_data'; // Replace with your table name
$query = "SELECT * FROM $table";
$stmt = $connection->prepare($query);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>