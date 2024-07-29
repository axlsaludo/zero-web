<?php
header('Content-Type: application/json');
require_once '../db/Database.php';
require_once '../models/Users.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

$data = json_decode(file_get_contents('php://input'), true);

// Return the data as JSON for display
echo json_encode(['status' => 'success', 'data' => $data]);

// Optional: Store the data in the database
$table = 'sensor_data'; // Replace with your table name
$columns = ['temperature', 'humidity', 'ir', 'ldr'];
$values = [
    'temperature' => $data['temperature'] ?? null,
    'humidity' => $data['humidity'] ?? null,
    'ir' => $data['ir'] ?? null,
    'ldr' => $data['ldr'] ?? null
];

$user->insertData($table, $columns, $values);
?>
