<?php
header('Content-Type: application/json');
require_once '../db/Database.php';
require_once '../models/Users.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Debug: Log the received data
file_put_contents('php://stderr', print_r($data, true));

// Define table and columns
$table = 'sensor_data'; // Replace with your table name
$columns = ['temperature', 'humidity', 'ir', 'ldr'];
$values = [
    'temperature' => $data['temperature'] ?? null,
    'humidity' => $data['humidity'] ?? null,
    'ir' => $data['ir'] ?? null,
    'ldr' => $data['ldr'] ?? null
];

// Check for null values
foreach ($values as $key => $value) {
    if ($value === null) {
        file_put_contents('php://stderr', "Value for $key is null\n", FILE_APPEND);
    }
}

// Insert data into database and handle errors
try {
    if ($user->insertData($table, $columns, $values)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insert failed']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    file_put_contents('php://stderr', "Database error: " . $e->getMessage() . "\n", FILE_APPEND);
}
?>
