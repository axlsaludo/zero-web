<?php
header('Content-Type: application/json');
require_once '../db/Database.php';
require_once '../models/Users.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

$data = json_decode(file_get_contents('php://input'), true);

$table = 'sensor_data'; // Replace with your table name
$columns = ['temperature', 'humidity', 'ir', 'ldr'];
$values = [
    'temperature' => $data['temperature'] ?? null,
    'humidity' => $data['humidity'] ?? null,
    'ir' => $data['ir'] ?? null,
    'ldr' => $data['ldr'] ?? null
];

if ($user->insertData($table, $columns, $values)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>